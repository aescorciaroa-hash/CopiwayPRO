# `app/Controllers/Admin/ClientesController.php`

## Ubicación
`app/Controllers/Admin/ClientesController.php`

## Propósito
Script procesador del **directorio de clientes**. Hoy solo tiene una responsabilidad:
devolver en **JSON** el historial de compras de un cliente para el modal "Ver historial".

El listado `/admin/clientes` (GET) lo arma `public/index.php`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`,
`Models/Cliente.php`, `Models/Pedido.php`.

## Acciones (`$action`)

### `historial` — `GET /admin/clientes/{id}/historial`
`public/index.php` captura esa ruta, pone `$_GET['action'] = 'historial'` y `$_GET['id']`,
y hace `require` de este script.

1. `Cliente::find($id)` y `Cliente::historial($id)`.
2. A cada pedido le añade `codigo` con `Pedido::codigo($p)`.
3. **Borra el campo `contrasena`** del cliente antes de responder.
4. Emite `Content-Type: application/json` y `echo json_encode(['cliente' => ..., 'pedidos' => ...])`.

Cualquier otro `$action` → `redirect('/admin/clientes')`.

## Notas
- Es una respuesta JSON pura: **no** carga layout ni vista.
- La búsqueda del directorio (`?q=...`) se resuelve en `index.php` con `Cliente::directorio($buscar)`.
