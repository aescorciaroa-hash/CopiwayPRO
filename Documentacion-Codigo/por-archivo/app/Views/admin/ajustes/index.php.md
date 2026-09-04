# `app/Views/admin/ajustes/index.php`

## Ubicación
`app/Views/admin/ajustes/index.php`

## Propósito
**Ajustes y Caja**: los cuatro parámetros del negocio y la generación del cierre de caja
del día.

Cubre **RF-46** a **RF-53** (tarifa, margen, horario, pausa de emergencia y cierre).

## Quién la renderiza
`public/index.php`, `case '/admin/ajustes'`, con el layout **`admin`**.

## Variables que espera

| Variable | De dónde viene |
|---|---|
| `$config` | `Configuracion::get()` |
| `$cierre` | `CierreCaja::obtener(date('Y-m-d'))` — que **recalcula**, no lee un reporte guardado |
| `$cierresRecientes` | `CierreCaja::recientes()` |

La vista empieza con `$t = $cierre['totales'];` para abreviar.

## Los cuatro formularios de ajustes

Cada uno es independiente y va a su propia ruta:

| Sección | Campo | Ruta (POST) |
|---|---|---|
| Tarifa Plana de Domicilio | `tarifa_plana_domicilio` (`number`, `step=100`) | `/admin/ajustes/tarifa` |
| Margen de Ganancia (Arma tu Burger) | `margen_ganancia_defecto` (0–100) | `/admin/ajustes/margen` |
| Horario de Atención | `horario_apertura`, `horario_cierre` (`type="time"`) | `/admin/ajustes/horario` |
| Pausa de Emergencia (Botón de Pánico) | *(ninguno)* | `/admin/ajustes/pausa` |

La **pausa no manda ningún campo**: el controlador lee el valor actual y lo invierte. Es
un interruptor.

> El horario se guarda añadiéndole los segundos (`'08:00'` → `'08:00:00'`), porque el
> input `type="time"` solo devuelve `HH:MM`.

## El bloque de cierre de caja

Muestra el resumen del día (ventas, efectivo, digital) y abre un modal (`openCierre`) con
el detalle antes de confirmar. El formulario final:

```php
<input type="hidden" name="fecha" value="<?= date('Y-m-d') ?>">
```
→ `POST /admin/ajustes/cierre` → `CierreCaja::generar($fecha, Auth::id())`.

También hay un enlace al **reporte imprimible** (`/admin/ajustes/reporte`).

## Notas
- Generar el cierre dos veces el mismo día **no duplica nada**: `generar()` hace un upsert
  sobre el `UNIQUE(id_admin, fecha)` de `REPORTE_CAJA`.
- Lo que se ve aquí es siempre el cálculo en vivo, porque `obtener()` es un alias de
  `calcular()`. Ver `../../../app/Models/CierreCaja.php.md`.
- La tarifa y el margen que se guardan aquí los consumen el checkout y el Creador
  Interactivo respectivamente.
