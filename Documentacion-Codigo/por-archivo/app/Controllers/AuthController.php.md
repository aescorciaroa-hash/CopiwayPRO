# `app/Controllers/AuthController.php`

## Ubicación
`app/Controllers/AuthController.php` · namespace `App\Controllers`

## Propósito
Maneja **inicio de sesión, registro de clientes, recuperación de contraseña y cierre de
sesión**. Hay un solo login para los 4 roles.

## Dependencias
`Controller`, `Auth`, `Session`, `App\Models\Usuario`, `App\Models\Cliente`.

## Métodos

### `showLogin(): string` — `GET /login`
Si ya hay sesión → `redirect(Auth::homeFor(rol))`. Si no → vista `auth/login`
(layout `auth`).

### `login(): string` — `POST /login`
1. `verifyCsrf()`.
2. `validate(['correo' => 'required|email', 'contrasena' => 'required'])`.
3. `Usuario::porCorreo($correo)` — busca el correo en las 4 tablas de cuentas.
4. Si no existe **o** `!password_verify(...)` → `back('/login', ['contrasena' => 'Correo o contraseña incorrectos.'])`.
5. Si `!$cuenta['activo']` → error "cuenta inactiva".
6. `Auth::login($cuenta['role'], [id, nombre, correo, telefono])`.
7. Flash "Ingreso exitoso · Entrando al panel de ...".
8. Redirección según rol:
   - `cocina` → `/kitchen/estacion` (login de PIN)
   - `domiciliario` → `/delivery/estacion`
   - resto → `Auth::homeFor($rol)`

### `devLogin(string $correo): string` — `GET /_dev/login/{correo}`
**Solo si `app.debug` es true** (la ruta ni se registra si no). Inicia sesión sin
contraseña (por correo). Marca los flags de estación para cocina/domiciliario. Redirige
a `?next=` si empieza por `/`, o al panel del rol. Sirve para pruebas/capturas.

### `showRegister()` / `register()` — `/register`
Registra un **cliente**:
1. `validate`: nombre (3–120), correo, teléfono (7–20), fecha de nacimiento (date),
   contraseña (min 6, `confirmed`), `habeas_data` (`accepted`).
2. `Usuario::existeCorreoOTelefono` → si ya existe, error.
3. `Cliente::registrar($data)` (hashea la contraseña).
4. `Auth::login('cliente', ...)` + flash de bienvenida + `redirect('/client')`.

### `showForgot()` / `forgot()` — `/forgot-password`
Flujo **simulado**: siempre responde "Si el correo existe, enviamos un código" y
redirige a `/login`. En producción enviaría un código real (tabla `CODIGO_VERIFICACION`).

### `logout(): string` — `POST /logout`
`verifyCsrf()` + `Auth::logout()` + flash "Sesión cerrada" + `redirect('/login')`.

## Notas
- La clave del diseño: **un solo formulario**, y `Usuario::porCorreo` descubre el rol.
- El mensaje de error no dice si falló el correo o la contraseña (no da pistas a un atacante).
