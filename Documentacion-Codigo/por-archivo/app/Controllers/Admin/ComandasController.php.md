# `app/Controllers/Admin/ComandasController.php`

## Ubicación
`app/Controllers/Admin/ComandasController.php`

## Propósito
Script procesador del **Tablero de Comandas**: detalle de un pedido en JSON, alta de
**pedidos manuales** (llamada / WhatsApp) y corrección de la dirección de entrega.

El tablero `/admin/comandas` (GET) lo arma `public/index.php` con `Pedido::activos()`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`,
`Models/Pedido.php`, `Models/PedidoServicio.php`, `Models/Producto.php`,
`Models/Configuracion.php`.

## Acciones (`$action`)

### `detalle` — `GET /admin/comandas/{id}`
`Pedido::completo($id)` + `codigo`. Responde **JSON** (404 con `{"error": ...}` si no existe).
Alimenta el modal "Ver detalle".

### `crearManual` — `POST /admin/comandas/manual`
1. Lee `cliente`, `telefono`, `direccion` y los arrays `producto_id[]` / `producto_cant[]`.
2. Valida que haya teléfono, dirección y al menos un producto; si no, flash de error y vuelve.
3. Arma `$lineas` (cada una con `personalizaciones` vacías).
4. `PedidoServicio::clienteParaManual($nombre, $telefono)` → busca o crea el cliente por teléfono.
5. `PedidoServicio::crear([...])` con `canal_origen` = `$_POST['canal']` (por defecto `llamada`),
   `metodo_pago` = `efectivo` y **`aprobar_pago = true`** → entra directo a cocina.
6. Flash con el código del pedido (que llevará prefijo `#MAN-`).

### `editarDireccion` — `POST /admin/comandas/{id}/direccion`
`PedidoServicio::editarDireccion($id, $dir)` si ambos vienen no vacíos.

Cualquier otro `$action` → `redirect('/admin/comandas')`.

## Notas
- Las dos acciones POST terminan en `redirect('/admin/comandas')` (**PRG**).
- El `id` de la ruta lo inyecta `public/index.php` en `$_POST['id']` con una expresión regular
  antes de hacer el `require`.
