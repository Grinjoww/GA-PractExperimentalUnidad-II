package com.uteq.veterinaria.controller;

import com.uteq.veterinaria.security.RegistroService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;

/**
 * Controlador de autenticación.
 * El login lo procesa Spring Security (formLogin); aquí solo
 * mostramos las vistas y manejamos el registro.
 */
@Controller
public class AuthController {

    private final RegistroService registroService;

    public AuthController(RegistroService registroService) {
        this.registroService = registroService;
    }

    @GetMapping("/login")
    public String login() {
        return "auth/login";
    }

    @GetMapping("/registro")
    public String formRegistro() {
        return "auth/registro";
    }

    @PostMapping("/registro")
    public String registrar(
            @RequestParam String nombre,
            @RequestParam String email,
            @RequestParam String password,
            Model model) {

        String error = registroService.registrar(nombre, email, password);
        if (error != null) {
            model.addAttribute("error", error);
            return "auth/registro";
        }
        model.addAttribute("ok", true);
        return "auth/registro";
    }
}
