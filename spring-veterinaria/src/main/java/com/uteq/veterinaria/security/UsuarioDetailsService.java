package com.uteq.veterinaria.security;

import com.uteq.veterinaria.model.Usuario;
import com.uteq.veterinaria.repository.UsuarioRepository;
import org.springframework.security.core.authority.SimpleGrantedAuthority;
import org.springframework.security.core.userdetails.User;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.core.userdetails.UserDetailsService;
import org.springframework.security.core.userdetails.UsernameNotFoundException;
import org.springframework.stereotype.Service;

import java.util.List;

/**
 * Conecta Spring Security con la tabla 'usuarios'.
 * Spring invoca loadUserByUsername durante el login y compara
 * la contraseña con el PasswordEncoder (BCrypt) configurado.
 */
@Service
public class UsuarioDetailsService implements UserDetailsService {

    private final UsuarioRepository usuarios;

    public UsuarioDetailsService(UsuarioRepository usuarios) {
        this.usuarios = usuarios;
    }

    @Override
    public UserDetails loadUserByUsername(String email)
            throws UsernameNotFoundException {

        Usuario u = usuarios.buscarPorEmail(email)
                .orElseThrow(() ->
                        new UsernameNotFoundException("Credenciales incorrectas."));

        return new User(
                u.email(),
                u.passwordHash(),
                List.of(new SimpleGrantedAuthority("ROLE_" + u.rol()))
        );
    }
}
