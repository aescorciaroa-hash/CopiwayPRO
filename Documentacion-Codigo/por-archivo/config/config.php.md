# `config/config.php`

## Ubicación
`config/config.php`

## Propósito
Un archivo que **devuelve un array** con parámetros de la aplicación. No define clases ni
constantes: se lee con `require`.

```php
$cfg = @require __DIR__ . '/../../config/config.php';
$manual = $cfg['app']['base_url'] ?? '';
```

## Contenido

| Bloque | Claves | ¿Quién lo lee? |
|---|---|---|
| `db` | `host`, `port`, `name`, `user`, `password`, `charset` | **Solo `database/install.php`** |
| `app` | `name`, `nombre_legal`, `base_url`, `timezone`, `debug` | Solo `base_url`, desde `helpers.php` |
| `paths` | `root`, `app`, `views`, `uploads`, `storage` | ❌ Nadie |

## Lo único que realmente se usa: `app.base_url`

Lo lee `app/Core/helpers.php` para calcular la constante `APP_BASE`:

- Si `base_url` **tiene valor**, `APP_BASE` es ese valor (con `/` al principio y sin `/`
  al final).
- Si está **vacío** (el caso normal), `helpers.php` deduce la carpeta base sola a partir
  de `$_SERVER['SCRIPT_NAME']`.

Por eso el proyecto funciona igual en `http://copiway2.test` que en
`http://localhost/Copiway2/public/` sin tocar nada.

## ⚠️ El bloque `db`: lo lee el instalador, pero no la aplicación

```php
// database/install.php
$config = require __DIR__ . '/../config/config.php';
$db = $config['db'];
$pdo = new PDO("mysql:host={$db['host']};port={$db['port']};...", $db['user'], $db['password'], ...);
```

Pero **`config/database.php` no lee este archivo**: tiene sus propios `$host`, `$user`,
`$password` y `$dbname` escritos a mano.

> **Consecuencia práctica: si cambias la contraseña de MySQL hay que editar los DOS
> archivos.** Solo este y el instalador funcionará pero la aplicación no arrancará; solo
> `database.php` y será al revés.

## ⚠️ Claves que no hacen nada

- **`app.debug`** — no hay ningún `if ($cfg['app']['debug'])` en el código. La
  visibilidad de errores depende del `php.ini`, no de esta clave.
- **`app.timezone`** — no se llama a `date_default_timezone_set()` en ninguna parte; PHP
  usa la zona horaria de su configuración.
- **`paths`** — el código construye sus rutas con `__DIR__` y `dirname(__DIR__)`.

Son restos de una versión anterior del proyecto. Se conservan porque no molestan, pero
conviene saber que **editarlas no cambia el comportamiento**.

## Notas
- El `@` de `@require` en `helpers.php` silencia el aviso si el archivo no existiera, para
  que el sistema arranque igual.
- Ver `database.php.md` para la conexión de la aplicación y `../database/install.php.md`
  para el instalador.
