# `app/Controllers/Client/HistorialController.php`

## Ubicación
`app/Controllers/Client/HistorialController.php`

## Propósito
Script procesador del **historial del cliente**: la **recompra en 1 clic** y las
**reseñas** (1–5 estrellas).

La pantalla `/client/historial` (GET) la arma `public/index.php`, que además calcula ahí
los filtros (`todos` / `pendientes` / `calificados`) y el total invertido.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Pedido.php`, `Models/Producto.php`, `Models/Carrito.php`.

## Acciones (`$action`)

`public/index.php` reconoce `/client/historial/{id}/recomprar` y `/client/historial/{id}/resena`
y deja el `{id}` en `$_POST['id']` y la acción en `$_POST['action']`.

### `recomprar` — `POST /client/historial/{id}/recomprar`
1. `Pedido::find($id)` y `Pedido::detalle($id)`.
2. **Primera pasada de validación:** si *cualquier* línea está agotada
   (`Producto::estaAgotado`), aborta con un flash nombrando el producto y **no agrega nada**.
3. Segunda pasada: reconstruye las personalizaciones de cada línea desde
   `accion_modificacion` y `costo_aplicado`, y las mete al carrito con `Carrito::agregar`.
4. Redirige a `/client/carrito`.

### `resena` — `POST /client/historial/{id}/resena`
1. Acota el puntaje con `max(1, min(5, ...))`.
2. Consulta `RESENA` por `id_pedido` con una sentencia preparada sobre `$conn`:
   - si ya existe → `UPDATE` (puntaje y comentario),
   - si no → `INSERT` con `uuid()` y `NOW()`.
3. Vuelve a `/client/historial`.

Cualquier otro `$action` → `redirect('/client/historial')`.

## Notas
- La reseña es **una por pedido** (la tabla `RESENA` tiene `UNIQUE(id_pedido)`), por eso
  el script hace *upsert* manual en vez de insertar siempre.
- Este archivo usa `$conn` directamente para las reseñas; no hay modelo `Resena`.
