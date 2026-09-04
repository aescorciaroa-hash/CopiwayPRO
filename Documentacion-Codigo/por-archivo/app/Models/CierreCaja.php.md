# `app/Models/CierreCaja.php`

## Ubicación
`app/Models/CierreCaja.php`

## Propósito
El **cierre de caja del día**: junta las ventas por método de pago, el consumo de
insumos (escandallo) y la liquidación de cada domiciliario, y lo guarda como reporte.

## Cómo se usa
```php
require_once __DIR__ . '/../Models/CierreCaja.php';
$cierreModel = new CierreCaja();
$resumen = $cierreModel->calcular(date('Y-m-d'));   // solo calcula
$id      = $cierreModel->generar(date('Y-m-d'), Auth::id());  // calcula y guarda
```

## Métodos

### `calcular(string $fecha): array` — solo lee, no escribe nada
Lanza cuatro consultas preparadas sobre el rango `fecha 00:00:00` → `fecha 23:59:59`:

| Clave del resultado | Qué trae |
|---|---|
| `totales` | `total_ventas`, `total_ordenes`, `total_efectivo`, `total_digital` (un `SUM` con `CASE WHEN pg.metodo = ...`), excluyendo los `cancelado` |
| `escandallo` | Por ingrediente: `consumido` = `SUM(receta.cantidad_necesaria * detalle.cantidad)` y el `stock_real` actual. Ordenado por consumo |
| `liquidaciones` | Por domiciliario: `entregas`, `recaudo` (solo pedidos **en efectivo** y **entregados**) y `total_entregar` = `base_efectivo_asignada + recaudo` |
| `activasSinEntregar` | Cuántos pedidos del día siguen en `pendiente` / `en_preparacion` / `listo` / `en_camino` (aviso de que aún no conviene cerrar) |
| `ventasSemana` | `Pedido::ventasPorDia()` desde el lunes de esta semana, para el gráfico |

### `generar(string $fecha, string $idAdmin): string`
Llama a `calcular()` y guarda todo **dentro de una transacción**
(`begin_transaction` / `commit` / `rollback` en un `try/catch`). Devuelve el `id_reporte`.

Es un **upsert**: si ya existe un `REPORTE_CAJA` para ese `(id_admin, fecha)` — la tabla
tiene `UNIQUE` en ese par — borra sus `DETALLE_AUDITORIA` y `LIQUIDACION_DOMICILIARIO`
y actualiza los totales; si no existe, lo inserta con un `uuid()` nuevo.

Después:
1. Una fila de `DETALLE_AUDITORIA` por cada ingrediente del escandallo.
2. Una fila de `LIQUIDACION_DOMICILIARIO` por cada repartidor
   (`base_asignada`, `efectivo_recolectado`, `efectivo_liquidado`).
3. **Enlaza los pedidos del día al reporte**:
   ```sql
   UPDATE PEDIDO SET id_reporte = ?
    WHERE id_reporte IS NULL AND estado = 'entregado' AND fecha_hora BETWEEN ? AND ?
   ```
   El `id_reporte IS NULL` evita robarle pedidos a un cierre anterior.

> ⚠️ **Limitación conocida:** en `DETALLE_AUDITORIA` se guarda el mismo valor en
> `stock_teorico` y en `stock_real` (los dos salen de `$teorico`). O sea que la tabla
> queda escrita, pero **la comparación teórico-vs-real siempre da cero** y no detecta
> mermas. Para que la auditoría sirviera de verdad, el teórico habría que calcularlo como
> `stock del inicio del día − consumido`.

### `obtener(string $fecha): array`
Hoy es un **alias de `calcular($fecha)`**: no lee `REPORTE_CAJA`, recalcula. Por eso lo
que muestra la pantalla de Ajustes es siempre el cálculo en vivo del día, exista o no un
reporte guardado.

### `recientes(int $limite = 10): array`
`SELECT * FROM REPORTE_CAJA ORDER BY fecha DESC LIMIT n` — la lista de cierres guardados.

## Notas
- Es uno de los dos modelos que abren transacciones (el otro es `PedidoServicio`).
- El reporte imprimible (`/admin/ajustes/reporte`) lo sirve `public/index.php`
  directamente, llamando a `obtener()` y cargando la vista sin layout.
- El escandallo cuenta **todo lo vendido**, sin descontar los ingredientes que el cliente
  pidió quitar (a diferencia del trigger `trg_pago_aprobado`, que sí los excluye).
