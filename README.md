Práctica Experimental Unidad II · Aplicaciones Web · UTEQ

Backend de una clínica veterinaria implementado en **dos tecnologías de servidor**
con autenticación segura, CRUD de mascotas y controles de seguridad OWASP.

## Tecnologías

|                | Tecnología 1              | Tecnología 2             |
| -------------- | ------------------------- | ------------------------ |
| Lenguaje       | PHP 8.2                   | Java 21                  |
| Framework      | Vanilla + PDO             | Spring Boot 3.3          |
| Base de datos  | PostgreSQL                | PostgreSQL               |
| Acceso a datos | PDO (prepared statements) | JdbcTemplate             |
| Plantillas     | PHP nativo                | Thymeleaf                |
| Seguridad      | Manual (Argon2id, CSRF)   | Spring Security (BCrypt) |
| Puerto         | 8080                      | 8081                     |

## Entidad del CRUD

`Mascota` (nombre, especie, raza, edad, dueño, teléfono) — entidad central de la
clínica veterinaria.

## Controles de seguridad OWASP implementados

* **A01 — Control de acceso:** rutas protegidas (guard en PHP / `authorizeHttpRequests` en Spring).
* **A02/A07 — Criptografía y autenticación:** Argon2id (PHP) y BCrypt costo 12 (Spring).
* **A03 — Inyección:** 100% prepared statements, sin concatenación de SQL.
* **A05 — Mala configuración:** cabeceras `X-Content-Type-Options`, `X-Frame-Options`, CSP, `Referrer-Policy`.
* **XSS:** saneamiento de salidas (`htmlspecialchars` / escape automático de Thymeleaf).
* **CSRF:** token sincronizador en todos los formularios.
* **Session fixation:** regeneración de ID al autenticar.

## Cómo ejecutar

### Base de datos PostgreSQL

Crear la base de datos:

```bash
psql -U postgres -c "CREATE DATABASE veterinaria_db;"
```

Ejecutar el script de creación de tablas:

```bash
psql -U postgres -d veterinaria_db -f php-veterinaria/sql/schema_postgres.sql
```

El script crea las tablas principales del sistema:

* `usuarios`
* `mascotas`

### PHP

```bash
cd php-veterinaria
composer install
```

Configurar las credenciales de PostgreSQL antes de iniciar el servidor:

```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="5432"
$env:DB_NAME="veterinaria_db"
$env:DB_USER="postgres"
$env:DB_PASS="TU_CLAVE_REAL"
php -S localhost:8080 -t public
```

Abrir:

```text
http://localhost:8080
```

Análisis estático:

```bash
composer phpstan
```

### Spring Boot

Antes de ejecutar Spring Boot, configurar las credenciales de PostgreSQL en:

```text
spring-veterinaria/src/main/resources/application.properties
```

Ejemplo de configuración:

```properties
server.port=8081
spring.datasource.url=jdbc:postgresql://localhost:5432/veterinaria_db
spring.datasource.username=postgres
spring.datasource.password=TU_CLAVE_REAL
```

Ejecutar el proyecto:

```bash
cd spring-veterinaria
mvn spring-boot:run
```

Abrir:

```text
http://localhost:8081
```

## Estructura

```text
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

* Johan Stalin Carvajal Loor
* Michael Xavier Fajardo Montes
* Jaime Josué Mariscal Cabrera
