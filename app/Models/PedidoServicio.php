<?php
/**
 * Modelo PedidoServicio (creacion, estados, pago).
 */
class PedidoServicio
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function clienteParaManual(string $nombre, string $telefono): string
    {
        $stmt = $this->conn->prepare("SELECT id_cliente FROM CLIENTE WHERE telefono = ? LIMIT 1");
        $stmt->bind_param("s", $telefono);
        $stmt->execute();
        $cli = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($cli) {
            return $cli['id_cliente'];
        }

        $id = uuid();
        $stmtIns = $this->conn->prepare(
            "INSERT INTO CLIENTE (id_cliente, nombre, telefono, correo, contrasena, direccion,
                                  fecha_nacimiento, puntos_fidelidad, fecha_aceptacion_habeas_data)
             VALUES (?,?,?,?,?,?,?,0,NOW())"
        );
        $nom = $nombre ?: 'Cliente manual';
        $email = $telefono . '@manual.copiway';
        $pass = password_hash(uuid(), PASSWORD_BCRYPT);
        $dir = null;
        $fechaNac = '2000-01-01';

        $stmtIns->bind_param("sssssss", $id, $nom, $telefono, $email, $pass, $dir, $fechaNac);
        $stmtIns->execute();
        $stmtIns->close();

        return $id;
    }

    public function crear(array $datos): string
    {
        $this->conn->begin_transaction();
        try {
            $configModel = new Configuracion();
            $config = $configModel->get();
            $tarifa = (float) ($config['tarifa_plana_domicilio'] ?? 0);

            $productoModel = new Producto();

            $subtotal = 0.0;
            foreach ($datos['lineas'] as $l) {
                $prod = $productoModel->find($l['id_producto']);
                $precio = (float) $prod['precio'];
                $extras = 0.0;
                foreach ($l['personalizaciones'] ?? [] as $p) {
                    if (($p['accion'] ?? '') === 'agregar') $extras += (float) ($p['costo'] ?? 0);
                }
                $subtotal += ($precio + $extras) * (int) $l['cantidad'];
            }

            $descuento = (float) ($datos['descuento_cumpleanos'] ?? 0);
            $total = max(0, $subtotal - $descuento) + $tarifa;

            $idPedido = uuid();
            $canal = $datos['canal_origen'] ?? 'web';
            $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $stmtP = $this->conn->prepare(
                "INSERT INTO PEDIDO
                   (id_pedido, id_cliente, direccion_entrega, fecha_hora, estado, canal_origen,
                    pin_entrega, subtotal, costo_domicilio, descuento_cumpleanos, total)
                 VALUES (?,?,?,NOW(),'pendiente',?,?,?,?,?,?)"
            );
            $stmtP->bind_param("sssssdddd", $idPedido, $datos['id_cliente'], $datos['direccion_entrega'], $canal, $pin, $subtotal, $tarifa, $descuento, $total);
            $stmtP->execute();
            $stmtP->close();

            foreach ($datos['lineas'] as $l) {
                $prod = $productoModel->find($l['id_producto']);
                $idDet = uuid();
                $precioU = (float) $prod['precio'];
                $cant = (int) $l['cantidad'];

                $stmtD = $this->conn->prepare(
                    "INSERT INTO DETALLE_PEDIDO (id_detalle, id_pedido, id_producto, cantidad, precio_unitario)
                     VALUES (?,?,?,?,?)"
                );
                $stmtD->bind_param("sssid", $idDet, $idPedido, $l['id_producto'], $cant, $precioU);
                $stmtD->execute();
                $stmtD->close();

                foreach ($l['personalizaciones'] ?? [] as $p) {
                    if (empty($p['id_ingrediente'])) {
                        continue;
                    }
                    $stmtPers = $this->conn->prepare(
                        "INSERT INTO PERSONALIZACION (id_personalizacion, id_detalle, id_ingrediente, accion_modificacion, costo_aplicado)
                         VALUES (?,?,?,?,?)"
                    );
                    $idP = uuid();
                    $costoA = (float) ($p['costo'] ?? 0);
                    $stmtPers->bind_param("ssssd", $idP, $idDet, $p['id_ingrediente'], $p['accion'], $costoA);
                    $stmtPers->execute();
                    $stmtPers->close();
                }
            }

            // Pago
            $metodo = $datos['metodo_pago'] ?? 'efectivo';
            $comprobante = $datos['comprobante'] ?? null;
            $stmtPago = $this->conn->prepare(
                "INSERT INTO PAGO (id_pedido, metodo, comprobante, estado, fecha_pago) VALUES (?,?,?,'pendiente',NULL)"
            );
            $stmtPago->bind_param("sss", $idPedido, $metodo, $comprobante);
            $stmtPago->execute();
            $stmtPago->close();

            if ($metodo === 'digital' || !empty($datos['aprobar_pago'])) {
                $this->aprobarPago($idPedido);
            }

            $this->conn->commit();
            return $idPedido;
        } catch (\Throwable $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function aprobarPago(string $idPedido): void
    {
        $stmt = $this->conn->prepare("UPDATE PAGO SET estado = 'aprobado' WHERE id_pedido = ? AND estado <> 'aprobado'");
        $stmt->bind_param("s", $idPedido);
        $stmt->execute();
        $stmt->close();
    }

    public function cambiarEstado(string $idPedido, string $estado, array $extra = []): void
    {
        if (!empty($extra['id_domiciliario'])) {
            $stmt = $this->conn->prepare("UPDATE PEDIDO SET estado = ?, id_domiciliario = ? WHERE id_pedido = ?");
            $stmt->bind_param("sss", $estado, $extra['id_domiciliario'], $idPedido);
        } elseif (!empty($extra['id_ayudante'])) {
            // Solo asigna el ayudante si el pedido aun no tiene uno (el primero que lo toma).
            $stmt = $this->conn->prepare(
                "UPDATE PEDIDO SET estado = ?, id_ayudante = COALESCE(id_ayudante, ?) WHERE id_pedido = ?"
            );
            $stmt->bind_param("sss", $estado, $extra['id_ayudante'], $idPedido);
        } else {
            $stmt = $this->conn->prepare("UPDATE PEDIDO SET estado = ? WHERE id_pedido = ?");
            $stmt->bind_param("ss", $estado, $idPedido);
        }
        $stmt->execute();
        $stmt->close();

        $stmtN = $this->conn->prepare(
            "INSERT INTO NOTIFICACION (id_notificacion, id_pedido, tipo, mensaje, estado_envio)
             VALUES (?,?,'push',?, 'enviado')"
        );
        $idNot = uuid();
        $msg = 'Tu pedido ahora esta: ' . str_replace('_', ' ', $estado);
        $stmtN->bind_param("sss", $idNot, $idPedido, $msg);
        $stmtN->execute();
        $stmtN->close();
    }

    public function editarDireccion(string $idPedido, string $direccion): void
    {
        $stmt = $this->conn->prepare("UPDATE PEDIDO SET direccion_entrega = ? WHERE id_pedido = ?");
        $stmt->bind_param("ss", $direccion, $idPedido);
        $stmt->execute();
        $stmt->close();
    }
}

