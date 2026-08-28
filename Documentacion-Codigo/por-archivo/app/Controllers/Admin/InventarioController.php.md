# `app/Controllers/Admin/InventarioController.php`

## Ubicación
`app/Controllers/Admin/InventarioController.php` · namespace `App\Controllers\Admin`

## Propósito
**Inventario Express** (`/admin/inventario`): registrar insumos comprados y ajustar el
stock rápido (+1 / −1 / fijar valor).

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Ingrediente`, `App\Models\Categoria`.

## Métodos

### `index(): string` — `GET /admin/inventario`
Lee `?q=` (búsqueda). Vista con:
`insumos` (`Ingrediente::conCategoria`), `kpis` (`Ingrediente::kpis`),
`movimientos` (`Ingrediente::movimientos(30)`), `categorias` (`Categoria::deInsumos`, para el modal).

### `guardarInsumo(): string` — `POST /admin/inventario/insumo`
1. `verifyCsrf()` + `validate(nombre, id_categoria, unidad_medida, cantidad, costo_total)`.
2. **Calcula el costo unitario:** `costo_total / cantidad` (redondeado a 2 decimales).
3. `Ingrediente::insert([... cantidad_stock, umbral_minimo (10 por defecto), costo_unitario,
   precio_extra, proveedor ...])`.
4. `Ingrediente::moverStock($id, 'entrada', $cantidad, 'Registro inicial de compra', Auth::id())`
   — deja registro del movimiento.
5. Flash + redirect.

### `ajustar(string $id): string` — `POST /admin/inventario/insumo/{id}/ajuste`
Lee `accion` (`mas` / `menos` / `set`) y `valor`:
- `set` → `moverStock($id, 'ajuste', $valor, 'Ajuste manual de inventario', ...)`
  (fija el stock a `$valor`).
- `mas` → `moverStock(..., 'entrada', ..., 'Reabastecimiento rapido', ...)`.
- `menos` → `moverStock(..., 'salida', ..., 'Salida / merma rapida', ...)`.

## Notas
- El descuento **por venta** NO pasa por aquí: lo hace el trigger `trg_pago_aprobado`
  de la base de datos. Aquí solo hay ajustes manuales del admin.
- `Ingrediente::moverStock` actualiza `cantidad_stock` **y** registra en
  `MOVIMIENTO_INVENTARIO`.
