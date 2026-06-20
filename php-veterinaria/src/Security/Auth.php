<?php
declare(strict_types=1);

namespace App\Security;

/**
 * Control de acceso a rutas protegidas (A01: Broken Access Control).
 */
final class Auth
{
    public static function estaAutenticado(): bool
    {
        return !empty($_SESSION['usuario_id']);
    }

    /** Redirige al login si el usuario no ha iniciado sesión. */
    public static function requerirAutenticacion(): void
    {
        if (!self::estaAutenticado()) {
            header('Location: /login.php');
            exit;
        }
    }

    public static function idUsuarioActual(): ?int
    {
        return isset($_SESSION['usuario_id']) ? (int) $_SESSION['usuario_id'] : null;
    }

    public static function nombreUsuarioActual(): ?string
    {
        return $_SESSION['usuario_nombre'] ?? null;
    }

    /** Guarda los datos mínimos del usuario en sesión tras login correcto. */
    public static function iniciarSesionUsuario(int $id, string $nombre): void
    {
        $_SESSION['usuario_id']     = $id;
        $_SESSION['usuario_nombre'] = $nombre;
    }

    public static function cerrarSesion(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }
        session_destroy();
    }
}
