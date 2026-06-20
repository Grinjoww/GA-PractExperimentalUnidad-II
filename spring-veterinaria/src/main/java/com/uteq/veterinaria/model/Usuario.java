package com.uteq.veterinaria.model;

/**
 * Entidad Usuario.
 */
public record Usuario(
        Long id,
        String nombre,
        String email,
        String passwordHash,
        String rol
) {}
