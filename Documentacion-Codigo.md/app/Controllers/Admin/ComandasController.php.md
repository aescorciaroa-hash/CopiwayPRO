# `app/Controllers/Admin/ComandasController.php`

## Ubicación
`app/Controllers/Admin/ComandasController.php` · namespace `App\Controllers\Admin`

## Propósito
**Órdenes en Tiempo Real** (`/admin/comandas`): el admin ve todas las comandas del
turno, registra pedidos manuales (llamada/WhatsApp) y corrige direcciones.

## Dependencias
`Controller`, `Session`, `App\Models\Pedido`, `App\Models\PedidoServicio`,
`App\Models\Producto`.

## Métodos

### `index(): string` — `GET /admin/comandas`
1. `$activos = Pedido::delTurno()` — activos + entregados/cancelados de las últimas 18 h.
2. A cada pedido le añade `codigo` (`Pedido::codigo`) y `lineas` (`Pedido::detalle`).
3. Vista `admin/comandas/index` con: `activos`, `contadores` (`Pedido::contarPorEstado`),
   `productos` (`Producto::catalogo(false)` para el modal de pedido manual).

### `datos(): string` — `GET /admin/comandas/datos`
Contadores + lista de activos en **JSON** (para refresco).

### `detalle(string $id): string` — `GET /admin/comandas/{id}`
`Pedido::completo($id)` en **JSON** (para el modal "ver detalle").

### `crearManual(): string` — `POST /admin/comandas/manual`
1. `verifyCsrf()`.
2. Lee `cliente`, `telefono`, `direccion` y los arrays `producto_id[]` / `producto_cant[]`.
3. Si falta teléfono, dirección o productos → flash de error.
4. Arma las `$lineas` (sin personalizaciones).
5. `PedidoServicio::clienteParaManual($nombre, $telefono)` — busca el cliente por
   teléfono o crea uno mínimo.
6. `PedidoServicio::crear([... 'canal_origen' => 'llamada', 'metodo_pago' => 'efectivo',
   'aprobar_pago' => true ...])` — entra **directo a cocina**.
7. Flash "Pedido manual #MAN-XXXX creado" + `redirect('/admin/comandas')`.

### `editarDireccion(string $id): string` — `POST /admin/comandas/{id}/direccion`
`verifyCsrf()` + `PedidoServicio::editarDireccion($id, $dir)` + flash.

## Notas
- Los pedidos manuales llevan prefijo `#MAN-` en el código (los web `#ORD-`).
- El pedido manual se aprueba al crearse (regla: se cobra al entregar, en efectivo).
