# `app/Core/Auth.php`

## Ubicación
`app/Core/Auth.php` · namespace `App\Core`

## Propósito
Autenticación y **control de acceso por rol (RBAC)**. Guarda quién está logueado, con
qué rol, y decide si puede entrar a una ruta.

Roles posibles: `admin`, `cliente`, `cocina`, `domiciliario`.

## Dependencias
Usa `Session`, `View` (para el 403) y `App\Models\Usuario` (para validar que la cuenta
siga existiendo).

## Métodos (todos `static`)

### `login(string $role, array $user): void`
```php
session_regenerate_id(true);          // nuevo ID de sesión -> evita "session fixation"
Session::set('auth_role', $role);
Session::set('auth_user', $user);      // ['id','nombre','correo','telefono']
```

### `logout(): void`
Borra `auth_role`, `auth_user` y `carrito`.

### Consultas
| Método | Devuelve |
|--------|----------|
| `check()` | `true` si hay sesión (`Session::has('auth_role')`) |
| `role()` | el rol o `null` |
| `user()` | el array del usuario o `null` |
| `id()` | `user()['id'] ?? null` |
| `is('admin')` | `true` si el rol coincide |

### `refresh(array $fields): void`
Actualiza algunos campos del usuario en sesión (ej. tras editar el perfil):
`Session::set('auth_user', array_merge(user(), $fields))`.

### `requireRole(string $role): void` — el "middleware"
Lo llama el `Router` antes del controlador. Comprueba 3 cosas:
1. **¿Hay sesión?** Si no → mensaje + `redirect('/login')`.
2. **¿El rol coincide?** Si no → `http_response_code(403)` + vista `errors/403` + `exit`.
3. **¿La cuenta aún existe en la BD?** `Usuario::porId($role, self::id())`. Si no
   (por reinstalar la BD o eliminar el empleado) → `logout()` + "Tu sesión expiró" +
   `redirect('/login')`.

### `homeFor(string $role): string`
Ruta del panel de cada rol:
```php
'admin' => '/admin', 'cocina' => '/kitchen', 'domiciliario' => '/delivery', default => '/client'
```

## Notas
- El chequeo 3 se añadió para evitar errores fatales cuando la sesión apunta a un id
  que ya no existe.
- Cocina y domiciliario, además de esto, necesitan el flag de PIN de estación
  (`estacion_cocina_ok` / `estacion_domi_ok`), que verifican sus propios controladores.
