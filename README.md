# 🐾 Clínica Veterinaria — Backend Comparativo

**Práctica Experimental Unidad II · Aplicaciones Web · UTEQ**

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php&logoColor=white)
![Java](https://img.shields.io/badge/Java-21-ED8B00?logo=openjdk&logoColor=white)
![Spring Boot](https://img.shields.io/badge/Spring%20Boot-3.3-6DB33F?logo=springboot&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-database-4169E1?logo=postgresql&logoColor=white)
![OWASP](https://img.shields.io/badge/OWASP-Top%2010-000000?logo=owasp&logoColor=white)
![License](https://img.shields.io/badge/license-académico-lightgrey)

Backend de una clínica veterinaria implementado en **dos tecnologías de servidor**, con autenticación segura, CRUD de mascotas y controles de seguridad OWASP. 🩺🐶

---

## 📑 Contenido

- [✨ Características](#-características)
- [⚙️ Tecnologías](#️-tecnologías)
- [📋 Requisitos previos](#-requisitos-previos)
- [🐶 Entidad del CRUD](#-entidad-del-crud)
- [🔒 Controles de seguridad OWASP](#-controles-de-seguridad-owasp-implementados)
- [🚀 Cómo ejecutar](#-cómo-ejecutar)
  - [1️⃣ Base de datos PostgreSQL](#1️⃣-base-de-datos-postgresql)
  - [2️⃣ PHP](#2️⃣-php)
  - [3️⃣ Spring Boot](#3️⃣-spring-boot)
- [📂 Estructura del proyecto](#-estructura-del-proyecto)
- [🧭 Decisiones de arquitectura](#-decisiones-de-arquitectura)
- [👥 Equipo](#-equipo)

---

## ✨ Características

| | |
|---|---|
| 🔐 | Autenticación segura con hashing moderno (Argon2id / BCrypt) |
| 🐾 | CRUD completo de mascotas |
| 🛡️ | Controles OWASP Top 10 implementados en ambas versiones |
| ⚖️ | Dos stacks equivalentes para comparar arquitecturas |
| 🗃️ | Misma base de datos (PostgreSQL) en ambas implementaciones |

---

## ⚙️ Tecnologías

| | Tecnología 1 | Tecnología 2 |
|---|---|---|
| **Lenguaje** | PHP 8.2 | Java 21 |
| **Framework** | Vanilla + PDO | Spring Boot 3.3 |
| **Base de datos** | PostgreSQL | PostgreSQL |
| **Acceso a datos** | PDO (prepared statements) | JdbcTemplate |
| **Plantillas** | PHP nativo | Thymeleaf |
| **Seguridad** | Manual (Argon2id, CSRF) | Spring Security (BCrypt) |
| **Puerto** | `8080` | `8081` |

## 📋 Requisitos previos

| Herramienta | Uso |
|---|---|
| 🐘 PostgreSQL | Base de datos compartida por ambas implementaciones |
| 🐬 PHP 8.2 + Composer | Implementación PHP |
| ☕ Java 21 + Maven | Implementación Spring Boot |
| 🔧 psql (cliente CLI) | Crear la base de datos y ejecutar el script SQL |

## 🐶 Entidad del CRUD

**`Mascota`** — entidad central de la clínica veterinaria.

Atributos: `nombre`, `especie`, `raza`, `edad`, `dueño`, `teléfono`.

## 🔒 Controles de seguridad OWASP implementados

| Riesgo OWASP | Mitigación |
|---|---|
| **A01 — Control de acceso** | Rutas protegidas (guard en PHP / `authorizeHttpRequests` en Spring) |
| **A02 / A07 — Criptografía y autenticación** | Argon2id (PHP) y BCrypt costo 12 (Spring) |
| **A03 — Inyección** | 100% prepared statements, sin concatenación de SQL |
| **A05 — Mala configuración** | Cabeceras `X-Content-Type-Options`, `X-Frame-Options`, CSP, `Referrer-Policy` |
| **XSS** | Saneamiento de salidas (`htmlspecialchars` / escape automático de Thymeleaf) |
| **CSRF** | Token sincronizador en todos los formularios |
| **Session fixation** | Regeneración de ID al autenticar |

## 🚀 Cómo ejecutar

### 1️⃣ Base de datos PostgreSQL

Crear la base de datos:

\```bash
psql -U postgres -c "CREATE DATABASE veterinaria_db;"
\```

Ejecutar el script de creación de tablas:

\```bash
psql -U postgres -d veterinaria_db -f php-veterinaria/sql/schema_postgres.sql
\```

El script crea las tablas principales del sistema:

- `usuarios`
- `mascotas`

### 2️⃣ PHP

Instalar dependencias:

\```bash
cd php-veterinaria
composer install
\```

Configurar las credenciales de PostgreSQL antes de iniciar el servidor:

\```powershell
$env:DB_HOST="127.0.0.1"
$env:DB_PORT="5432"
$env:DB_NAME="veterinaria_db"
$env:DB_USER="postgres"
$env:DB_PASS="TU_CLAVE_REAL"
php -S localhost:8080 -t public
\```

Abrir: [http://localhost:8080](http://localhost:8080)

Análisis estático:

\```bash
composer phpstan
\```

### 3️⃣ Spring Boot

Configurar las credenciales de PostgreSQL en:

\```text
spring-veterinaria/src/main/resources/application.properties
\```

Ejemplo de configuración:

\```properties
server.port=8081
spring.datasource.url=jdbc:postgresql://localhost:5432/veterinaria_db
spring.datasource.username=postgres
spring.datasource.password=TU_CLAVE_REAL
\```

Ejecutar el proyecto:

\```bash
cd spring-veterinaria
mvn spring-boot:run
\```

Abrir: [http://localhost:8081](http://localhost:8081)

## 📂 Estructura del proyecto

\```text
.
├── php-veterinaria/        # Implementación PHP 8.2
├── spring-veterinaria/     # Implementación Spring Boot 3
└── docs/
    └── adr/                # Architecture Decision Records
        ├── ADR-001-tecnologia-backend.md
        └── ADR-002-estrategia-bd.md
\```

## 🧭 Decisiones de arquitectura

- [ADR-001](docs/adr/ADR-001-tecnologia-backend.md) — Elección de tecnología
- [ADR-002](docs/adr/ADR-002-estrategia-bd.md) — Estrategia de base de datos

## 👥 Equipo

- Johan Stalin Carvajal Loor
- Michael Xavier Fajardo Montes
- Jaime Josué Mariscal Cabrera
