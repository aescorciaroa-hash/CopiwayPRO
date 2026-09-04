# `app/Controllers/Admin/AjustesController.php`

## Ubicación
`app/Controllers/Admin/AjustesController.php`

## Propósito
Script procesador de **Ajustes y Caja**: guarda la tarifa de domicilio, el margen de
ganancia, el horario, la pausa de emergencia y **genera el cierre de caja del día**.

Solo atiende **POST**. La pantalla `/admin/ajustes` (GET) no pasa por aquí: la arma
`public/index.php` en su bloque `case '/admin/ajustes'`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Configuracion.php`, `Models/CierreCaja.php`, `Models/Pedido.php`.

Instancia `$configModel = new Configuracion()` y `$cierreModel = new CierreCaja()`.

## Acciones (`$action`)

El script lee `$action = $_GET['action'] ?? $_POST['action'] ?? ''`.
`public/index.php` deduce ese valor a partir del final de la ruta.

| Ruta (POST) | `$action` | Qué hace |
|---|---|---|
| `/admin/ajustes/tarifa` | `tarifa` | `Configuracion::save(['tarifa_plana_domicilio' => (float) $_POST[...]])` |
| `/admin/ajustes/margen` | `margen` | `save(['margen_ganancia_defecto' => ...])`. Afecta el precio del creador "Arma tu Burger" |
| `/admin/ajustes/horario` | `horario` | Guarda `horario_apertura` y `horario_cierre` (les añade `:00` de segundos) |
| `/admin/ajustes/pausa` | `pausa` | **Alterna** `pausa_emergencia_activa` (lee el valor actual y lo invierte) |
| `/admin/ajustes/cierre` | `generarCierre` | `CierreCaja::generar($fecha, Auth::id())` con `$_POST['fecha']` o la de hoy |

Cualquier otro valor de `$action` → `redirect('/admin/ajustes')`.

## Notas
- Todas las acciones terminan en `redirect('/admin/ajustes')` (patrón **PRG**), dejando
  antes un mensaje en `$_SESSION['_flash']` con tipo `config` o `cierre`.
- **No existe** una acción de vista previa del cierre: la vista `admin/ajustes/index.php`
  ya recibe `$cierre` calculado desde `index.php`.
- El **reporte imprimible** del cierre (`/admin/ajustes/reporte`) tampoco pasa por aquí:
  lo sirve directamente `public/index.php`.
