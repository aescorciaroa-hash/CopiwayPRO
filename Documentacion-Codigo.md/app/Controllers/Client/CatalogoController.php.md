# `app/Controllers/Client/CatalogoController.php`

## Ubicación
`app/Controllers/Client/CatalogoController.php` · namespace `App\Controllers\Client`

## Propósito
El **catálogo del cliente** (`/client`): el menú para pedir, con personalización.

## Dependencias
`Controller`, `Auth`, `Database`, `App\Models\Producto`, `App\Models\Categoria`,
`App\Models\Cliente`, `App\Models\Configuracion`.

## Métodos

### `index(): string` — `GET /client`
Trae:
- `cliente` — `Cliente::find(Auth::id())`.
- `ultimoPedido` — id del último pedido no cancelado (para el botón "pedir lo mismo").
- `productos` — `Producto::catalogo()`.
- `categorias` — `Categoria::menu()`.
- `cumple` — `Cliente::esCumpleanos($cliente)` (banner de 15%).
- `estado` — `Configuracion::estadoCocina()`.

Vista `client/catalogo` (layout `client`).

### `personalizar(string $id): string` — `GET /client/producto/{id}`
JSON para el modal de personalización:
- el producto con su categoría (`Producto::conCategoria`),
- `agotado` (`Producto::estaAgotado`),
- `personalizables` (`Producto::personalizables` → ingredientes que se pueden **quitar**
  y **extras** que se pueden **agregar**, con su precio y si están agotados).

## Notas
- Si la sesión apunta a un cliente que ya no existe, `Auth::requireRole` lo desloguea
  antes de llegar aquí (no revienta).
