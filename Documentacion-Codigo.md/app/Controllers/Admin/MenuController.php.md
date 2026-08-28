# `app/Controllers/Admin/MenuController.php`

## Ubicación
`app/Controllers/Admin/MenuController.php` · namespace `App\Controllers\Admin`

## Propósito
**Gestión de Menú** (`/admin/menu`): crear/editar/ocultar/eliminar productos y su
**receta** (escandallo), y administrar categorías.

## Dependencias
`Controller`, `Session`, `Database`, `App\Models\Producto`, `App\Models\Categoria`,
`App\Models\Ingrediente`.

## Métodos

### `index(): string` — `GET /admin/menu`
Lee `?categoria=` y `?q=` (filtro y búsqueda). Vista con:
`productos` (`Producto::paraAdmin`), `categorias` (`Categoria::conConteo('menu')`),
`ingredientes` (`Ingrediente::conCategoria`, para el selector de receta), `filtroCat`, `buscar`.

### `datosProducto(string $id): string` — `GET /admin/menu/producto/{id}`
JSON con el producto + su `receta` (`Producto::receta`) + su `costo`
(`Producto::costoReceta`). Para el modal de edición.

### `guardarProducto(): string` — `POST /admin/menu/producto`
1. `verifyCsrf()` + `validate(nombre, id_categoria, precio)`.
2. Arma `$datos` (categoría, nombre, descripción, precio, imagen, etiqueta).
3. Si viene `id_producto` → `Producto::update` (mensaje "Actualizado").
   Si no → añade `estado = 'activo'` y `Producto::insert` (mensaje "Publicado").
4. **Receta:** lee los arrays paralelos `receta_ingrediente[]` y `receta_cantidad[]`,
   arma `$items` y llama `Producto::guardarReceta($id, $items)` (borra y reinserta).
5. Flash + `redirect('/admin/menu')`.

### `cambiarEstado(string $id): string` — `POST .../producto/{id}/estado`
Alterna `activo` ↔ `oculto`. Flash "marcado como Disponible/Oculto".

### `eliminarProducto(string $id): string` — `POST .../producto/{id}/eliminar`
Si `Producto::tienePedidos($id)` → **no** deja borrar ("tiene historial, ocúltalo").
Si no → `Producto::delete($id)`.

### `crearCategoria(): string` — `POST /admin/menu/categoria`
`Categoria::insert(['nombre' => ..., 'ambito' => 'menu'])`.

### `eliminarCategoria(string $id): string` — `POST .../categoria/{id}/eliminar`
Cuenta productos con esa categoría; si hay → no deja borrar ("reasígnalos primero").

## Notas
- La receta se guarda con **arrays paralelos** del formulario (índice `i` empareja
  `receta_ingrediente[i]` con `receta_cantidad[i]`).
- No se puede borrar un producto con ventas ni una categoría en uso (integridad histórica).
