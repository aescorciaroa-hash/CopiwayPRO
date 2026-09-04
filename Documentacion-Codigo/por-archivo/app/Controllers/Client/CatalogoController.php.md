# `app/Controllers/Client/CatalogoController.php`

## Ubicación
`app/Controllers/Client/CatalogoController.php`

## Propósito
Script procesador del **catálogo del cliente**. Su única responsabilidad es devolver en
**JSON** los datos que necesita el modal "Personalizar" de un producto.

La pantalla `/client` (GET) la arma `public/index.php` (productos, categorías, si es
cumpleaños, historial y último pedido para la recompra).

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`,
`Models/Producto.php`, `Models/Categoria.php`.

## Acciones (`$action`)

### `personalizar` — `GET /client/producto/{id}`
`public/index.php` captura esa ruta, pone `$_GET['action'] = 'personalizar'` y `$_GET['id']`,
y hace `require` de este script.

1. `Producto::conCategoria($id)`; 404 en JSON si no existe.
2. Añade `agotado` → `Producto::estaAgotado($id)`.
3. Añade `personalizables` → `Producto::personalizables($id)`: los ingredientes que se
   pueden **quitar** (SIN) o **agregar** (EXTRA), con su `precio_extra`.
4. Emite `Content-Type: application/json` y devuelve el producto.

Cualquier otro `$action` → `redirect('/client')`.

## Notas
- Respuesta JSON pura: no carga layout ni vista.
- Lo consume `app/Views/client/_modal_personalizar.php` con `fetch`.
