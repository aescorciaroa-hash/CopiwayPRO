<?php
/**
 * Router para el servidor embebido de PHP (solo desarrollo/pruebas):
 *   php -S 127.0.0.1:8899 -t public public/router.php
 * Con Laragon/Apache este archivo no se usa (manda .htaccess).
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;

if ($path !== '/' && is_file($file)) {
    return false; // deja que el servidor sirva el archivo estatico
}

require __DIR__ . '/index.php';
