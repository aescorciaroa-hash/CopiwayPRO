# `app/Controllers/Client/CreadorController.php`

## Ubicación
`app/Controllers/Client/CreadorController.php`

## Propósito
Script procesador del **Creador Interactivo** ("Arma tu Burger"): convierte las capas
elegidas por el cliente en una línea de carrito.

La pantalla `/client/creador` (GET) la arma `public/index.php` con
`Producto::ingredientesCreador()`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`,
`Models/Producto.php`, `Models/Carrito.php`, `Models/Ingrediente.php`,
`Models/Configuracion.php`.

## Acciones (`$action`)

### `agregar` — `POST /client/creador/agregar`
1. Exige al menos una capa en `$_POST['capa'][]`.
2. Busca el producto base **"Hamburguesa Personalizada"** con una consulta directa sobre
   `$conn`. Si no existe, lo crea con `Producto::guardar()` en la primera categoría de
   ámbito `menu` y con `estado = 'oculto'` (no aparece en el catálogo).
3. Por cada capa, salta los ingredientes sin stock y calcula el precio:
   - si el ingrediente tiene `precio_extra > 0`, usa ese;
   - si no, `round(costo_unitario * (1 + margen_ganancia_defecto / 100))`.
4. Todas las capas se guardan como personalizaciones de **acción `agregar`** (EXTRA).
5. `Carrito::agregar($idBase, $cantidad, $pers)` y redirige a `/client/carrito`.

Cualquier otro `$action` → `redirect('/client/creador')`.

## Notas
- El precio de la hamburguesa personalizada sale **entero de los extras**: el producto
  base vale 0.
- Es el único punto del cliente donde se puede crear un `PRODUCTO`, y siempre oculto.
