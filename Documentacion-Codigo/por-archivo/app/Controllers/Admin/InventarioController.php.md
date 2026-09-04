# `app/Controllers/Admin/InventarioController.php`

## Ubicación
`app/Controllers/Admin/InventarioController.php`

## Propósito
Script procesador del **Inventario Express**: alta de insumos y ajustes rápidos de stock.

La pantalla `/admin/inventario` (GET) la arma `public/index.php` (insumos, críticos,
categorías, KPIs y movimientos).

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Ingrediente.php`, `Models/Categoria.php`.

## Acciones (`$action`)

### `guardarInsumo` — `POST /admin/inventario/insumo`
1. Valida que haya `nombre`, `id_categoria` y `cantidad > 0`.
2. Calcula `costo_unitario = round(costo_total / cantidad, 2)`.
3. `Ingrediente::guardar([...])` **con `cantidad_stock = 0`**.
4. `Ingrediente::moverStock($id, 'entrada', $cantidad, 'Registro inicial de compra', Auth::id())`.

> El stock nunca se escribe directo: se crea el insumo en 0 y es el **movimiento de
> entrada** el que fija la cantidad real. Así queda registrado en el historial y no se
> duplica la cantidad.

### `ajustar` — `POST /admin/inventario/insumo/{id}/ajuste`
Según `$_POST['accion']`:

| `accion` | Movimiento | Motivo |
|---|---|---|
| `set` | `ajuste` | `Ajuste manual de inventario` |
| `mas` | `entrada` | `Reabastecimiento rapido` |
| `menos` | `salida` | `Salida / merma rapida` |

La cantidad sale de `$_POST['valor']`. Los botones `+1` / `−1` de la tabla mandan `valor = 1`.

Cualquier otro `$action` → `redirect('/admin/inventario')`.

## Notas
- Ambas acciones terminan en `redirect('/admin/inventario')` (**PRG**), con flash de tipo
  `inventory` o `success`.
- Cada movimiento queda firmado con `Auth::id()` (quién lo hizo).
