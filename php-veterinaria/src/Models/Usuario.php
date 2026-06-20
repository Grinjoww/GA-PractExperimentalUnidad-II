<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Entidad Usuario.
 * Usa readonly properties (PHP 8.2) para inmutabilidad:
 * una vez creado el objeto, sus datos no pueden alterarse.
 */
final class Usuario
{
    public function __construct(
        public readonly ?int   $id,
        public readonly string $nombre,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly string $rol = 'VET',
    ) {}
}
