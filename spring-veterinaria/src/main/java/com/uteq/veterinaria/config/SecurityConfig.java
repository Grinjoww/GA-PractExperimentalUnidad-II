package com.uteq.veterinaria.config;

import com.uteq.veterinaria.security.UsuarioDetailsService;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.config.annotation.web.configurers.HeadersConfigurer;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.header.writers.ReferrerPolicyHeaderWriter;

/**
 * Configuración central de seguridad (Spring Security 6).
 *
 * Compara con la versión PHP — aquí mucho viene "por defecto":
 *  - CSRF: habilitado automáticamente (token en cada formulario).
 *  - Sesión: ID se regenera al autenticar (anti session fixation) sin código extra.
 *  - Cabeceras de seguridad: configuradas vía headers().
 *  - Contraseñas: BCrypt mediante PasswordEncoder.
 *  - A01 (control de acceso): authorizeHttpRequests protege las rutas.
 */
@Configuration
public class SecurityConfig {

    private final UsuarioDetailsService userDetailsService;

    public SecurityConfig(UsuarioDetailsService userDetailsService) {
        this.userDetailsService = userDetailsService;
    }

    @Bean
    public PasswordEncoder passwordEncoder() {
        // Factor de costo 12 (mayor que el default 10) para más resistencia.
        return new BCryptPasswordEncoder(12);
    }

    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {
        http
            .userDetailsService(userDetailsService)
            // A01: control de acceso por ruta.
            .authorizeHttpRequests(auth -> auth
                .requestMatchers("/", "/login", "/registro", "/css/**").permitAll()
                .anyRequest().authenticated()
            )
            .formLogin(form -> form
                .loginPage("/login")
                .defaultSuccessUrl("/mascotas", true)
                .permitAll()
            )
            .logout(logout -> logout
                .logoutUrl("/logout")
                .logoutSuccessUrl("/login?logout")
                .permitAll()
            )
            // CSRF está habilitado por defecto; se deja explícito por claridad.
            // Cabeceras de seguridad HTTP (A05).
            .headers(headers -> headers
                .contentTypeOptions(c -> {})                       // X-Content-Type-Options: nosniff
                .frameOptions(HeadersConfigurer.FrameOptionsConfig::deny) // X-Frame-Options: DENY
                .referrerPolicy(r -> r.policy(
                        ReferrerPolicyHeaderWriter.ReferrerPolicy.NO_REFERRER))
                .contentSecurityPolicy(csp -> csp.policyDirectives(
                        "default-src 'self'; style-src 'self' 'unsafe-inline'"))
            );

        return http.build();
    }
}
