# ADR-002: Estrategia de base de datos y acceso a datos

- **Estado:** Aceptada
- **Fecha:** 2026-05-15
- **Decisores:** Equipo de desarrollo — PFC Clínica Veterinaria

## Contexto

Ambas implementaciones del backend (PHP y Spring Boot) necesitan persistir
usuarios y mascotas. La guía exige que **ninguna consulta** construya SQL por
concatenación de strings con datos del usuario, para prevenir inyección SQL
(OWASP A03).

Había que decidir el motor de base de datos y el mecanismo de acceso a datos
en cada tecnología, manteniendo equivalencia funcional para que la comparación
sea justa.

## Decisión

Motor: se adopta PostgreSQL como única base de datos, compartida por ambas implementaciones mediante el mismo esquema (`schema_postgres.sql`).

Razones:

- PostgreSQL permite trabajar con una base de datos relacional robusta y compatible con ambas implementaciones del proyecto.
- Un esquema único garantiza que PHP y Spring Boot operen sobre datos equivalentes
- La práctica se ejecutó localmente con PostgreSQL, manteniendo la misma base `veterinaria_db` para las dos tecnologías.

**Mecanismo de acceso a datos:**

- En **PHP**: PDO con prepared statements y `ATTR_EMULATE_PREPARES = false`,
  organizado con el **patrón Repository** (interfaz + implementación concreta)
  para desacoplar la lógica de negocio del acceso a datos.
- En **Spring Boot**: `JdbcTemplate` con parámetros posicionales `?`, que
  internamente usa `PreparedStatement`. Se eligió JdbcTemplate sobre JPA/Hibernate
  para mantener un control explícito del SQL y un paralelismo directo con el
  enfoque de PDO, facilitando la comparación técnica.

## Consecuencias

**Positivas:**
- Inmunidad a inyección SQL por diseño en ambas tecnologías.
- El patrón Repository permite sustituir la fuente de datos o crear mocks para
  pruebas sin tocar la lógica de negocio (favorece SOLID/DIP).
- Equivalencia funcional real entre ambas implementaciones.

**Negativas / Trade-offs:**
- JdbcTemplate implica escribir el SQL a mano (más verboso que JPA), pero da
  transparencia sobre las consultas ejecutadas.
- El mapeo fila→objeto es manual en ambos lados (RowMapper / fromArray).
