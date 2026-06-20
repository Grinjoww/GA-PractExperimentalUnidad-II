Práctica Experimental Unidad II · Aplicaciones Web. · UTEQ

Backend de una clínica veterinaria implementado en **dos tecnologías de servidor**
con autenticación segura, CRUD de mascotas y controles de seguridad OWASP.

## Tecnologías

| | Tecnología 1 | Tecnología 2 |
|---|---|---|
| Lenguaje | PHP 8.2 | Java 21 |
| Framework | Vanilla + PDO | Spring Boot 3.3 |
| Acceso a datos | PDO (prepared statements) | JdbcTemplate |
| Plantillas | PHP nativo | Thymeleaf |
| Seguridad | Manual (Argon2id, CSRF) | Spring Security (BCrypt) |
| Puerto | 8080 | 8081 |

## Entidad del CRUD

`Mascota` (nombre, especie, raza, edad, dueño, teléfono) — entidad central de la
clínica veterinaria.

## Controles de seguridad OWASP implementados

- **A01 — Control de acceso:** rutas protegidas (guard en PHP / `authorizeHttpRequests` en Spring).
- **A02/A07 — Criptografía y autenticación:** Argon2id (PHP) y BCrypt costo 12 (Spring).
- **A03 — Inyección:** 100% prepared statements, sin concatenación de SQL.
- **A05 — Mala configuración:** cabeceras `X-Content-Type-Options`, `X-Frame-Options`, CSP, `Referrer-Policy`.
- **XSS:** saneamiento de salidas (`htmlspecialchars` / escape automático de Thymeleaf).
- **CSRF:** token sincronizador en todos los formularios.
- **Session fixation:** regeneración de ID al autenticar.

## Cómo ejecutar

### PHP

```bash
cd php-veterinaria
mysql -u root -p < sql/schema.sql      # crea la BD
composer install                        # instala PHPStan (dev)
php -S localhost:8080 -t public         # servidor embebido
```

Abrir http://localhost:8080

Análisis estático:
```bash
composer phpstan        # PHPStan nivel 5
```

### Spring Boot

```bash
cd spring-veterinaria
# Configurar credenciales en src/main/resources/application.properties
./mvnw spring-boot:run
```

Abrir http://localhost:8081

## Estructura

```
php-veterinaria/        # Implementación PHP 8.2
spring-veterinaria/     # Implementación Spring Boot 3
docs/adr/               # Architecture Decision Records
  ADR-001-tecnologia-backend.md
  ADR-002-estrategia-bd.md
```

## Decisiones de arquitectura

Ver [ADR-001](docs/adr/ADR-001-tecnologia-backend.md) (elección de tecnología) y
[ADR-002](docs/adr/ADR-002-estrategia-bd.md) (estrategia de base de datos).

## Equipo

- Lucas Sánchez Rodríguez
- Johan Stalin Carvajal Loor
- Michael Xavier Fajardo Montes
- Jaime Josué Mariscal Cabrera
