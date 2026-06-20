<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Mascota;
use PDO;

/**
 * CRUD completo de Mascota con PDO + prepared statements.
 *
 * Demuestra la diferencia bindValue (by value) vs bindParam (by reference):
 *  - bindValue: liga el valor en el momento de la llamada.
 *  - bindParam: liga una REFERENCIA a la variable; su valor se lee en execute().
 * Aquí se usa bindValue por claridad y seguridad.
 */
final class PdoMascotaRepository implements MascotaRepositoryInterface
{
    public function __construct(private readonly PDO $pdo) {}

    /** @return Mascota[] */
    public function listarTodas(): array
    {
        $sql  = 'SELECT id, nombre, especie, raza, edad, nombre_dueno, telefono, creado_por
                 FROM mascotas
                 ORDER BY creado_en DESC';
        $stmt = $this->pdo->query($sql);

        $mascotas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $mascotas[] = Mascota::fromArray($row);
        }
        return $mascotas;
    }

    public function buscarPorId(int $id): ?Mascota
    {
        $sql = 'SELECT id, nombre, especie, raza, edad, nombre_dueno, telefono, creado_por
                FROM mascotas
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Mascota::fromArray($row) : null;
    }

    public function crear(Mascota $m): int
    {
        $sql = 'INSERT INTO mascotas
                    (nombre, especie, raza, edad, nombre_dueno, telefono, creado_por)
                VALUES
                    (:nombre, :especie, :raza, :edad, :dueno, :telefono, :creado_por)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nombre',     $m->nombre,      PDO::PARAM_STR);
        $stmt->bindValue(':especie',    $m->especie,     PDO::PARAM_STR);
        $stmt->bindValue(':raza',       $m->raza,        PDO::PARAM_STR);
        $stmt->bindValue(':edad',       $m->edad,        PDO::PARAM_INT);
        $stmt->bindValue(':dueno',      $m->nombreDueno, PDO::PARAM_STR);
        $stmt->bindValue(':telefono',   $m->telefono,    PDO::PARAM_STR);
        $stmt->bindValue(':creado_por', $m->creadoPor,   PDO::PARAM_INT);
        $stmt->execute();

        // PostgreSQL requiere el nombre de la secuencia; MySQL lo ignora.
        return (int) $this->pdo->lastInsertId('mascotas_id_seq');
    }

    public function actualizar(Mascota $m): bool
    {
        $sql = 'UPDATE mascotas SET
                    nombre       = :nombre,
                    especie      = :especie,
                    raza         = :raza,
                    edad         = :edad,
                    nombre_dueno = :dueno,
                    telefono     = :telefono
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nombre',   $m->nombre,      PDO::PARAM_STR);
        $stmt->bindValue(':especie',  $m->especie,     PDO::PARAM_STR);
        $stmt->bindValue(':raza',     $m->raza,        PDO::PARAM_STR);
        $stmt->bindValue(':edad',     $m->edad,        PDO::PARAM_INT);
        $stmt->bindValue(':dueno',    $m->nombreDueno, PDO::PARAM_STR);
        $stmt->bindValue(':telefono', $m->telefono,    PDO::PARAM_STR);
        $stmt->bindValue(':id',       $m->id,          PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function eliminar(int $id): bool
    {
        $sql  = 'DELETE FROM mascotas WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
