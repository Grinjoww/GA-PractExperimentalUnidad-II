# ADR-001: Selección de la tecnología principal del backend

- **Estado:** Aceptada
- **Fecha:** 2026-05-15
- **Decisores:** Equipo de desarrollo — PFC Clínica Veterinaria

## Contexto

El Proyecto Fin de Curso requiere desarrollar el backend de una clínica
veterinaria con autenticación segura y un módulo CRUD. La guía de la práctica
exige implementar la funcionalidad en **dos** tecnologías de servidor para
compararlas objetivamente: PHP 8.x (obligatorio) más una segunda a elección
entre ASP.NET Core y Java/Spring Boot.

Debíamos decidir cuál sería la tecnología "de referencia" del PFC a futuro,
considerando el contexto del mercado de Los Ríos y Ecuador: predominancia de
hosting compartido económico, disponibilidad de desarrolladores locales y
costos de despliegue.

## Decisión

Se adopta **PHP 8.2 como tecnología principal** del PFC, y **Java/Spring Boot 3
como segunda tecnología** de contraste.

Motivos a favor de PHP como principal:

- El hosting compartido en Ecuador soporta PHP de forma nativa y económica,
  mientras que desplegar ASP.NET Core o Spring Boot suele requerir un VPS.
- La curva de aprendizaje es más corta para el equipo, que ya domina PHP.
- `password_hash()` con Argon2id y PDO con prepared statements cubren los
  requisitos de seguridad sin dependencias externas.

Motivos para elegir Java/Spring Boot como segunda tecnología (en vez de ASP.NET):

- El equipo tiene experiencia previa con Java en otras asignaturas.
- Spring Security aporta CSRF, regeneración de sesión y cabeceras de seguridad
  "por defecto", lo que da un contraste didáctico claro frente al enfoque
  manual de PHP.

## Consecuencias

**Positivas:**
- Despliegue de bajo costo para el cliente final (hosting compartido).
- Reutilización del conocimiento existente del equipo.

**Negativas / Trade-offs:**
- Mantener dos bases de código duplica el esfuerzo de la práctica.
- PHP exige implementar manualmente controles que Spring trae integrados,
  lo que aumenta la superficie de error (mitigado con PHPStan nivel 5).
