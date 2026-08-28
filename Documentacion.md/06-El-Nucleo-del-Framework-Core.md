# 06 · El núcleo del sistema (carpeta `config/` y `app/Core/`)

El sistema utiliza una arquitectura simplificada y tradicional en PHP. A continuación se explican sus componentes principales:

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

## `Session.php` — Sesión y notificaciones flash

Maneja el estado de la sesión y las notificaciones temporales:
- `Session::start()`: Inicia la sesión PHP de forma segura.
- `Session::set/get/has/forget`: Manipula la superglobal `$_SESSION`.
- `Session::flash($tipo, $titulo, $mensaje)`: Registra notificaciones para la vista.

---

## `Auth.php` — Autenticación y roles

Controla el estado de autenticación de los usuarios:
- `Auth::login($rol, $usuario)`: Regenera el ID de sesión y registra el usuario logueado.
- `Auth::logout()`: Limpia la sesión y vacía el carrito.
- `Auth::check()`: Verifica si existe un usuario autenticado.
- `Auth::id()` / `Auth::user()` / `Auth::role()`: Devuelve información de la cuenta activa.

---

## `Periodo.php` — Rangos de fechas

Traduce palabras clave (`hoy`, `semana`, `mes`) a un rango de fechas MySQL `[desde, hasta]` para los reportes y filtros del Dashboard.

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
| `csrf_field()` | Genera campos o tokens de seguridad para formularios POST. |

