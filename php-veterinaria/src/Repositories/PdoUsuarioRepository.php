<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;
use PDO;

/**
 * Implementación concreta del repositorio de usuarios usando PDO.
 *
 * TODAS las consultas usan prepared statements con marcadores nombrados.
 * NINGUNA query concatena datos del usuario (anti SQL injection - A03 OWASP).
 */
final class PdoUsuarioRepository implements UsuarioRepositoryInterface
{
    public function __construct(private readonly PDO $pdo) {}

    public function buscarPorEmail(string $email): ?Usuario
    {
        $sql = 'SELECT id, nombre, email, password_hash, rol
                FROM usuarios
                WHERE email = :email
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row ? $this->mapear($row) : null;
    }

    public function buscarPorId(int $id): ?Usuario
    {
        $sql = 'SELECT id, nombre, email, password_hash, rol
                FROM usuarios
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch();
        return $row ? $this->mapear($row) : null;
    }

    public function crear(string $nombre, string $email, string $passwordHash): int
    {
        $sql = 'INSERT INTO usuarios (nombre, email, password_hash)
                VALUES (:nombre, :email, :hash)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nombre', $nombre,       PDO::PARAM_STR);
        $stmt->bindValue(':email',  $email,        PDO::PARAM_STR);
        $stmt->bindValue(':hash',   $passwordHash, PDO::PARAM_STR);
        $stmt->execute();

        // PostgreSQL requiere el nombre de la secuencia; MySQL lo ignora.
        return (int) $this->pdo->lastInsertId('usuarios_id_seq');
    }

    public function existeEmail(string $email): bool
    {
        $sql = 'SELECT 1 FROM usuarios WHERE email = :email LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    private function mapear(array $row): Usuario
    {
        return new Usuario(
            id:           (int) $row['id'],
            nombre:       (string) $row['nombre'],
            email:        (string) $row['email'],
            passwordHash: (string) $row['password_hash'],
            rol:          (string) $row['rol'],
        );
    }
}
