# `app/Models/Pedido.php`

## Ubicación
`app/Models/Pedido.php` · namespace `App\Models` · **extends `Model`**

## Propósito
Tabla `PEDIDO`. Contiene las consultas de **lectura** de pedidos (KPIs, listados,
detalle). La lógica de **escritura** (crear, cambiar estado) está en `PedidoServicio`.

## Configuración
```php
protected static string $table = 'PEDIDO';
protected static string $key   = 'id_pedido';
const ESTADOS = ['pendiente','en_preparacion','listo','en_camino','entregado','cancelado'];
```

## Métodos propios

### `codigo(array $pedido): string`
Código corto legible: `#ORD-XXXX` (canal `web`) o `#MAN-XXXX` (llamada/WhatsApp).
Toma los **últimos 4 caracteres** del UUID en mayúsculas:
```php
$hex = str_replace('-', '', $pedido['id_pedido']);
$num = strtoupper(substr($hex, -4));
```

### `kpis($desde, $hasta): array`
`SUM(total)` (ventas), `COUNT(*)` (órdenes), `AVG(total)` (ticket) para un rango,
excluyendo cancelados.

### `ventasPorDia($desde, $hasta): array`
`SUM(total)` agrupado por `DATE(fecha_hora)`. Para el gráfico de barras.

### `rankingProductos($desde, $hasta, $limite = 5): array`
`SUM(dp.cantidad)` por producto, ordenado desc. Para la dona del tablero.

### `contarPorEstado(): array`
Cuántos pedidos hay en cada estado activo.

### `activos(): array`
Pedidos en `pendiente/en_preparacion/listo/en_camino` + cliente, domiciliario, pago y
`minutos` (`TIMESTAMPDIFF(MINUTE, fecha_hora, NOW())`).

### `delTurno(): array`
Los `activos` **más** los `entregado`/`cancelado` de las últimas 18 horas. Ordenados
por prioridad de estado y fecha. Para el tablero de comandas del admin.

### `recientes($limite = 8): array`
Últimos N pedidos (para el tablero).

### `detalle(string $idPedido): array`
Las líneas del pedido (`DETALLE_PEDIDO` + nombre del producto) y, para cada una, sus
`personalizaciones` (con `id_ingrediente`, `accion_modificacion`, `costo_aplicado`, nombre).

### `completo(string $idPedido): ?array`
El pedido + cliente + domiciliario + pago + `lineas`. Para el modal de detalle y la
tirilla.

## Notas
- `codigo()` usa los **últimos** 4 hex porque en el seed todos los UUID empiezan igual.
- `minutos` alimenta el SLA de cocina (>15 min → alerta roja).
