<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Usuario;

/**
 * Contrato del repositorio de usuarios.
 * Desacopla la lógica de negocio del mecanismo de persistencia (DIP de SOLID).
 */
interface UsuarioRepositoryInterface
{
    public function buscarPorEmail(string $email): ?Usuario;

    public function buscarPorId(int $id): ?Usuario;

    /** @return int ID del usuario recién creado */
    public function crear(string $nombre, string $email, string $passwordHash): int;

    public function existeEmail(string $email): bool;
}
