# `app/Controllers/Admin/PersonalController.php`

## Ubicación
`app/Controllers/Admin/PersonalController.php` · namespace `App\Controllers\Admin`

## Propósito
**Equipo y Personal** (`/admin/personal`): alta, edición, baja (soft delete),
reactivación y borrado de ayudantes de cocina y domiciliarios.

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Empleado`, `App\Models\Usuario`.

## Métodos

### `index(): string` — `GET /admin/personal`
Vista con `empleados` (`Empleado::todos()` — cocina + domiciliarios unidos) y
`activos` (contador para el KPI).

### `crear(): string` — `POST /admin/personal`
1. `verifyCsrf()` + `validate(nombre, correo, telefono, contrasena min 6,
   rol in:cocina,domiciliario)`.
2. `Usuario::existeCorreoOTelefono` → si ya existe, error.
3. `Empleado::crear($d['rol'], $d, Auth::id())` — inserta en `AYUDANTE_COCINA` o
   `DOMICILIARIO`, guarda hash, marca `creado_por`.

### `datos(string $rol, string $id): string` — `GET /admin/personal/{rol}/{id}`
`Empleado::buscar($rol, $id)` en JSON (modal de edición).

### `actualizar(string $rol, string $id): string` — `POST /admin/personal/{rol}/{id}`
`Empleado::actualizar($rol, $id, $this->all())`. Solo cambia la contraseña si viene una nueva.

### `baja(string $rol, string $id): string` — `POST .../baja`
`Empleado::darDeBaja` → **soft delete** (`activo = 0`). Conserva el historial.

### `reactivar(string $rol, string $id): string` — `POST .../reactivar`
`Empleado::reactivar` → `activo = 1`.

### `eliminar(string $rol, string $id): string` — `POST .../eliminar`
`Empleado::eliminar` → **borrado real**, pero solo si no tiene pedidos. Si tiene →
flash "solo puedes darlo de baja".

## Notas
- El parámetro `{rol}` de la URL es `cocina` o `domiciliario` y decide en qué tabla
  trabajar.
- Regla de negocio: nunca se borra a alguien con historial (integridad de reportes).
