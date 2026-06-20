package com.uteq.veterinaria;

import com.uteq.veterinaria.model.Mascota;
import org.junit.jupiter.api.Test;

import static org.junit.jupiter.api.Assertions.*;

/**
 * Pruebas unitarias del modelo Mascota.
 * Verifican la construcción inmutable del record.
 */
class MascotaTest {

    @Test
    void creaMascotaConTodosLosCampos() {
        Mascota m = new Mascota(1L, "Firulais", "Perro", "Labrador",
                4, "María Pérez", "0991234567", null);

        assertEquals("Firulais", m.nombre());
        assertEquals("Perro", m.especie());
        assertEquals(4, m.edad());
        assertEquals("María Pérez", m.nombreDueno());
    }

    @Test
    void permiteCamposOpcionalesNulos() {
        Mascota m = new Mascota(null, "Michi", "Gato", null,
                null, "Juan Gómez", null, null);

        assertNull(m.raza());
        assertNull(m.edad());
        assertNull(m.telefono());
        assertEquals("Gato", m.especie());
    }
}
