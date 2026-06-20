<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Entidad Mascota — entidad central del CRUD del PFC (veterinaria).
 */
final class Mascota
{
    public function __construct(
        public readonly ?int   $id,
        public readonly string $nombre,
        public readonly string $especie,
        public readonly ?string $raza,
        public readonly ?int   $edad,
        public readonly string $nombreDueno,
        public readonly ?string $telefono,
        public readonly ?int   $creadoPor = null,
    ) {}

    /** Construye una Mascota desde una fila asociativa de PDO. */
    public static function fromArray(array $row): self
    {
        return new self(
            id:          isset($row['id']) ? (int) $row['id'] : null,
            nombre:      (string) $row['nombre'],
            especie:     (string) $row['especie'],
            raza:        $row['raza']  ?? null,
            edad:        isset($row['edad']) ? (int) $row['edad'] : null,
            nombreDueno: (string) $row['nombre_dueno'],
            telefono:    $row['telefono'] ?? null,
            creadoPor:   isset($row['creado_por']) ? (int) $row['creado_por'] : null,
        );
    }
}
