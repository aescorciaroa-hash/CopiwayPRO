# Archivo: `app/Models/PedidoServicio.php`

Modelo de servicio para la creación y gestión del ciclo de vida de los pedidos con transacciones MySQLi.

## Descripción

Procesa el registro completo de pedidos (encabezado, detalles, personalizaciones y pago) dentro de una transacción nativa de MySQLi (`$conn->begin_transaction()`, `$conn->commit()`, `$conn->rollback()`).

## Métodos

- `crear($datos)`: Registra el pedido en la base de datos de manera atómica.
- `aprobarPago($idPedido)`: Actualiza el estado del pago a `aprobado`, lo cual dispara el trigger de inventario y fidelización (`trg_pago_aprobado`).
- `cambiarEstado($idPedido, $nuevoEstado, $extra)`: Actualiza el estado del pedido.
- `editarDireccion($idPedido, $nuevaDireccion)`: Corrige la dirección de entrega de un pedido activo.
