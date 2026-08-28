<?php
/**
 * Modelo CierreCaja.
 */
class CierreCaja
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function calcular(string $fecha): array
    {
        $desde = $fecha . ' 00:00:00';
        $hasta = $fecha . ' 23:59:59';

        $stmtT = $this->conn->prepare(
            "SELECT
                COALESCE(SUM(p.total),0) AS total_ventas,
                COUNT(*) AS total_ordenes,
                COALESCE(SUM(CASE WHEN pg.metodo = 'efectivo' THEN p.total ELSE 0 END),0) AS total_efectivo,
                COALESCE(SUM(CASE WHEN pg.metodo = 'digital'  THEN p.total ELSE 0 END),0) AS total_digital
             FROM PEDIDO p
             LEFT JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             WHERE p.estado <> 'cancelado' AND p.fecha_hora BETWEEN ? AND ?"
        );
        $stmtT->bind_param("ss", $desde, $hasta);
        $stmtT->execute();
        $totales = $stmtT->get_result()->fetch_assoc();
        $stmtT->close();

        $stmtE = $this->conn->prepare(
            "SELECT i.id_ingrediente, i.nombre, i.unidad_medida, i.cantidad_stock AS stock_real,
                    SUM(r.cantidad_necesaria * dp.cantidad) AS consumido
             FROM DETALLE_PEDIDO dp
             JOIN PEDIDO p ON p.id_pedido = dp.id_pedido
             JOIN RECETA r ON r.id_producto = dp.id_producto
             JOIN INGREDIENTE i ON i.id_ingrediente = r.id_ingrediente
             WHERE p.estado <> 'cancelado' AND p.fecha_hora BETWEEN ? AND ?
             GROUP BY i.id_ingrediente, i.nombre, i.unidad_medida, i.cantidad_stock
             ORDER BY consumido DESC"
        );
        $stmtE->bind_param("ss", $desde, $hasta);
        $stmtE->execute();
        $escandallo = $stmtE->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtE->close();

        $stmtL = $this->conn->prepare(
            "SELECT d.id_domiciliario, d.nombre, d.base_efectivo_asignada,
                    COUNT(p.id_pedido) AS entregas,
                    COALESCE(SUM(p.total),0) AS recaudo
             FROM DOMICILIARIO d
             JOIN PEDIDO p ON p.id_domiciliario = d.id_domiciliario
             JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             WHERE pg.metodo = 'efectivo' AND p.estado = 'entregado' AND p.fecha_hora BETWEEN ? AND ?
             GROUP BY d.id_domiciliario, d.nombre, d.base_efectivo_asignada"
        );
        $stmtL->bind_param("ss", $desde, $hasta);
        $stmtL->execute();
        $liquidaciones = $stmtL->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtL->close();

        foreach ($liquidaciones as &$l) {
            $l['total_entregar'] = (float) $l['base_efectivo_asignada'] + (float) $l['recaudo'];
        }

        $stmtA = $this->conn->prepare(
            "SELECT COUNT(*) as cant FROM PEDIDO WHERE estado IN ('pendiente','en_preparacion','listo','en_camino')
             AND fecha_hora BETWEEN ? AND ?"
        );
        $stmtA->bind_param("ss", $desde, $hasta);
        $stmtA->execute();
        $resA = $stmtA->get_result()->fetch_assoc();
        $stmtA->close();
        $activasSinEntregar = (int) ($resA['cant'] ?? 0);

        $pedidoModel = new Pedido();
        $lunes = date('Y-m-d', strtotime('monday this week'));

        return [
            'fecha'              => $fecha,
            'totales'            => $totales,
            'escandallo'         => $escandallo,
            'liquidaciones'      => $liquidaciones,
            'ventasSemana'       => $pedidoModel->ventasPorDia($lunes, $hasta),
            'activasSinEntregar' => $activasSinEntregar,
        ];
    }

    public function generar(string $fecha, string $idAdmin): string
    {
        $data = $this->calcular($fecha);
        $this->conn->begin_transaction();
        try {
            $stmtEx = $this->conn->prepare("SELECT id_reporte FROM REPORTE_CAJA WHERE id_admin = ? AND fecha = ?");
            $stmtEx->bind_param("ss", $idAdmin, $fecha);
            $stmtEx->execute();
            $existente = $stmtEx->get_result()->fetch_assoc();
            $stmtEx->close();

            if ($existente) {
                $idReporte = $existente['id_reporte'];

                $stmtDelA = $this->conn->prepare("DELETE FROM DETALLE_AUDITORIA WHERE id_reporte = ?");
                $stmtDelA->bind_param("s", $idReporte);
                $stmtDelA->execute();
                $stmtDelA->close();

                $stmtDelL = $this->conn->prepare("DELETE FROM LIQUIDACION_DOMICILIARIO WHERE id_reporte = ?");
                $stmtDelL->bind_param("s", $idReporte);
                $stmtDelL->execute();
                $stmtDelL->close();

                $stmtUp = $this->conn->prepare(
                    "UPDATE REPORTE_CAJA SET total_ventas = ?, total_efectivo = ?, total_digital = ? WHERE id_reporte = ?"
                );
                $v = (float) $data['totales']['total_ventas'];
                $e = (float) $data['totales']['total_efectivo'];
                $d = (float) $data['totales']['total_digital'];
                $stmtUp->bind_param("ddds", $v, $e, $d, $idReporte);
                $stmtUp->execute();
                $stmtUp->close();
            } else {
                $idReporte = uuid();
                $stmtIns = $this->conn->prepare(
                    "INSERT INTO REPORTE_CAJA (id_reporte, id_admin, fecha, total_ventas, total_efectivo, total_digital)
                     VALUES (?,?,?,?,?,?)"
                );
                $v = (float) $data['totales']['total_ventas'];
                $e = (float) $data['totales']['total_efectivo'];
                $d = (float) $data['totales']['total_digital'];
                $stmtIns->bind_param("sssddd", $idReporte, $idAdmin, $fecha, $v, $e, $d);
                $stmtIns->execute();
                $stmtIns->close();
            }

            foreach ($data['escandallo'] as $esc) {
                $teorico = (float) $esc['stock_real'];
                $stmtAud = $this->conn->prepare(
                    "INSERT INTO DETALLE_AUDITORIA (id_auditoria, id_reporte, id_ingrediente, nombre, stock_teorico, stock_real)
                     VALUES (?,?,?,?,?,?)"
                );
                $idAud = uuid();
                $stmtAud->bind_param("ssssdd", $idAud, $idReporte, $esc['id_ingrediente'], $esc['nombre'], $teorico, $teorico);
                $stmtAud->execute();
                $stmtAud->close();
            }

            foreach ($data['liquidaciones'] as $liq) {
                $stmtLiq = $this->conn->prepare(
                    "INSERT INTO LIQUIDACION_DOMICILIARIO
                       (id_liquidacion, id_reporte, id_domiciliario, base_asignada, efectivo_recolectado, efectivo_liquidado)
                     VALUES (?,?,?,?,?,?)"
                );
                $idL = uuid();
                $base = (float) $liq['base_efectivo_asignada'];
                $rec = (float) $liq['recaudo'];
                $tot = (float) $liq['total_entregar'];
                $stmtLiq->bind_param("sssddd", $idL, $idReporte, $liq['id_domiciliario'], $base, $rec, $tot);
                $stmtLiq->execute();
                $stmtLiq->close();
            }

            $stmtP = $this->conn->prepare(
                "UPDATE PEDIDO SET id_reporte = ?
                 WHERE id_reporte IS NULL AND estado = 'entregado'
                   AND fecha_hora BETWEEN ? AND ?"
            );
            $desde = $fecha . ' 00:00:00';
            $hasta = $fecha . ' 23:59:59';
            $stmtP->bind_param("sss", $idReporte, $desde, $hasta);
            $stmtP->execute();
            $stmtP->close();

            $this->conn->commit();
            return $idReporte;
        } catch (\Throwable $ex) {
            $this->conn->rollback();
            throw $ex;
        }
    }

    public function obtener(string $fecha): array
    {
        return $this->calcular($fecha);
    }

    public function recientes(int $limite = 10): array
    {
        $res = $this->conn->query("SELECT * FROM REPORTE_CAJA ORDER BY fecha DESC LIMIT {$limite}");
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

