# `app/Views/admin/dashboard.php`

## Ubicación
`app/Views/admin/dashboard.php`

## Propósito
El **Tablero Analítico** (`/admin`): KPIs, gráfico de ventas por día, donut de productos
más vendidos, últimas órdenes y estado de la operación.

Cubre **RF-36** (Dashboard de Ventas) y **RF-37** (Filtro de Rango de Tiempo).

## Quién la renderiza
`public/index.php`, `case '/admin'`, con el layout **`admin`**.
Ahí está toda la consulta: es lo que antes hacía `DashboardController`.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$kpi` | `Pedido::kpis($desde, $hasta)` — ventas, órdenes, ticket promedio |
| `$ordenesHoy` | `Pedido::kpis(rango('hoy'))['ordenes']` |
| `$ventasDia` | `Pedido::ventasPorDia($desde, $hasta)` |
| `$rango` | `[desde, hasta]` — la vista rellena los días sin ventas |
| `$ranking` | `Pedido::rankingProductos(...)` |
| `$recientes` | `Pedido::recientes(8)` |
| `$contadores` | `Pedido::contarPorEstado()` |
| `$empleados` | `Empleado::activos()` (un entero) |
| `$estadoCocina` | `Configuracion::estadoCocina()` |
| `$periodo`, `$opciones` | `Periodo::valida(...)` y `Periodo::OPCIONES` |

## ⚠️ Es la vista con más lógica del proyecto

Las primeras 40 líneas son cálculo puro, no HTML. Merece la pena entenderlas:

### 1. Rellenar los días sin ventas
`ventasPorDia()` solo devuelve los días que **tuvieron** ventas. Si el gráfico usara eso
directamente, un martes sin pedidos simplemente desaparecería. Por eso se recorre el rango
día a día y se completa con 0:

```php
$ventasMap = [];
foreach ($ventasDia as $d) { $ventasMap[$d['dia']] = (float) $d['total']; }
for ($c = $ini; $c <= $fin; $c->modify("+{$paso} day")) { ... }
```

### 2. Agrupar en períodos largos
```php
$paso = $dias > 45 ? 7 : 1;   // mas de 45 dias -> barras semanales
if (count($serie) >= 14) break;   // nunca mas de 14 barras
```
Así el gráfico nunca se llena de barras finísimas. Las etiquetas cambian también: día de
la semana (`Lun`, `Mar`…) si el paso es 1, o `d/m` si son semanas.

### 3. Un techo "bonito" para el eje Y
```php
$escala = (int) pow(10, max(0, strlen((string) (int) $maxVenta) - 2));
$ejeMax = (int) (ceil($maxVenta / max(1, $escala)) * $escala) ?: 1;
```
Redondea el máximo hacia arriba a una cifra redonda, para que el eje no marque
"187.432" sino "190.000". `$fmtEje` lo abrevia luego a `k` / `M`.

### 4. Los segmentos del donut
Se recorre `$ranking` acumulando porcentajes (`desde` → `hasta`) y asignando un color de
una paleta de 5 que rota con `$i % 5`.

## Las secciones

1. **KPIs** en *bento grid* — Ventas Totales, Órdenes, Ticket Promedio, Empleados Activos.
   Se pintan desde un array `$tarjetas`, no repitiendo el marcado 4 veces.
2. **Ventas Diarias** — el gráfico de barras, con su `<select name="periodo">` que hace
   `onchange="this.form.submit()"` (recarga con `?periodo=`). El eje Y y la rejilla están
   dibujados con divs, **sin librería de gráficos**.
3. **Productos más vendidos** — el donut, hecho con `conic-gradient` en CSS.
4. **Órdenes recientes** — tabla con `estado_badge()` para el color de cada estado.
5. **Despacho y logística** — contadores por estado, estado de la cocina
   (Abierto / Pausado / Cerrado) y un botón a `/admin/rutas`.

## Notas
- Todo el tablero se filtra por el mismo `$periodo`, que `Periodo::valida()` sanea para que
  un `?periodo=` manipulado no rompa nada.
- No hay ninguna librería de gráficos: todo es CSS y PHP.
