# `app/Core/helpers.php`

## Ubicación
`app/Core/helpers.php`

## Propósito
Funciones **globales** (no una clase: funciones sueltas) disponibles en todo el sistema:
escapado HTML, URLs, formato de dinero, redirecciones, UUID y los helpers que usan las
vistas para pintar errores de formulario y badges de estado.

Se carga con `require_once` desde `public/index.php` y desde cada script de controlador.

## Al cargarse hace dos cosas

### 1. Arranca la sesión
```php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
```

### 2. Define la constante `APP_BASE`
Es la carpeta base del proyecto **vista desde el navegador**. Se detecta sola:

| URL | `APP_BASE` |
|---|---|
| `http://localhost/Copiway2/public/login` | `/Copiway2/public` |
| `http://copiway2.test/login` | `''` (cadena vacía) |

Primero mira `config/config.php` → `app.base_url`; si está vacío (lo normal), la deduce
de `$_SERVER['SCRIPT_NAME']` quitando el `/index.php` o `/router.php` final.

Gracias a esto los enlaces y las redirecciones funcionan igual con dominio propio que
bajo una subcarpeta de localhost, **sin tocar configuración**.

## Funciones

### Salida y formato

| Función | Qué hace |
|---|---|
| `e($value): string` | `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`. **Escapa todo lo que se imprime** → evita XSS |
| `money($value): string` | `15000` → `"$ 15.000"` (punto como separador de miles, formato colombiano) |
| `mods_html(array $pers): string` | Pinta las personalizaciones de una línea: `SIN X` con clase `.mod-sin` (rojo) y `EXTRA Y` con `.mod-extra` (verde), unidas por ` · ` |
| `estado_badge(string $estado): array` | Devuelve `['texto' => 'En Cocina', 'clases' => 'bg-blue-100 text-blue-700']` para cada uno de los 6 estados. Cualquier estado no previsto cae en gris con `ucfirst()` |

### URLs y navegación

| Función | Qué hace |
|---|---|
| `url($path = ''): string` | `APP_BASE . '/' . $path`. **Todos los `action` y `href` la usan** |
| `asset($path): string` | `APP_BASE . '/assets/' . $path` |
| `current_path(): string` | La ruta actual sin la carpeta base, para marcar el enlace activo del menú |
| `redirect($url): void` | `header('Location: ...')` + `exit`. Antepone `APP_BASE` a las rutas internas, saltándose las que empiezan por `//` o que ya lo llevan |

### Formularios

| Función | Qué hace |
|---|---|
| `csrf_field(): string` | `<input type="hidden" name="_csrf" value="...">` con el token de `Session::csrf()` |
| `old($key, $default = '')` | El valor que el usuario había escrito, guardado en `$_SESSION['_old']` |
| `error($key): string` | El mensaje de error de ese campo (`$_SESSION['_errors']`), o `''` |
| `has_errors(): bool` | Si hay algún error pendiente |
| `clear_errors(): void` | Borra `_errors` y `_old` |

> El único que llena `_errors` y `_old` hoy es `AuthController` en la acción `register`.

### Utilidades

| Función | Qué hace |
|---|---|
| `uuid(): string` | UUID v4 de 36 caracteres, para las llaves `CHAR(36)`. Pone los bits de versión y variante a mano |
| `now(): string` | La fecha/hora actual en formato MySQL (`Y-m-d H:i:s`) |
| `dd(...$vars): void` | *Dump and die*: imprime un `var_dump` con estilo y corta la ejecución. **Solo para depurar** |

## Notas
- Estas funciones son globales: se llaman directas (`e($x)`), sin `->` ni `::`.
- `e()` es la defensa principal contra XSS: la regla es **escapar siempre** lo que venga
  del usuario o de la base de datos. Ver `../../guia/12-Seguridad.md` §2.
- `redirect()` lleva la lógica de `APP_BASE`, por eso hay que usarla en lugar de escribir
  `header('Location: ...')` a mano.
- `csrf_field()` genera el campo, pero **hoy nadie valida el token** al recibir el POST.
  Ver `Session.php.md` y `../../guia/12-Seguridad.md` §3.
