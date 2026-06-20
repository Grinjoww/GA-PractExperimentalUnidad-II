<?php
declare(strict_types=1);

namespace App\Security;

/**
 * Utilidades de seguridad transversales.
 *
 * Cubre varias mitigaciones del OWASP Top 10:
 *  - A03 (Inyección/XSS): escape() para saneamiento de salidas.
 *  - A07 (Fallas de Autenticación): regeneración de ID de sesión.
 *  - A05 (Mala configuración): cabeceras de seguridad HTTP.
 *  - CSRF: generación y verificación de token sincronizador.
 */
final class Security
{
    /**
     * Inicia una sesión con cookies endurecidas.
     * HttpOnly  -> la cookie no es accesible desde JavaScript (mitiga robo por XSS).
     * Secure    -> solo se envía sobre HTTPS.
     * SameSite=Strict -> la cookie no viaja en peticiones cross-site (mitiga CSRF).
     */
    public static function iniciarSesionSegura(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'httponly' => true,
            'secure'   => isset($_SERVER['HTTPS']),
            'samesite' => 'Strict',
        ]);

        session_start();
    }

    /**
     * Saneamiento de salida para prevenir XSS reflejado/almacenado.
     * Convierte caracteres especiales HTML en entidades.
     */
    public static function escape(?string $valor): string
    {
        return htmlspecialchars($valor ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * Devuelve el token CSRF de la sesión, generándolo si no existe.
     * Se usa el patrón "synchronizer token".
     */
    public static function tokenCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica el token CSRF recibido contra el de la sesión.
     * hash_equals() previene timing attacks en la comparación.
     */
    public static function verificarCsrf(?string $token): bool
    {
        return !empty($_SESSION['csrf_token'])
            && is_string($token)
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    /** Genera el campo oculto <input> con el token CSRF, ya escapado. */
    public static function campoCsrf(): string
    {
        $token = self::escape(self::tokenCsrf());
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }

    /**
     * Cabeceras de seguridad HTTP (A05: Security Misconfiguration).
     */
    public static function cabecerasSeguridad(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: no-referrer');
        header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");
        header('X-XSS-Protection: 1; mode=block');
    }

    /**
     * Regenera el ID de sesión. Debe llamarse justo tras autenticar
     * para mitigar session fixation (A07).
     */
    public static function regenerarSesion(): void
    {
        session_regenerate_id(true);
    }
}
