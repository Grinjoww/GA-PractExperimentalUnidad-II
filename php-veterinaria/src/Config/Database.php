<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;

/**
 * Gestor de conexión PDO (patrón Singleton).
 *
 * Configura PDO en modo seguro:
 *  - ERRMODE_EXCEPTION  -> los errores lanzan PDOException (no fallan en silencio)
 *  - EMULATE_PREPARES=false -> usa prepared statements REALES del motor,
 *    lo que garantiza la separación entre query y datos (anti SQL injection)
 *  - FETCH_ASSOC por defecto
 */
final class Database
{
    private static ?PDO $instance = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: '127.0.0.1';
            $port = getenv('DB_PORT') ?: '5432';
            $name = getenv('DB_NAME') ?: 'veterinaria_db';
            $user = getenv('DB_USER') ?: 'postgres';
            $pass = getenv('DB_PASS') ?: 'postgres';

            // DSN para PostgreSQL (requiere la extensión pdo_pgsql habilitada)
            $dsn = "pgsql:host={$host};port={$port};dbname={$name};options='--client_encoding=UTF8'";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // No exponer detalles internos al cliente (A09: registro/monitoreo)
                error_log('Error de conexión BD: ' . $e->getMessage());
                http_response_code(500);
                exit('Error interno del servidor.');
            }
        }

        return self::$instance;
    }
}
