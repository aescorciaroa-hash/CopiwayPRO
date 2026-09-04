# `app/Controllers/Admin/MenuController.php`

## Ubicación
`app/Controllers/Admin/MenuController.php`

## Propósito
Script procesador de la **Gestión del Menú**: productos, su **receta** (escandallo) y
las categorías del menú.

La pantalla `/admin/menu` (GET) la arma `public/index.php` (productos, categorías con
conteo, ingredientes para los modales).

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`,
`Models/Producto.php`, `Models/Categoria.php`, `Models/Ingrediente.php`.

## Acciones (`$action`)

### `cargarProducto` — `GET /admin/menu/producto/{id}`
Devuelve **JSON** con `Producto::conCategoria($id)` más la clave `receta`
(`Producto::receta($id)`). 404 con `{"error": ...}` si no existe.
Alimenta el modal de edición.

> Ojo: la acción se llama **`cargarProducto`** (no `datosProducto`).

### `guardarProducto` — `POST /admin/menu/producto`
1. Valida `nombre` e `id_categoria`.
2. `Producto::guardar([...])` — **crea o edita** según venga o no `$_POST['id_producto']`.
3. Arma la receta con los arrays paralelos `receta_ingrediente[]` y `receta_cantidad[]`
   y llama `Producto::guardarReceta($id, $items)`.

### `cambiarEstado` — `POST /admin/menu/producto/{id}/estado`
Lee el producto y **alterna** `activo` ↔ `oculto`.

### `eliminarProducto` — `POST /admin/menu/producto/{id}/eliminar`
`Producto::eliminar($id)`. Si devuelve `false` (el producto tiene historial de ventas),
muestra un flash de advertencia en vez de borrar.

### `crearCategoria` — `POST /admin/menu/categoria`
`Categoria::crear(['nombre' => ..., 'ambito' => 'menu'])`.

### `eliminarCategoria` — `POST /admin/menu/categoria/{id}/eliminar`
`Categoria::eliminar($id)`.

Cualquier otro `$action` → `redirect('/admin/menu')`.

## Notas
- Todas las acciones POST terminan en `redirect('/admin/menu')` (**PRG**).
- `public/index.php` deduce el `$action` mirando el final de la ruta y extrae el `{id}`
  con una expresión regular, poniéndolo en `$_POST['id']`.
