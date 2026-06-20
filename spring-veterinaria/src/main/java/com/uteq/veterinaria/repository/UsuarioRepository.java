package com.uteq.veterinaria.repository;

import com.uteq.veterinaria.model.Usuario;
import org.springframework.dao.EmptyResultDataAccessException;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.jdbc.core.RowMapper;
import org.springframework.stereotype.Repository;

import java.util.Optional;

/**
 * Repositorio de usuarios usando JdbcTemplate.
 *
 * JdbcTemplate parametriza con '?' y PreparedStatement internamente,
 * lo que separa el SQL de los datos (anti SQL injection - A03 OWASP),
 * igual que los prepared statements de PDO en PHP.
 */
@Repository
public class UsuarioRepository {

    private final JdbcTemplate jdbc;

    public UsuarioRepository(JdbcTemplate jdbc) {
        this.jdbc = jdbc;
    }

    private static final RowMapper<Usuario> MAPPER = (rs, n) -> new Usuario(
            rs.getLong("id"),
            rs.getString("nombre"),
            rs.getString("email"),
            rs.getString("password_hash"),
            rs.getString("rol")
    );

    public Optional<Usuario> buscarPorEmail(String email) {
        String sql = "SELECT id, nombre, email, password_hash, rol " +
                     "FROM usuarios WHERE email = ? LIMIT 1";
        try {
            return Optional.ofNullable(jdbc.queryForObject(sql, MAPPER, email));
        } catch (EmptyResultDataAccessException e) {
            return Optional.empty();
        }
    }

    public boolean existeEmail(String email) {
        String sql = "SELECT COUNT(*) FROM usuarios WHERE email = ?";
        Integer count = jdbc.queryForObject(sql, Integer.class, email);
        return count != null && count > 0;
    }

    public void crear(String nombre, String email, String passwordHash) {
        String sql = "INSERT INTO usuarios (nombre, email, password_hash) " +
                     "VALUES (?, ?, ?)";
        jdbc.update(sql, nombre, email, passwordHash);
    }
}
