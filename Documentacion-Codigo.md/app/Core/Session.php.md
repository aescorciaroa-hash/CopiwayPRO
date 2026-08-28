# `app/Core/Session.php`

## Ubicación
`app/Core/Session.php` · namespace `App\Core`

## Propósito
Envoltorio de la sesión PHP (`$_SESSION`). Añade **mensajes flash** (toasts que duran
una petición) y el **token CSRF**.

## Métodos (todos `static`)

### Manejo básico de sesión
| Método | Qué hace |
|--------|----------|
| `start()` | `session_start()` si no está iniciada |
| `set($key, $value)` | `$_SESSION[$key] = $value` |
| `get($key, $default = null)` | `$_SESSION[$key] ?? $default` |
| `has($key)` | `isset($_SESSION[$key])` |
| `forget($key)` | `unset($_SESSION[$key])` |
| `destroy()` | vacía `$_SESSION` y `session_destroy()` |

### Mensajes flash (toasts)
- `flash(string $type, string $title, string $message = '')` — apila un mensaje en
  `$_SESSION['_flash']`. `$type` = `success`, `danger`, `warning`, `cart`, `staff`,
  `config`, `inventory`, `cierre`, `whatsapp`… (el partial `toast.php` les da color e icono).
- `pullFlash(): array` — **devuelve y borra** todos los mensajes. Lo llama `View::render`
  para pasárselos al layout.

### CSRF
- `csrf(): string` — si no existe, genera un token de 64 hex
  (`bin2hex(random_bytes(32))`) y lo guarda en `$_SESSION['_csrf']`. Devuelve el token.
- `checkCsrf(?string $token): bool` — `is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token)`.
  `hash_equals` compara en **tiempo constante** (no filtra info por el tiempo de respuesta).

## Notas
- El helper `csrf_field()` imprime `<input type="hidden" name="_csrf" value="<token>">`.
- `Controller::verifyCsrf()` usa `checkCsrf()` en cada POST.
- El carrito del cliente vive en `$_SESSION['carrito']` (lo maneja el modelo `Carrito`).
- Los datos del login están en `$_SESSION['auth_role']` y `$_SESSION['auth_user']`
  (los maneja `Auth`).
