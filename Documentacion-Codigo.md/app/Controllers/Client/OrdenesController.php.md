# `app/Controllers/Client/OrdenesController.php`

## Ubicación
`app/Controllers/Client/OrdenesController.php` · namespace `App\Controllers\Client`

## Propósito
**Órdenes Activas** del cliente (`/client/ordenes`): el seguimiento en vivo de los
pedidos que aún no se han entregado.

## Dependencias
`Controller`, `Auth`, `Database`, `App\Models\Pedido`.

## Métodos

### `index(): string` — `GET /client/ordenes`
Consulta los pedidos del cliente logueado en estado
`pendiente` / `en_preparacion` / `listo` / `en_camino`, con datos del domiciliario y
del pago. A cada uno le añade `codigo` y `lineas`. Vista `client/ordenes`.

La vista muestra la barra de progreso (Recibido → Cocina → Listo → En Camino) y el
**PIN de entrega** que el cliente debe mostrarle al domiciliario.

### `detalle(string $id): string` — `GET /client/ordenes/{id}`
`Pedido::completo($id)` en JSON, **solo si el pedido es del cliente logueado**
(`$p['id_cliente'] === Auth::id()`); si no, 404.

## Notas
- Es una de las pantallas con **auto-refresco** (se recarga cada N segundos).
- El PIN sale de `PEDIDO.pin_entrega` (generado por el trigger `trg_pedido_pin`).
