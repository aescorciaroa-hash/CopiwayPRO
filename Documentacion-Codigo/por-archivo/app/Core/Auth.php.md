# `app/Core/Auth.php`

## Ubicación
`app/Core/Auth.php`

## Propósito
Autenticación y **control de acceso por rol (RBAC)**. Guarda quién está logueado y con
qué rol, y ofrece las consultas que usan las vistas y los controladores.

Roles posibles: `admin`, `cliente`, `cocina`, `domiciliario`.

## Dependencias
Usa `Session` y el modelo `Usuario` (para validar que la cuenta siga existiendo).
No tiene `namespace`: se carga con `require_once` desde `public/index.php` y desde cada
script de controlador.

## Métodos (todos `static`)

### `login(string $role, array $user): void`
```php
session_regenerate_id(true);          // nuevo ID de sesión -> evita "session fixation"
Session::set('auth_role', $role);
Session::set('auth_user', $user);
```

El array `$user` que arma `AuthController` lleva `id`, `nombre`, `correo`, `telefono`
**más la clave propia del rol** (`id_cliente`, `id_ayudante`, `id_domiciliario`), que es
la que consultan las vistas de cada panel.

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

### `homeFor(string $role): string`
Ruta del panel de cada rol: `admin` → `/admin`, `cocina` → `/kitchen`,
`domiciliario` → `/delivery`, cualquier otro → `/client`.
La usa `public/index.php` para devolver a cada quien a su panel cuando intenta entrar
a un área que no le corresponde.

### `requireRole(string $role): void` — **actualmente sin uso**
Comprueba 3 cosas:
1. **¿Hay sesión?** Si no → mensaje + `redirect('/login')`.
2. **¿El rol coincide?** Si no → `http_response_code(403)` + `exit('Acceso denegado')`.
3. **¿La cuenta aún existe en la BD?** `Usuario::porId($role, self::id())`. Si no
   (por reinstalar la BD o eliminar el empleado) → `logout()` + "Tu sesión expiró" +
   `redirect('/login')`.

> ⚠️ **Este método no lo invoca nadie.** El control de acceso real lo hace la función
> anónima del principio de `public/index.php`, que deduce el rol requerido a partir del
> prefijo de la ruta (`/admin`, `/client`, `/kitchen`, `/delivery`) y **redirige** en vez
> de devolver 403. `requireRole()` se conserva como alternativa, pero hoy es código muerto
> (y su comprobación nº 3, la de "la cuenta ya no existe", por lo tanto no se ejecuta).

## Notas
- El chequeo del rol vive en un solo sitio: `public/index.php`. Ver `guia/12-Seguridad.md` §5.
- Cocina y domiciliario, además del rol, necesitan el flag de PIN de estación
  (`estacion_cocina_ok` / `estacion_domi_ok`). Ese segundo factor **también lo verifica
  `public/index.php`**, no los controladores.
