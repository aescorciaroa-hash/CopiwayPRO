# `app/Views/admin/inventario/index.php`

## Ubicación
`app/Views/admin/inventario/index.php`

## Propósito
El **Abastecimiento Express**: KPIs de inventario, los insumos separados en consumibles y
empaques con ajuste rápido de stock, el historial de movimientos y el alta de insumos.

Cubre **RF-45** (Abastecimiento Express) y las alertas de stock crítico.

## Quién la renderiza
`public/index.php`, `case '/admin/inventario'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$insumos` | `Ingrediente::conCategoria($_GET['buscar'] ?? null)` — con `valorizacion` y `critico` calculados |
| `$criticos` | `Ingrediente::criticos()` |
| `$categorias` | `Categoria::deInsumos()` |
| `$kpis` | `Ingrediente::kpis()` |
| `$movimientos` | `Ingrediente::movimientos()` |

## Dos ayudas locales

```php
$fmt = fn($n) => rtrim(rtrim(number_format((float)$n, 2, '.', ''), '0'), '.');
$esEmpaque = fn($cat) => stripos((string) $cat, 'empaque') !== false
                      || stripos((string) $cat, 'desechable') !== false;
```

- `$fmt` quita los ceros decimales sobrantes: `2.50` → `2.5`, `3.00` → `3`.
- `$esEmpaque` separa los insumos en dos grupos.

> ⚠️ `$esEmpaque` decide **por el nombre de la categoría** (busca "empaque" o "desechable"
> en el texto), no por el campo `ambito` de la tabla. Si alguien crea una categoría de
> empaques con otro nombre, aparecerá en el grupo equivocado. El modal de producto, en
> cambio, sí usa `ambito`.

## Secciones

1. **3 KPIs** — Items críticos (en rojo si hay alguno), unidades totales con el número de
   tipos, y valorización del inventario.
2. **Consumibles** y **Empaques y Desechables** — dos rejillas. Cada tarjeta muestra el
   stock, la unidad, la valorización, y borde rojo si `critico`.
3. **Ajuste rápido** por insumo — tres formularios a
   `/admin/inventario/insumo/{id}/ajuste`:
   - `−1` → `accion=menos`, `valor=1`
   - `+1` → `accion=mas`, `valor=1`
   - fijar un valor → `accion=set`
4. **Historial de Movimientos** — la tabla de `MOVIMIENTO_INVENTARIO` con tipo, cantidad y
   motivo. Ahí se ven tanto los ajustes manuales como las salidas automáticas que genera el
   trigger `trg_pago_aprobado` al aprobarse un pago.
5. **Modal "Registrar Nuevo Insumo"** — `POST` a `/admin/inventario/insumo`, con un
   `x-data="{ cantidad: '', costo: '' }"` que muestra el **costo unitario calculado**
   (`costo_total / cantidad`) mientras se escribe.

## Notas
- El alta crea el insumo con stock 0 y registra un movimiento de `entrada` con la cantidad
  real, para que quede en el historial. Ver `InventarioController.php.md`.
- Los insumos críticos aparecen también en la campana del admin y en el sidebar del KDS.
