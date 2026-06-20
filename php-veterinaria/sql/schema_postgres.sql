-- ============================================================
--  Base de datos: veterinaria_db  (PostgreSQL)
--  Proyecto Fin de Curso - Clínica Veterinaria
--  Aplicaciones Web [111] - UTEQ
-- ============================================================
--
--  IMPORTANTE: en PostgreSQL la base de datos se crea por separado.
--  Primero, desde la terminal psql (conectado como el usuario postgres):
--
--      CREATE DATABASE veterinaria_db;
--
--  Luego conéctate a esa base y ejecuta este script:
--
--      \c veterinaria_db
--      \i schema_postgres.sql
--
--  O en un solo comando desde la terminal del sistema:
--      psql -U postgres -d veterinaria_db -f schema_postgres.sql
-- ============================================================

-- ------------------------------------------------------------
--  Tabla: usuarios
--  La contraseña se guarda SIEMPRE como hash (nunca en texto plano).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id            SERIAL PRIMARY KEY,
    nombre        VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password_hash VARCHAR(255)  NOT NULL,
    rol           VARCHAR(10)   NOT NULL DEFAULT 'VET'
                  CHECK (rol IN ('ADMIN', 'VET')),
    creado_en     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
--  Tabla: mascotas  (entidad CRUD del PFC)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mascotas (
    id            SERIAL PRIMARY KEY,
    nombre        VARCHAR(100)  NOT NULL,
    especie       VARCHAR(50)   NOT NULL,
    raza          VARCHAR(80)            DEFAULT NULL,
    edad          SMALLINT               DEFAULT NULL,
    nombre_dueno  VARCHAR(120)  NOT NULL,
    telefono      VARCHAR(20)            DEFAULT NULL,
    creado_por    INTEGER                DEFAULT NULL,
    creado_en     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mascota_usuario
        FOREIGN KEY (creado_por) REFERENCES usuarios(id)
        ON DELETE SET NULL
);

-- Índice para búsquedas por especie (consulta frecuente del PFC)
CREATE INDEX IF NOT EXISTS idx_mascota_especie ON mascotas(especie);

-- ------------------------------------------------------------
--  Datos de ejemplo
-- ------------------------------------------------------------
INSERT INTO mascotas (nombre, especie, raza, edad, nombre_dueno, telefono) VALUES
('Firulais', 'Perro', 'Labrador',      4, 'María Pérez', '0991234567'),
('Michi',    'Gato',  'Siamés',        2, 'Juan Gómez',  '0987654321'),
('Rocky',    'Perro', 'Pastor Alemán', 6, 'Ana Torres',  '0961122334');
