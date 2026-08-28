<?php
/**
 * Autenticacion y control de acceso por rol (RBAC).
 * Roles: admin, cliente, cocina, domiciliario.
 */
class Auth
{
    /** Inicia sesion guardando el usuario y su rol. */
    public static function login(string $role, array $user): void
    {
        session_regenerate_id(true);
        Session::set('auth_role', $role);
        Session::set('auth_user', $user);
    }

    public static function logout(): void
    {
        Session::forget('auth_role');
        Session::forget('auth_user');
        Session::forget('carrito');
    }

    public static function check(): bool
    {
        return Session::has('auth_role');
    }

    public static function role(): ?string
    {
        return Session::get('auth_role');
    }

    public static function user(): ?array
    {
        return Session::get('auth_user');
    }

    public static function id(): ?string
    {
        return self::user()['id'] ?? null;
    }

    public static function is(string $role): bool
    {
        return self::role() === $role;
    }

    /** Actualiza en sesion algunos campos del usuario (tras editar el perfil). */
    public static function refresh(array $fields): void
    {
        $user = self::user() ?? [];
        Session::set('auth_user', array_merge($user, $fields));
    }

    /** Bloquea el acceso si no hay sesion o el rol no coincide. */
    public static function requireRole(string $role): void
    {
        if (!self::check()) {
            Session::flash('warning', 'Inicia sesion', 'Debes iniciar sesion para continuar.');
            redirect('/login');
        }

        if (self::role() !== $role) {
            http_response_code(403);
            exit('Acceso denegado');
        }

        $usuarioModel = new Usuario();
        if (!$usuarioModel->porId($role, (string) self::id())) {
            self::logout();
            Session::flash('warning', 'Tu sesion expiro', 'Vuelve a iniciar sesion para continuar.');
            redirect('/login');
        }
    }

    /** Ruta del panel segun el rol. */
    public static function homeFor(string $role): string
    {
        return match ($role) {
            'admin'        => '/admin',
            'cocina'       => '/kitchen',
            'domiciliario' => '/delivery',
            default        => '/client',
        };
    }
}

