package com.uteq.veterinaria.repository;

import com.uteq.veterinaria.model.Mascota;
import org.springframework.dao.EmptyResultDataAccessException;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.stereotype.Repository;

import java.util.List;
import java.util.Optional;

/**
 * CRUD completo de Mascota con JdbcTemplate + parámetros '?'.
 * Equivalente directo al PdoMascotaRepository de la versión PHP.
 */
@Repository
public class MascotaRepository {

    private final JdbcTemplate jdbc;

    public MascotaRepository(JdbcTemplate jdbc) {
        this.jdbc = jdbc;
    }

    private static final RowMapper<Mascota> MAPPER = (rs, n) -> {
        // Conversión segura: PostgreSQL devuelve Integer para SMALLINT/INTEGER,
        // MySQL puede devolver otros tipos. Number cubre ambos casos.
        Object edadObj   = rs.getObject("edad");
        Object creadoObj = rs.getObject("creado_por");
        return new Mascota(
            rs.getLong("id"),
            rs.getString("nombre"),
            rs.getString("especie"),
            rs.getString("raza"),
            edadObj   == null ? null : ((Number) edadObj).intValue(),
            rs.getString("nombre_dueno"),
            rs.getString("telefono"),
            creadoObj == null ? null : ((Number) creadoObj).longValue()
        );
    };

    public List<Mascota> listarTodas() {
        String sql = "SELECT id, nombre, especie, raza, edad, nombre_dueno, " +
                     "telefono, creado_por FROM mascotas ORDER BY creado_en DESC";
        return jdbc.query(sql, MAPPER);
    }

    public Optional<Mascota> buscarPorId(Long id) {
        String sql = "SELECT id, nombre, especie, raza, edad, nombre_dueno, " +
                     "telefono, creado_por FROM mascotas WHERE id = ? LIMIT 1";
        try {
            return Optional.ofNullable(jdbc.queryForObject(sql, MAPPER, id));
        } catch (EmptyResultDataAccessException e) {
            return Optional.empty();
        }
    }

    public void crear(Mascota m) {
        String sql = "INSERT INTO mascotas " +
                     "(nombre, especie, raza, edad, nombre_dueno, telefono, creado_por) " +
                     "VALUES (?, ?, ?, ?, ?, ?, ?)";
        jdbc.update(sql, m.nombre(), m.especie(), m.raza(), m.edad(),
                    m.nombreDueno(), m.telefono(), m.creadoPor());
    }

    public void actualizar(Mascota m) {
        String sql = "UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, " +
                     "edad = ?, nombre_dueno = ?, telefono = ? WHERE id = ?";
        jdbc.update(sql, m.nombre(), m.especie(), m.raza(), m.edad(),
                    m.nombreDueno(), m.telefono(), m.id());
    }

    public void eliminar(Long id) {
        jdbc.update("DELETE FROM mascotas WHERE id = ?", id);
    }
}
