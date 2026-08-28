# `app/Models/Ingrediente.php`

## Ubicación
`app/Models/Ingrediente.php` · namespace `App\Models` · **extends `Model`**

## Propósito
Tabla `INGREDIENTE` (insumos). Listado con valorización, alertas de stock crítico,
KPIs y **movimientos de inventario** (entradas/salidas/ajustes).

## Configuración
```php
protected static string $table = 'INGREDIENTE';
protected static string $key   = 'id_ingrediente';
```

## Métodos propios

### `conCategoria(?string $buscar = null): array`
Cada ingrediente + su categoría + dos campos calculados:
- `valorizacion` = `cantidad_stock * costo_unitario`,
- `critico` = `cantidad_stock <= umbral_minimo`.
Filtro opcional por nombre. Ordena los críticos primero.

### `criticos(): array`
`WHERE cantidad_stock <= umbral_minimo` ordenado por stock ascendente. Alimenta la
alerta roja de la cocina y del admin.

### `kpis(): array`
Una sola consulta con: `tipos` (COUNT), `unidades` (SUM stock), `valorizacion`
(SUM stock×costo), `criticos` (SUM CASE).

### `moverStock($idIngrediente, $tipo, $cantidad, $motivo, $idAdmin = null): void`
El método clave de ajustes manuales:
- `entrada` → `stock += cantidad`
- `salida` → `stock = max(0, stock - cantidad)`
- `ajuste` → `stock = cantidad` (valor absoluto)
Luego `UPDATE INGREDIENTE SET cantidad_stock = ?` **y** `INSERT INTO MOVIMIENTO_INVENTARIO`
con el tipo, la cantidad y el motivo.

### `movimientos(int $limite = 50): array`
Historial de `MOVIMIENTO_INVENTARIO` + nombre del ingrediente, más recientes primero.

## Notas
- Los descuentos **por venta** los hace el trigger `trg_pago_aprobado` de la BD, no
  `moverStock`. `moverStock` es solo para ajustes que hace el admin.
- `umbral_minimo` por defecto es 10 (config `umbral_stock_critico_default`).
