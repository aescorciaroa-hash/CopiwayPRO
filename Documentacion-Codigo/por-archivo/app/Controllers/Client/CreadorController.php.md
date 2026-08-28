# `app/Controllers/Client/CreadorController.php`

## Ubicación
`app/Controllers/Client/CreadorController.php` · namespace `App\Controllers\Client`

## Propósito
El **Creador Interactivo** "Arma tu Burger" (`/client/creador`): el cliente construye
una hamburguesa eligiendo ingredientes capa por capa.

## Dependencias
`Controller`, `Session`, `Database`, `App\Models\Producto`, `App\Models\Carrito`,
`App\Models\Ingrediente`.

## Métodos

### `index(): string` — `GET /client/creador`
Vista con `ingredientes` = `Producto::ingredientesCreador()` (insumos alimenticios con
un **precio calculado** a partir del costo y el margen de ganancia configurado).

### `agregar(): string` — `POST /client/creador/agregar`
Recibe `capa[]` (ids de ingredientes) y `cantidad`.
1. `verifyCsrf()`. Si no hay capas → flash "Burger vacía".
2. Busca un producto oculto llamado **"Hamburguesa Personalizada"**. Si no existe, lo
   crea (`estado = 'oculto'`, precio 0, en la primera categoría de menú).
3. Por cada capa con stock > 0, calcula su precio
   (`precio_extra` si tiene, si no `costo_unitario * (1 + margen/100)`) y arma una
   personalización `accion => 'agregar'`.
4. `Carrito::agregar($idBase, $cantidad, $pers)` → `redirect('/client/carrito')`.

## Notas
- El producto base "Hamburguesa Personalizada" se **reutiliza**: solo se crea una vez.
- El precio de la burger personalizada = suma de los extras (el producto base cuesta 0).
- Comparte la fórmula de precio con `Producto::ingredientesCreador`.
