# `app/Models/Pedido.php`

## Ubicación
`app/Models/Pedido.php`

## Propósito
Tabla `PEDIDO`. Contiene las consultas de **lectura** de pedidos (KPIs, listados,
detalle). La lógica de **escritura** (crear, cambiar estado) está en `PedidoServicio`.

## Cómo se usa
No es una clase estática y no hereda de nada. Se instancia y recibe la conexión global
en el constructor:

```php
require_once __DIR__ . '/../Models/Pedido.php';
$pedidoModel = new Pedido();
$pedidoModel->activos();
```

### `const ESTADOS`
```php
public const ESTADOS = ['pendiente','en_preparacion','listo','en_camino','entregado','cancelado'];
```
Los 6 valores del `ENUM` de la columna `estado`. Se lee con `Pedido::ESTADOS` (es una
constante de clase, no hace falta instanciar).

## Métodos

### `find($id): ?array`
El pedido crudo por su id, o `null`.

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

### `delTurno(): array` — **sin uso**
Los `activos` **más** los `entregado`/`cancelado` de las últimas 18 horas, ordenados por
prioridad de estado y fecha.

> ⚠️ **Ya no la llama nadie.** El tablero de comandas del admin usa `activos()`.
> Es código muerto que quedó de una versión anterior.

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
