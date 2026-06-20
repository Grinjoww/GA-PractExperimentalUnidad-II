<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UsuarioRepositoryInterface;

/**
 * Lógica de autenticación: registro y verificación de credenciales.
 *
 * Contraseñas con password_hash() + PASSWORD_ARGON2ID:
 *  - Argon2id es resistente a ataques por GPU y side-channel.
 *  - password_verify() compara en tiempo constante (mitiga timing attack).
 */
final class AuthService
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarios
    ) {}

    /**
     * Registra un usuario nuevo.
     * @return array{ok: bool, error?: string, id?: int}
     */
    public function registrar(string $nombre, string $email, string $password): array
    {
        $nombre = trim($nombre);
        $email  = trim($email);

        if ($nombre === '' || $email === '' || $password === '') {
            return ['ok' => false, 'error' => 'Todos los campos son obligatorios.'];
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['ok' => false, 'error' => 'El correo no tiene un formato válido.'];
        }
        if (strlen($password) < 8) {
            return ['ok' => false, 'error' => 'La contraseña debe tener al menos 8 caracteres.'];
        }
        if ($this->usuarios->existeEmail($email)) {
            return ['ok' => false, 'error' => 'Ya existe una cuenta con ese correo.'];
        }

        // Argon2id con parámetros de costo explícitos.
        $hash = password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536, // 64 MB
            'time_cost'   => 4,
            'threads'     => 2,
        ]);

        $id = $this->usuarios->crear($nombre, $email, $hash);
        return ['ok' => true, 'id' => $id];
    }

    /**
     * Verifica credenciales de login.
     * @return array{ok: bool, error?: string, id?: int, nombre?: string}
     */
    public function login(string $email, string $password): array
    {
        $email = trim($email);
        $usuario = $this->usuarios->buscarPorEmail($email);

        // Mensaje genérico: no revelar si el correo existe (enumeración de usuarios).
        if ($usuario === null || !password_verify($password, $usuario->passwordHash)) {
            return ['ok' => false, 'error' => 'Credenciales incorrectas.'];
        }

        // Rehash si los parámetros de costo cambiaron (buena práctica).
        if (password_needs_rehash($usuario->passwordHash, PASSWORD_ARGON2ID)) {
            $nuevoHash = password_hash($password, PASSWORD_ARGON2ID);
            // Aquí se actualizaría el hash en BD (omitido por brevedad).
        }

        return ['ok' => true, 'id' => $usuario->id, 'nombre' => $usuario->nombre];
    }
}
