# `app/Controllers/Client/PerfilController.php`

## Ubicación
`app/Controllers/Client/PerfilController.php`

## Propósito
Script procesador del **perfil del cliente**: datos personales y cambio de contraseña.

La pantalla `/client/perfil` (GET) la arma `public/index.php`.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Cliente.php`.

## Acciones (`$action`)

### `actualizar` — `POST /client/perfil`
Es la acción por defecto de la ruta.

1. Exige `nombre` y `telefono`.
2. `UPDATE CLIENTE SET nombre, telefono, direccion, fecha_nacimiento WHERE id_cliente = ?`
   con sentencia preparada sobre `$conn`.
3. **`Auth::refresh(['nombre' => ..., 'telefono' => ...])`** — actualiza también la copia
   que hay en sesión, para que la cabecera muestre el nombre nuevo sin volver a entrar.

### `password` — `POST /client/perfil/password`
1. Lee la contraseña guardada del cliente logueado.
2. `password_verify($actual, $row['contrasena'])`; si falla → flash y vuelve.
3. Exige que la nueva tenga **6+ caracteres** y coincida con la confirmación.
4. `password_hash($nueva, PASSWORD_BCRYPT)` y `UPDATE CLIENTE SET contrasena = ?`.

Cualquier otro `$action` → `redirect('/client/perfil')`.

## Notas
- La `fecha_nacimiento` que se guarda aquí es la que habilita el **descuento de
  cumpleaños del 15%** (`Cliente::esCumpleanos`).
- El correo **no** se puede cambiar desde el perfil (es la llave del login).
