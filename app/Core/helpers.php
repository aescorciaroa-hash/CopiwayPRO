<?php
/**
 * Funciones auxiliares para el proyecto Copiway (PHP plano).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Carpeta base del proyecto vista desde el navegador. Se detecta sola:
 *   http://localhost/Copiway2/public/login   -> APP_BASE = '/Copiway2/public'
 *   http://copiway2.test/login               -> APP_BASE = ''
 * Asi los enlaces y redirecciones funcionan igual con dominio propio o con localhost.
 */
if (!defined('APP_BASE')) {
    $cfg = @require __DIR__ . '/../../config/config.php';
    $manual = $cfg['app']['base_url'] ?? '';
    if ($manual !== '') {
        define('APP_BASE', '/' . trim($manual, '/'));
    } else {
        $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
        $base   = preg_replace('#/(index|router)\.php$#', '', $script);
        define('APP_BASE', rtrim($base, '/') === '' ? '' : rtrim($base, '/'));
    }
}

/** Genera un UUID v4 (para las llaves CHAR(36) de la base de datos). */
function uuid(): string
{
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/** Escapa texto para imprimir en HTML de forma segura. */
function e($value): string
{
    return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8');
}

/** URL de un asset publico. */
function asset(string $path): string
{
    return APP_BASE . '/assets/' . ltrim($path, '/');
}

/** Genera la URL absoluta para una ruta. */
function url(string $path = ''): string
{
    return APP_BASE . '/' . ltrim($path, '/');
}

/** Ruta actual sin la carpeta base (para marcar el enlace activo del menu). */
function current_path(): string
{
    $p = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    if (APP_BASE !== '' && strpos($p, APP_BASE) === 0) {
        $p = substr($p, strlen(APP_BASE));
    }
    return $p === '' ? '/' : $p;
}

/** Genera el campo oculto HTML con el token CSRF. */
function csrf_field(): string
{
    $token = Session::csrf();
    return '<input type="hidden" name="_csrf" value="' . e($token) . '">';
}

/** Redirige a una ruta y termina la ejecucion. */
function redirect(string $url): void
{
    // Prefija la carpeta base a las rutas internas ('/login', '/app/Views/...').
    if (APP_BASE !== '' && isset($url[0]) && $url[0] === '/'
        && substr($url, 0, 2) !== '//'
        && strpos($url, APP_BASE . '/') !== 0) {
        $url = APP_BASE . $url;
    }
    header('Location: ' . $url);
    exit;
}

/** Formatea un valor como pesos colombianos: $ 15.000 */
function money($value): string
{
    return '$ ' . number_format((float) $value, 0, ',', '.');
}

/** Devuelve el valor antiguo de un input tras un error de validacion. */
function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

/** Devuelve el mensaje de error de un campo (o '' si no hay). */
function error(string $key): string
{
    return $_SESSION['_errors'][$key] ?? '';
}

/** Indica si hay algun error de validacion pendiente. */
function has_errors(): bool
{
    return !empty($_SESSION['_errors']);
}

/** Limpia errores y valores antiguos. */
function clear_errors(): void
{
    unset($_SESSION['_errors'], $_SESSION['_old']);
}

/** dd() para depurar rapido. */
function dd(...$vars): void
{
    echo '<pre style="background:#151515;color:#0f0;padding:16px;border-radius:8px;overflow:auto">';
    foreach ($vars as $v) {
        var_dump($v);
    }
    echo '</pre>';
    exit;
}

/** Devuelve la fecha/hora actual en formato MySQL. */
function now(): string
{
    return date('Y-m-d H:i:s');
}

/** Renderiza las personalizaciones de una linea de pedido con resaltado SIN (rojo) / EXTRA (verde). */
function mods_html(array $personalizaciones): string
{
    $out = [];
    foreach ($personalizaciones as $p) {
        if (($p['accion_modificacion'] ?? '') === 'quitar') {
            $out[] = '<span class="mod-sin">SIN ' . e($p['nombre']) . '</span>';
        } else {
            $out[] = '<span class="mod-extra">EXTRA ' . e($p['nombre']) . '</span>';
        }
    }
    return implode(' · ', $out);
}

/**
 * Etiqueta y color del badge para un estado de pedido.
 * Devuelve un array asociativo: ['texto' => ..., 'clases' => ...].
 */
function estado_badge(string $estado): array
{
    if ($estado === 'pendiente') {
        return ['texto' => 'Pendiente', 'clases' => 'bg-amber-100 text-amber-700'];
    }
    if ($estado === 'en_preparacion') {
        return ['texto' => 'En Cocina', 'clases' => 'bg-blue-100 text-blue-700'];
    }
    if ($estado === 'listo') {
        return ['texto' => 'Listo', 'clases' => 'bg-purple-100 text-purple-700'];
    }
    if ($estado === 'en_camino') {
        return ['texto' => 'En Camino', 'clases' => 'bg-brand-100 text-brand-700'];
    }
    if ($estado === 'entregado') {
        return ['texto' => 'Entregado', 'clases' => 'bg-emerald-100 text-emerald-700'];
    }
    if ($estado === 'cancelado') {
        return ['texto' => 'Cancelado', 'clases' => 'bg-red-100 text-red-700'];
    }
    // Cualquier otro estado no previsto: gris y con la primera letra en mayuscula.
    return ['texto' => ucfirst($estado), 'clases' => 'bg-gray-100 text-gray-700'];
}

