# `app/Controllers/Admin/DashboardController.php`

## Ubicación
`app/Controllers/Admin/DashboardController.php` · namespace `App\Controllers\Admin`

## Propósito
El **Tablero Analítico** del administrador (`/admin`): KPIs de ventas, gráfico de ventas
por día, ranking de productos, pedidos recientes y estado de la operación.

## Dependencias
`Controller`, `App\Core\Periodo`, `App\Models\Pedido`, `App\Models\Empleado`,
`App\Models\Configuracion`.

## Métodos

### `index(): string` — `GET /admin`
1. `$periodo = Periodo::valida($this->input('periodo'))` — lee el filtro del `<select>`.
2. `[$desde, $hasta] = Periodo::rango($periodo)`.
3. Trae los datos:
   | Variable a la vista | De dónde sale |
   |---------------------|---------------|
   | `kpi` | `Pedido::kpis($desde, $hasta)` — ventas, órdenes, ticket promedio |
   | `ordenesHoy` | `Pedido::kpis(rango('hoy'))['ordenes']` |
   | `empleados` | `Empleado::activos()` |
   | `ventasDia` | `Pedido::ventasPorDia($desde, $hasta)` |
   | `rango` | `[$desde, $hasta]` (la vista rellena los días sin ventas) |
   | `ranking` | `Pedido::rankingProductos($desde, $hasta)` |
   | `recientes` | `Pedido::recientes(6)` |
   | `estadoCocina` | `Configuracion::estadoCocina()` |
   | `contadores` | `Pedido::contarPorEstado()` |
4. Devuelve la vista `admin/dashboard` (layout `admin`).

### `datos(): string` — `GET /admin/dashboard/datos`
Los mismos KPIs, ventas y ranking en **JSON** (para refresco sin recargar). Opcional.

## Notas
- Todo el tablero se filtra por el mismo `$periodo`.
- La vista construye el gráfico de barras con eje Y, rejilla y la semana completa a
  partir de `ventasDia` + `rango`.
