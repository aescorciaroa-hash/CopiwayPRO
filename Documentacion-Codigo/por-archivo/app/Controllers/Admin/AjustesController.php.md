# `app/Controllers/Admin/AjustesController.php`

## Ubicación
`app/Controllers/Admin/AjustesController.php` · namespace `App\Controllers\Admin`

## Propósito
**Ajustes y Caja** (`/admin/ajustes`): tarifa de domicilio, margen de ganancia, horario,
pausa de emergencia y **generación del cierre de caja del día**.

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Configuracion`, `App\Models\CierreCaja`.

## Métodos

### `index(): string` — `GET /admin/ajustes`
Vista con `config` (`Configuracion::get()`) y `cierre`
(`CierreCaja::calcular(date('Y-m-d'))` — resumen de hoy).

### `tarifa(): string` — `POST /admin/ajustes/tarifa`
`Configuracion::save(['tarifa_plana_domicilio' => (float) input(...)])` + flash.

### `margen(): string` — `POST /admin/ajustes/margen`
`Configuracion::save(['margen_ganancia_defecto' => ...])`. Afecta al precio del creador
"Arma tu Burger".

### `horario(): string` — `POST /admin/ajustes/horario`
Guarda `horario_apertura` y `horario_cierre` (le añade `:00` para hacerlas `TIME`).

### `pausa(): string` — `POST /admin/ajustes/pausa`
Alterna `pausa_emergencia_activa` (0 ↔ 1). Es el "botón de pánico": si está activo,
`Configuracion::cocinaAbierta()` devuelve `false` aunque sea horario.

### `vistaPrevia(): string` — `GET /admin/ajustes/cierre`
Vista `admin/ajustes/reporte` (layout `blank`, imprimible) con
`CierreCaja::calcular($fecha)`.

### `generarCierre(): string` — `POST /admin/ajustes/cierre`
`CierreCaja::generar($fecha, Auth::id())` — guarda `REPORTE_CAJA` + `DETALLE_AUDITORIA`
+ `LIQUIDACION_DOMICILIARIO` y enlaza los pedidos entregados del día. Flash + redirect.

## Notas
- Todos los ajustes se guardan en la **única fila** de `CONFIGURACION_SISTEMA`.
- `Configuracion::save` limpia la caché en memoria para que el nuevo valor se vea al instante.
