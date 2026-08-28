# `public/router.php`

## Ubicación
`public/router.php`

## Propósito
Es un "router" auxiliar **solo para el servidor web incorporado de PHP** (el que se usa
para pruebas rápidas sin Apache/Laragon):

```bash
php -S 127.0.0.1:8899 -t public public/router.php
```

Con Apache/Laragon **este archivo no se usa** (manda `public/.htaccess`).

## Contenido

```php
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // deja que el servidor sirva el archivo estático
}

require __DIR__ . '/index.php';
```

Lógica:
1. Saca la ruta pedida (sin los parámetros `?...`).
2. Si esa ruta corresponde a un **archivo real** dentro de `public/` (por ejemplo
   `/assets/css/app.css`), devuelve `false` → el servidor de PHP entrega ese archivo
   tal cual (CSS, JS, imágenes).
3. Para **cualquier otra ruta**, incluye `index.php` → entra al Front Controller.

## Notas
- Es el equivalente del `.htaccess` pero para el servidor `php -S`.
- Se le pasa a `php -S` como último argumento.
