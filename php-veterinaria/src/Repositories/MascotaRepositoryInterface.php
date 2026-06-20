<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Mascota;

/**
 * Contrato del repositorio de mascotas (las 5 operaciones del CRUD).
 */
interface MascotaRepositoryInterface
{
    /** @return Mascota[] */
    public function listarTodas(): array;

    public function buscarPorId(int $id): ?Mascota;

    /** @return int ID de la mascota creada */
    public function crear(Mascota $mascota): int;

    public function actualizar(Mascota $mascota): bool;

    public function eliminar(int $id): bool;
}
