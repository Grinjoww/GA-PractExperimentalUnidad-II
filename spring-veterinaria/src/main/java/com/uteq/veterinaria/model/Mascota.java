package com.uteq.veterinaria.model;

/**
 * Entidad Mascota como record (Java 16+): inmutable y conciso.
 * Equivalente al readonly class de PHP 8.2.
 */
public record Mascota(
        Long id,
        String nombre,
        String especie,
        String raza,
        Integer edad,
        String nombreDueno,
        String telefono,
        Long creadoPor
) {}
