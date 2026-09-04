# `app/Core/Session.php`

## Ubicación
`app/Core/Session.php`

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
  `config`, `inventory`, `cierre`, `whatsapp` (el partial `toast.php` les da color e icono;
  `AuthController` usa además `info`, que no está en esas listas y sale sin color).
- `pullFlash(): array` — **devuelve y borra** todos los mensajes.

  > Ojo: `partials/toast.php`, que es quien pinta los toasts en los 6 layouts, **no usa
  > este método**: lee `$_SESSION['_flash']` y hace `unset()` por su cuenta. Los únicos
  > que llaman a `pullFlash()` son las dos pantallas de PIN de estación
  > (`kitchen/estacion.php` y `delivery/estacion.php`), que no tienen layout.

### CSRF
- `csrf(): string` — si no existe, genera un token de 64 hex
  (`bin2hex(random_bytes(32))`) y lo guarda en `$_SESSION['_csrf']`. Devuelve el token.
- `checkCsrf(?string $token): bool` — `is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token)`.
  `hash_equals` compara en **tiempo constante** (no filtra info por el tiempo de respuesta).

## Notas
- El helper `csrf_field()` imprime `<input type="hidden" name="_csrf" value="<token>">`,
  y los layouts publican el mismo token en `<meta name="csrf-token">` para que
  `Copiway.post()` lo mande en las peticiones `fetch`.
- ⚠️ **`checkCsrf()` no se llama en ningún punto del sistema.** El token se genera y se
  envía, pero hoy ningún script lo valida: no existe un `verifyCsrf()` central. Ver la
  advertencia de `guia/12-Seguridad.md` §3.
- El carrito del cliente vive en `$_SESSION['carrito']` (lo maneja el modelo `Carrito`).
- Los datos del login están en `$_SESSION['auth_role']` y `$_SESSION['auth_user']`
  (los maneja `Auth`).
