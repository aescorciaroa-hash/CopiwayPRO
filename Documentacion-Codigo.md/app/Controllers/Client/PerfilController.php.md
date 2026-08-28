# `app/Controllers/Client/PerfilController.php`

## Ubicación
`app/Controllers/Client/PerfilController.php` · namespace `App\Controllers\Client`

## Propósito
**Gestión de Cuenta** del cliente (`/client/perfil`): editar datos personales y cambiar
la contraseña. También muestra los puntos de fidelidad.

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Cliente`.

## Métodos

### `index(): string` — `GET /client/perfil`
Vista `client/perfil` con `cliente` = `Cliente::find(Auth::id())`.

### `actualizar(): string` — `POST /client/perfil`
1. `verifyCsrf()` + `validate(nombre 3–120, telefono min 7, fecha_nacimiento date)`.
2. `Cliente::update(Auth::id(), [nombre, telefono, direccion, fecha_nacimiento])`.
3. `Auth::refresh(['nombre' => ..., 'telefono' => ...])` — actualiza la sesión para que
   el nombre nuevo se vea de inmediato en la cabecera.
4. Flash + redirect.

> Poner la **fecha de nacimiento** activa el 15% de descuento el día del cumpleaños.

### `password(): string` — `POST /client/perfil/password`
1. `verifyCsrf()`.
2. `password_verify($actual, $cliente['contrasena'])` — si la actual no coincide →
   flash "Contraseña incorrecta".
3. Si la nueva tiene < 6 caracteres o no coincide con la confirmación → flash de error.
4. `Cliente::update(Auth::id(), ['contrasena' => password_hash($nueva, PASSWORD_BCRYPT)])`.
5. Flash "Contraseña actualizada".

## Notas
- Cambiar la contraseña **siempre** exige conocer la actual (defensa contra secuestro
  de sesión).
- El correo no se puede cambiar aquí (es identificador de login).
