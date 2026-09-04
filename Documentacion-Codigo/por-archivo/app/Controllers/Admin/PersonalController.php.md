# `app/Controllers/Admin/PersonalController.php`

## Ubicación
`app/Controllers/Admin/PersonalController.php`

## Propósito
Script procesador de **Equipo y Personal**: alta, edición, baja (soft delete),
reactivación y eliminación definitiva de ayudantes de cocina y domiciliarios.

El listado `/admin/personal` (GET) lo arma `public/index.php` con `Empleado::todos()`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Empleado.php`, `Models/Usuario.php`.

## Acciones (`$action`)

Todas las rutas llevan el **`{rol}`** (`cocina` o `domiciliario`) dentro de la URL;
`public/index.php` lo extrae y lo deja en `$_POST['rol']`.

### `cargar` — `GET /admin/personal/{rol}/{id}`
`Empleado::buscar($rol, $id)` → **JSON** sin el campo `contrasena` (404 si no existe).
Alimenta el modal de edición.

> Ojo: la acción se llama **`cargar`** (no `datos`).

### `crear` — `POST /admin/personal`
1. Valida `nombre`, `correo` y `contrasena`.
2. `Usuario::existeCorreoOTelefono($correo, $telefono)` → rechaza duplicados
   (comprueba las 4 tablas de cuentas).
3. `Empleado::crear($rol, $_POST, Auth::id())`.

### `actualizar` — `POST /admin/personal/{rol}/{id}`
`Empleado::actualizar($rol, $id, $_POST)`.

### `baja` — `POST /admin/personal/{rol}/{id}/baja`
`Empleado::darDeBaja(...)` → **soft delete** (`activo = 0`), conserva el historial.

### `reactivar` — `POST /admin/personal/{rol}/{id}/reactivar`
`Empleado::reactivar(...)` → `activo = 1`.

### `eliminar` — `POST /admin/personal/{rol}/{id}/eliminar`
`Empleado::eliminar(...)`. Devuelve `false` si el empleado tiene pedidos asociados:
en ese caso se muestra un flash de advertencia y **no** se borra.

Cualquier otro `$action` → `redirect('/admin/personal')`.

## Notas
- Todas las acciones POST terminan en `redirect('/admin/personal')` (**PRG**), con flash
  de tipo `staff` o `danger`.
- El hash bcrypt de la contraseña lo hace el modelo `Empleado`, no este script.
