<?php
/**
 * Funciones auxiliares para el proyecto Copiway (PHP plano).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    return '/assets/' . ltrim($path, '/');
}

/** Redirige a una ruta y termina la ejecucion. */
function redirect(string $url): void
{
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

/** Etiqueta y color de badge para un estado de pedido. */
function estado_badge(string $estado): array
{
    return [
        'pendiente'      => ['Pendiente', 'bg-amber-100 text-amber-700'],
        'en_preparacion' => ['En Cocina', 'bg-blue-100 text-blue-700'],
        'listo'          => ['Listo', 'bg-purple-100 text-purple-700'],
        'en_camino'      => ['En Camino', 'bg-brand-100 text-brand-700'],
        'entregado'      => ['Entregado', 'bg-emerald-100 text-emerald-700'],
        'cancelado'      => ['Cancelado', 'bg-red-100 text-red-700'],
    ][$estado] ?? [ucfirst($estado), 'bg-gray-100 text-gray-700'];
}

