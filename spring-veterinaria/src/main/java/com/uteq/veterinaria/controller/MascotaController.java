package com.uteq.veterinaria.controller;

import com.uteq.veterinaria.model.Mascota;
import com.uteq.veterinaria.repository.MascotaRepository;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/**
 * Controlador del CRUD de mascotas (ruta protegida por Spring Security).
 * Todas las operaciones POST llevan token CSRF que Thymeleaf inyecta solo.
 */
@Controller
@RequestMapping("/mascotas")
public class MascotaController {

    private final MascotaRepository repo;

    public MascotaController(MascotaRepository repo) {
        this.repo = repo;
    }

    @GetMapping
    public String listar(Model model) {
        model.addAttribute("mascotas", repo.listarTodas());
        return "mascotas/listar";
    }

    @GetMapping("/nueva")
    public String formNueva(Model model) {
        model.addAttribute("mascota", null);
        return "mascotas/form";
    }

    @GetMapping("/editar/{id}")
    public String formEditar(@PathVariable Long id, Model model) {
        Mascota m = repo.buscarPorId(id).orElse(null);
        if (m == null) {
            return "redirect:/mascotas";
        }
        model.addAttribute("mascota", m);
        return "mascotas/form";
    }

    @PostMapping("/guardar")
    public String guardar(
            @RequestParam(required = false) Long id,
            @RequestParam String nombre,
            @RequestParam String especie,
            @RequestParam(required = false) String raza,
            @RequestParam(required = false) Integer edad,
            @RequestParam String nombreDueno,
            @RequestParam(required = false) String telefono,
            RedirectAttributes ra) {

        Mascota m = new Mascota(id, nombre, especie,
                blankToNull(raza), edad, nombreDueno,
                blankToNull(telefono), null);

        if (id == null) {
            repo.crear(m);
            ra.addFlashAttribute("exito", "Mascota registrada correctamente.");
        } else {
            repo.actualizar(m);
            ra.addFlashAttribute("exito", "Mascota actualizada correctamente.");
        }
        return "redirect:/mascotas";
    }

    @PostMapping("/eliminar/{id}")
    public String eliminar(@PathVariable Long id, RedirectAttributes ra) {
        repo.eliminar(id);
        ra.addFlashAttribute("exito", "Mascota eliminada correctamente.");
        return "redirect:/mascotas";
    }

    private static String blankToNull(String s) {
        return (s == null || s.isBlank()) ? null : s.trim();
    }
}
