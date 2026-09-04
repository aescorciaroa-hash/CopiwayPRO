# `app/Controllers/Client/CarritoController.php`

## Ubicación
`app/Controllers/Client/CarritoController.php`

## Propósito
Script procesador del **carrito de compras** (que vive en `$_SESSION['carrito']`):
agregar, actualizar, quitar y vaciar.

La pantalla `/client/carrito` (GET) la arma `public/index.php`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`,
`Models/Carrito.php`, `Models/Producto.php`, `Models/Ingrediente.php`,
`Models/Configuracion.php`.

## Función auxiliar del archivo

### `personalizacionesDesdePost($ingModel): array`
Convierte los arrays del formulario en el array de personalizaciones del carrito:

- `$_POST['quitar'][]` → `['accion' => 'quitar', 'costo' => 0]` (los **SIN**).
- `$_POST['extra'][]` → `['accion' => 'agregar', 'costo' => $ing['precio_extra']]`
  (los **EXTRA**), **solo si el ingrediente tiene `cantidad_stock > 0`**.

De cada ingrediente guarda también `id_ingrediente` y `nombre`.

## Acciones (`$action`)

### `agregar` — `POST /client/carrito/agregar`
1. Si no hay `id_producto` o `Producto::estaAgotado($id)` → flash de error y vuelve a `/client`.
2. `Carrito::agregar($id, $cantidad, $pers)` con `$cantidad = max(1, ...)`.
3. Redirige a `$_POST['volver']` si el formulario lo manda (así el catálogo y el modal
   vuelven a donde estaba el cliente).

### `actualizar` — `POST /client/carrito/actualizar`
`Carrito::actualizar($key, $cantidad, $pers)`. Solo recalcula las personalizaciones si el
formulario mandó `quitar[]` o `extra[]`; si no, pasa `null` y se conservan las que ya tenía.

### `quitar` — `POST /client/carrito/quitar`
`Carrito::quitar($key)`.

### `vaciar` — `POST /client/carrito/vaciar`
`Carrito::vaciar()`.

Cualquier otro `$action` → `redirect('/client/carrito')`.

## Notas
- La `key` identifica una **línea** del carrito (mismo producto con distintas
  personalizaciones = líneas distintas).
- Todas las acciones dejan un flash de tipo `cart` y redirigen (**PRG**).
