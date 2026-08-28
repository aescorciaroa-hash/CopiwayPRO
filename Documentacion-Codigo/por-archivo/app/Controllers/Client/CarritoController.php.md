# `app/Controllers/Client/CarritoController.php`

## Ubicación
`app/Controllers/Client/CarritoController.php` · namespace `App\Controllers\Client`

## Propósito
El **carrito** del cliente (`/client/carrito`). Añadir, actualizar, quitar y vaciar
productos. El carrito vive en la sesión (modelo `Carrito`).

## Dependencias
`Controller`, `Session`, `App\Models\Carrito`, `App\Models\Producto`,
`App\Models\Configuracion`, `App\Models\Ingrediente`.

## Métodos

### `index(): string` — `GET /client/carrito`
Vista con `items` (`Carrito::items()`), `subtotal` (`Carrito::subtotal()`) y `envio`
(`tarifa_plana_domicilio`).

### `agregar(): string` — `POST /client/carrito/agregar`
Recibe `id_producto`, `cantidad`, `quitar[]` (ids de ingredientes a quitar), `extra[]`
(ids a agregar), `volver` (a dónde regresar).
1. `verifyCsrf()`.
2. Si el producto está agotado (`Producto::estaAgotado`) → flash de error.
3. `$pers = $this->personalizacionesDesdePost()`.
4. `Carrito::agregar($id, $cantidad, $pers)`.
5. Flash + `redirect($volver ?? '/client')`.

### `actualizar(): string` — `POST /client/carrito/actualizar`
`Carrito::actualizar($key, $cantidad, $pers?)`. Si `cantidad <= 0`, el modelo lo quita.

### `quitar(): string` / `vaciar(): string`
`Carrito::quitar($key)` / `Carrito::vaciar()`.

### `private personalizacionesDesdePost(): array`
Convierte los arrays del formulario en el formato de personalizaciones:
- por cada id en `quitar[]` → `['id_ingrediente' => ..., 'nombre' => ..., 'accion' => 'quitar', 'costo' => 0]`.
- por cada id en `extra[]` **con stock > 0** → `['accion' => 'agregar', 'costo' => precio_extra]`.

## Notas
- La "clave" de cada línea del carrito es un `md5` del producto + las personalizaciones,
  así dos hamburguesas iguales se agrupan y dos distintas no.
- No toca la base de datos: todo es sesión hasta el checkout.
