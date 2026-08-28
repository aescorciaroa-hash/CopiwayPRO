<?php
/** Ayudas sencillas para manejar la sesion PHP y mensajes flash. */
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /** Guarda un mensaje flash para mostrarlo en la siguiente pagina (toast). */
    public static function flash(string $type, string $title, string $message = ''): void
    {
        $_SESSION['_flash'][] = ['type' => $type, 'title' => $title, 'message' => $message];
    }

    /** Devuelve y limpia todos los mensajes flash. */
    public static function pullFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }

    /** Token CSRF para formularios. */
    public static function csrf(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function checkCsrf(?string $token): bool
    {
        return is_string($token) && hash_equals($_SESSION['_csrf'] ?? '', $token);
    }
}
