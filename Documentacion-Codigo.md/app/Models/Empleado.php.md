# `app/Models/Empleado.php`

## Ubicación
`app/Models/Empleado.php` · namespace `App\Models` · **no extiende `Model`** (servicio)

## Propósito
Gestión **unificada** de ayudantes de cocina y domiciliarios: los trata como "empleados"
aunque estén en tablas distintas (`AYUDANTE_COCINA`, `DOMICILIARIO`).

## Dependencias
`App\Core\Database`.

## Métodos (todos `static`)

### `todos(): array`
Hace dos `SELECT` (uno por tabla) con columnas **alineadas** (mismos nombres, `NULL`
donde no aplica: `turno` para domiciliarios, `placa` para cocina), los une con
`array_merge` y ordena por nombre (`usort`). Cada fila tiene `rol` (`'cocina'` /
`'domiciliario'`).

### `buscar(string $rol, string $id): ?array`
`SELECT *` de la tabla que toca según el rol.

### `crear(string $rol, array $d, string $idAdmin): string`
`password_hash` de la contraseña + `INSERT` en la tabla correcta. Domiciliario lleva
además `tipo_vehiculo`, `placa`, `base_efectivo_asignada`. Marca `creado_por = $idAdmin`.
Devuelve el id.

### `actualizar(string $rol, string $id, array $d): void`
Actualiza nombre/correo/teléfono. **Solo** cambia la contraseña si `$d['contrasena']`
viene con valor. Campos extra según el rol. Usa el helper privado `patch()`.

### `darDeBaja(string $rol, string $id): void`
`UPDATE ... SET activo = 0` — **soft delete** (conserva historial).

### `reactivar(string $rol, string $id): void`
`UPDATE ... SET activo = 1`.

### `tienePedidos(string $rol, string $id): bool`
`SELECT COUNT(*) FROM PEDIDO WHERE id_ayudante|id_domiciliario = ?`.

### `eliminar(string $rol, string $id): bool`
Si `tienePedidos` → devuelve `false` (no borra). Si no → `DELETE` real, devuelve `true`.

### `activos(): int`
Cuenta ayudantes activos + domiciliarios activos (para el KPI del tablero).

### `private patch(string $tabla, string $keyCol, string $id, array $campos): void`
Arma un `UPDATE ... SET col = ?, ...` dinámico.

## Notas
- La "unificación" es solo a nivel de PHP; en la BD siguen siendo dos tablas.
- Nunca se borra a alguien con historial (integridad de reportes de caja).
