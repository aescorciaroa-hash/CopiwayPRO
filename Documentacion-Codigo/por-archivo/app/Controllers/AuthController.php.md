# `app/Controllers/AuthController.php`

## Ubicación
`app/Controllers/AuthController.php`

## Propósito
Script procesador de **autenticación**: login unificado, registro de cliente,
recuperación de contraseña (simulada) y cierre de sesión.

Las **pantallas** (`/login`, `/register`, `/forgot-password`) las sirve `public/index.php`,
que renderiza la vista con el layout `auth`. Este script solo procesa los **POST**.

## Dependencias (`require_once`)
`config/database.php`, `Core/helpers.php`, `Core/Session.php`, `Core/Auth.php`,
`Models/Usuario.php`, `Models/Cliente.php`.

## Cómo llega el `$action`
Los formularios de auth **no envían** un campo `action`. `public/index.php` lo deduce de
la ruta antes del `require`:

```php
$_POST['action'] = ($uri === '/forgot-password') ? 'forgot' : ltrim($uri, '/');
```

## Acciones (`$action`)

### `login` — `POST /login`
1. Exige `correo` y `contrasena` no vacíos.
2. `Usuario::porCorreo($correo)` → busca en las 4 tablas de cuentas.
3. `Usuario::verificarPassword($contrasena, $cuenta['row']['contrasena'])` (bcrypt).
4. Rechaza la cuenta si `activo` es falso ("Cuenta inactiva").
5. Arma el array de sesión con `id`, `nombre`, `correo`, `telefono` **más la clave propia
   del rol** (`id_cliente`, `id_ayudante`, `id_domiciliario`), que es la que usan las
   vistas de cada panel. Al domiciliario le guarda además `estado_disponibilidad`.
6. `Auth::login($rol, $sesion)` (regenera el ID de sesión).
7. Redirige según el rol: `/admin`, `/kitchen`, `/delivery` o `/client`.

> A Cocina y Domiciliario el guardia de `public/index.php` los desvía enseguida a su
> **login de estación** (`/kitchen/estacion`, `/delivery/estacion`) para pedir el PIN.

### `register` — `POST /register`
Registra un **cliente**:
1. Valida campo por campo y acumula en `$errores`: nombre, correo (`FILTER_VALIDATE_EMAIL`),
   teléfono, fecha de nacimiento (`strtotime`), contraseña de 6+ caracteres, confirmación
   que coincida y **aceptación de habeas data** obligatoria.
2. Si hay errores, los guarda en `$_SESSION['_errors']` y los valores escritos en
   `$_SESSION['_old']` (los leen los helpers `error()` y `old()` en la vista) y vuelve a
   `/register`.
3. `Usuario::existeCorreoOTelefono(...)` → rechaza duplicados.
4. `Cliente::registrar([...])` (hashea la contraseña y guarda la fecha de habeas data).
5. `Auth::login('cliente', [...])` → entra directo y redirige a `/client`.

### `forgot` — `POST /forgot-password`
Flujo **simulado**: siempre deja el mismo flash ("Si el correo existe, enviamos un
código") y redirige a `/login`. No consulta la tabla `CODIGO_VERIFICACION`.

### `logout` — `POST /logout`
`Auth::logout()` (borra rol, usuario y carrito) + flash + `/login`.

Cualquier otro `$action` → `redirect('/login')`.

## Notas
- El **cierre de sesión por GET** no pasa por aquí: lo resuelve `public/index.php`
  directamente (`case $uri === '/logout'`).
- **No existe** ninguna acción de login de desarrollo sin contraseña.
