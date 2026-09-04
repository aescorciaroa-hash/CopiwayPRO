# 06 · El núcleo del sistema (`config/` y `app/Core/`)

> El nombre del archivo dice "Framework" por razones históricas: **`app/Core/` no es un
> framework**. Son cuatro archivos sueltos de utilidades, sin clase base, sin router y
> sin contenedor. Todo se carga con `require_once`.

El sistema utiliza una arquitectura simplificada y tradicional en PHP. Estos son sus
componentes:

---

## Conexión a Base de Datos (`config/database.php`)

Se encarga de la conexión única y directa con la base de datos usando la extensión **MySQLi clásica de PHP**:

- Define las variables de conexión: `$host`, `$user`, `$password`, `$dbname`.
- Crea la variable global `$conn = new mysqli($host, $user, $password, $dbname);`.
- Configura el cotejo de caracteres a `utf8mb4`.
- Maneja los errores de conexión de forma directa:
  ```php
  if ($conn->connect_error) {
      die("Error de conexión a la base de datos: " . $conn->connect_error);
  }
  ```

---

## `Session.php` — Sesión, mensajes flash y token CSRF

Clase de **métodos estáticos** (se llaman con `::`, sin instanciar):
- `Session::start()`: `session_start()` solo si no había sesión ya iniciada.
- `Session::set/get/has/forget/destroy`: manipulan la superglobal `$_SESSION`.
- `Session::flash($tipo, $titulo, $mensaje)`: apila una notificación en
  `$_SESSION['_flash']`.
- `Session::pullFlash()`: **devuelve y borra** los mensajes. Lo llaman los layouts justo
  antes de incluir `partials/toast.php`.
- `Session::csrf()` / `Session::checkCsrf($token)`: generan y comparan el token CSRF.

> ⚠️ `checkCsrf()` **no se llama en ningún punto del sistema**. El token se genera y se
> envía en cada formulario, pero nadie lo valida. Ver `12-Seguridad.md` §3.

---

## `Auth.php` — Autenticación y roles

También de **métodos estáticos**:
- `Auth::login($rol, $usuario)`: hace `session_regenerate_id(true)` (evita *session
  fixation*) y guarda `auth_role` y `auth_user` en la sesión.
- `Auth::logout()`: borra rol, usuario y carrito.
- `Auth::check()` / `Auth::role()` / `Auth::user()` / `Auth::id()` / `Auth::is($rol)`:
  consultas sobre la sesión.
- `Auth::refresh($campos)`: actualiza datos del usuario en sesión tras editar el perfil.
- `Auth::homeFor($rol)`: la ruta del panel de cada rol (`/admin`, `/kitchen`,
  `/delivery`, `/client`). La usa el guardia de `public/index.php`.

> `Auth::requireRole()` también existe, pero **no la llama nadie**: el control de acceso
> real es el bloque del principio de `public/index.php`. Ver `07` y `12-Seguridad.md` §5.

---

## `Periodo.php` — Rangos de fechas

Traduce las claves de `Periodo::OPCIONES` (`hoy`, `semana`, `semana_pasada`, `mes`,
`mes_pasado`) a un rango `[desde, hasta]` en formato MySQL, para el filtro del tablero:

- `Periodo::rango($clave)`: usa `DateTimeImmutable('today')` y expresiones relativas
  (`monday this week`, `first day of last month`…). Devuelve el día completo, de
  `00:00:00` a `23:59:59`.
- `Periodo::valida($clave)`: si la clave no está en `OPCIONES` devuelve `'semana'`.
  Protege contra valores manipulados en la URL (`?periodo=cualquier-cosa`).

---

## `helpers.php` — Funciones globales auxiliares

Incluso en un esquema simple, las funciones globales facilitan la escritura de código en las vistas y controladores:

| Función | Qué hace |
|---------|----------|
| `uuid()` | Genera un identificador único v4 de 36 caracteres. |
| `e($texto)` | Escapa caracteres HTML (`htmlspecialchars`) para prevenir ataques XSS. |
| `url($ruta)` / `asset($ruta)` | Construye URLs absolutas o relativas del sistema. |
| `redirect($ruta)` | Ejecuta redirecciones HTTP directas (`header('Location: ...')` + `exit`). |
| `money($valor)` | Formatea importes numéricos a moneda de forma legible (ej. `$ 15.000`). |
| `csrf_field()` | Imprime el `<input type="hidden" name="_csrf">` con el token. |
| `current_path()` | La ruta actual sin la carpeta base, para marcar el menú activo. |
| `old()` / `error()` / `has_errors()` / `clear_errors()` | Recuperan lo que el usuario escribió y los errores de validación tras un formulario fallido. |
| `mods_html()` | Pinta las personalizaciones: `SIN X` en rojo, `EXTRA Y` en verde. |
| `estado_badge()` | Texto y clases de color del badge de cada estado de pedido. |
| `now()` | La fecha/hora actual en formato MySQL. |
| `dd()` | *Dump and die*, solo para depurar. |

Además, al cargarse, `helpers.php` **arranca la sesión** y define la constante
**`APP_BASE`**: la carpeta base del proyecto vista desde el navegador
(`/Copiway2/public` en localhost, `''` con dominio propio). Se detecta sola a partir de
`$_SERVER['SCRIPT_NAME']`, y por eso el proyecto funciona igual de las dos formas sin
tocar configuración.

