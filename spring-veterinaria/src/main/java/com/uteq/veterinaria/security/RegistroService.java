package com.uteq.veterinaria.security;

import com.uteq.veterinaria.repository.UsuarioRepository;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Service;

/**
 * Servicio de registro: valida y persiste usuarios con contraseña hasheada.
 */
@Service
public class RegistroService {

    private final UsuarioRepository usuarios;
    private final PasswordEncoder encoder;

    public RegistroService(UsuarioRepository usuarios, PasswordEncoder encoder) {
        this.usuarios = usuarios;
        this.encoder = encoder;
    }

    /** @return null si todo bien, o un mensaje de error. */
    public String registrar(String nombre, String email, String password) {
        if (nombre == null || nombre.isBlank()
                || email == null || email.isBlank()
                || password == null || password.isBlank()) {
            return "Todos los campos son obligatorios.";
        }
        if (!email.matches("^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$")) {
            return "El correo no tiene un formato válido.";
        }
        if (password.length() < 8) {
            return "La contraseña debe tener al menos 8 caracteres.";
        }
        if (usuarios.existeEmail(email.trim())) {
            return "Ya existe una cuenta con ese correo.";
        }

        String hash = encoder.encode(password);
        usuarios.crear(nombre.trim(), email.trim(), hash);
        return null;
    }
}
