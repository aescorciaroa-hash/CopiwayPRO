<?php
/**
 * Modelo Pedido.
 */
class Pedido
{
    private $conn;

    public const ESTADOS = ['pendiente', 'en_preparacion', 'listo', 'en_camino', 'entregado', 'cancelado'];

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function codigo(array $pedido): string
    {
        $prefix = ($pedido['canal_origen'] ?? 'web') === 'web' ? 'ORD' : 'MAN';
        $hex = str_replace('-', '', $pedido['id_pedido']);
        $num = strtoupper(substr($hex, -4));
        return "#{$prefix}-{$num}";
    }

    public function find($id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM PEDIDO WHERE id_pedido = ? LIMIT 1");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function kpis(string $desde, string $hasta): array
    {
        $stmt = $this->conn->prepare(
            "SELECT
                COALESCE(SUM(p.total),0) AS ventas,
                COUNT(*) AS ordenes,
                COALESCE(AVG(p.total),0) AS ticket
             FROM PEDIDO p
             WHERE p.estado <> 'cancelado' AND p.fecha_hora BETWEEN ? AND ?"
        );
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: ['ventas' => 0, 'ordenes' => 0, 'ticket' => 0];
    }

    public function ventasPorDia(string $desde, string $hasta): array
    {
        $stmt = $this->conn->prepare(
            "SELECT DATE(fecha_hora) AS dia, SUM(total) AS total
             FROM PEDIDO
             WHERE estado <> 'cancelado' AND fecha_hora BETWEEN ? AND ?
             GROUP BY DATE(fecha_hora) ORDER BY dia"
        );
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    public function rankingProductos(string $desde, string $hasta, int $limite = 5): array
    {
        $stmt = $this->conn->prepare(
            "SELECT pr.nombre, SUM(dp.cantidad) AS unidades, SUM(dp.cantidad * dp.precio_unitario) AS total
             FROM DETALLE_PEDIDO dp
             JOIN PEDIDO p ON p.id_pedido = dp.id_pedido
             JOIN PRODUCTO pr ON pr.id_producto = dp.id_producto
             WHERE p.estado <> 'cancelado' AND p.fecha_hora BETWEEN ? AND ?
             GROUP BY pr.id_producto, pr.nombre
             ORDER BY unidades DESC LIMIT {$limite}"
        );
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    public function contarPorEstado(): array
    {
        $res = $this->conn->query(
            "SELECT estado, COUNT(*) AS n FROM PEDIDO
             WHERE estado IN ('pendiente','en_preparacion','listo','en_camino')
             GROUP BY estado"
        );
        $rows = $res->fetch_all(MYSQLI_ASSOC);
        $out = ['pendiente' => 0, 'en_preparacion' => 0, 'listo' => 0, 'en_camino' => 0];
        foreach ($rows as $r) { $out[$r['estado']] = (int) $r['n']; }
        return $out;
    }

    public function activos(): array
    {
        $res = $this->conn->query(
            "SELECT p.*, cl.nombre AS cliente_nombre, cl.telefono AS cliente_telefono,
                    d.nombre AS domiciliario_nombre, d.placa, d.tipo_vehiculo,
                    pg.metodo AS pago_metodo, pg.estado AS pago_estado,
                    TIMESTAMPDIFF(MINUTE, p.fecha_hora, NOW()) AS minutos
             FROM PEDIDO p
             JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente
             LEFT JOIN DOMICILIARIO d ON d.id_domiciliario = p.id_domiciliario
             LEFT JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             WHERE p.estado IN ('pendiente','en_preparacion','listo','en_camino')
             ORDER BY p.fecha_hora ASC"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function delTurno(): array
    {
        $res = $this->conn->query(
            "SELECT p.*, cl.nombre AS cliente_nombre, cl.telefono AS cliente_telefono,
                    d.nombre AS domiciliario_nombre, d.placa, d.tipo_vehiculo,
                    pg.metodo AS pago_metodo, pg.estado AS pago_estado,
                    TIMESTAMPDIFF(MINUTE, p.fecha_hora, NOW()) AS minutos
             FROM PEDIDO p
             JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente
             LEFT JOIN DOMICILIARIO d ON d.id_domiciliario = p.id_domiciliario
             LEFT JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             WHERE p.estado IN ('pendiente','en_preparacion','listo','en_camino')
                OR p.fecha_hora >= (NOW() - INTERVAL 18 HOUR)
             ORDER BY FIELD(p.estado,'en_camino','listo','en_preparacion','pendiente','entregado','cancelado'),
                      p.fecha_hora DESC"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function recientes(int $limite = 8): array
    {
        $res = $this->conn->query(
            "SELECT p.*, cl.nombre AS cliente_nombre, pg.metodo AS pago_metodo
             FROM PEDIDO p
             JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente
             LEFT JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             ORDER BY p.fecha_hora DESC LIMIT {$limite}"
        );
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    public function detalle(string $idPedido): array
    {
        $stmt = $this->conn->prepare(
            "SELECT dp.*, pr.nombre FROM DETALLE_PEDIDO dp
             JOIN PRODUCTO pr ON pr.id_producto = dp.id_producto
             WHERE dp.id_pedido = ?"
        );
        $stmt->bind_param("s", $idPedido);
        $stmt->execute();
        $lineas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        foreach ($lineas as &$l) {
            $stmtP = $this->conn->prepare(
                "SELECT pe.id_ingrediente, pe.accion_modificacion, pe.costo_aplicado, i.nombre
                 FROM PERSONALIZACION pe JOIN INGREDIENTE i ON i.id_ingrediente = pe.id_ingrediente
                 WHERE pe.id_detalle = ?"
            );
            $stmtP->bind_param("s", $l['id_detalle']);
            $stmtP->execute();
            $l['personalizaciones'] = $stmtP->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmtP->close();
        }
        return $lineas;
    }

    public function completo(string $idPedido): ?array
    {
        $stmt = $this->conn->prepare(
            "SELECT p.*, cl.nombre AS cliente_nombre, cl.telefono AS cliente_telefono, cl.correo AS cliente_correo,
                    d.nombre AS domiciliario_nombre, d.placa, d.tipo_vehiculo, d.telefono AS domiciliario_telefono,
                    pg.metodo AS pago_metodo, pg.estado AS pago_estado
             FROM PEDIDO p
             JOIN CLIENTE cl ON cl.id_cliente = p.id_cliente
             LEFT JOIN DOMICILIARIO d ON d.id_domiciliario = p.id_domiciliario
             LEFT JOIN PAGO pg ON pg.id_pedido = p.id_pedido
             WHERE p.id_pedido = ?"
        );
        $stmt->bind_param("s", $idPedido);
        $stmt->execute();
        $p = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$p) return null;
        $p['lineas'] = $this->detalle($idPedido);
        return $p;
    }
}

