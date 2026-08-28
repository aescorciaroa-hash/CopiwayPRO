# `app/Controllers/Admin/ClientesController.php`

## Ubicación
`app/Controllers/Admin/ClientesController.php` · namespace `App\Controllers\Admin`

## Propósito
**Directorio de Clientes** (`/admin/clientes`): lista de clientes registrados con su
resumen de compras, y el historial de cada uno.

## Dependencias
`Controller`, `App\Models\Cliente`, `App\Models\Pedido`.

## Métodos

### `index(): string` — `GET /admin/clientes`
Lee `?q=` (búsqueda por nombre o teléfono). Vista con `clientes`
(`Cliente::directorio($buscar)` — incluye nº de pedidos, total gastado y último pedido)
y `buscar`.

### `historial(string $id): string` — `GET /admin/clientes/{id}/historial`
1. `Cliente::find($id)` — si no existe, JSON 404.
2. `Cliente::historial($id)` — todos sus pedidos.
3. A cada pedido le añade `codigo`.
4. Devuelve `{cliente, pedidos}` en **JSON** (para el modal "ver historial").

## Notas
- Solo lectura. El admin no edita ni borra clientes desde aquí.
- `Cliente::directorio` usa subconsultas correlacionadas para los totales.
