-- ============================================================
--  Base de datos: veterinaria_db
--  Proyecto Fin de Curso - Clínica Veterinaria
--  Aplicaciones Web [111] - UTEQ
-- ============================================================

CREATE DATABASE IF NOT EXISTS veterinaria_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE veterinaria_db;

-- ------------------------------------------------------------
--  Tabla: usuarios
--  Almacena credenciales. La contraseña se guarda SIEMPRE
--  como hash Argon2id (nunca en texto plano).
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS usuarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100)        NOT NULL,
    email         VARCHAR(150)        NOT NULL UNIQUE,
    password_hash VARCHAR(255)        NOT NULL,
    rol           ENUM('ADMIN','VET') NOT NULL DEFAULT 'VET',
    creado_en     TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
--  Tabla: mascotas  (entidad CRUD del PFC)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS mascotas (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre        VARCHAR(100)  NOT NULL,
    especie       VARCHAR(50)   NOT NULL,
    raza          VARCHAR(80)            DEFAULT NULL,
    edad          TINYINT UNSIGNED       DEFAULT NULL,
    nombre_dueno  VARCHAR(120)  NOT NULL,
    telefono      VARCHAR(20)            DEFAULT NULL,
    creado_por    INT UNSIGNED           DEFAULT NULL,
    creado_en     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_mascota_usuario
        FOREIGN KEY (creado_por) REFERENCES usuarios(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- Índice para búsquedas por especie (consulta frecuente del PFC)
CREATE INDEX idx_mascota_especie ON mascotas(especie);

-- ------------------------------------------------------------
--  Datos de ejemplo
--  Usuario admin@vet.ec  /  contraseña: Admin123*
--  (el hash se regenera al registrar; este es de ejemplo)
-- ------------------------------------------------------------
INSERT INTO mascotas (nombre, especie, raza, edad, nombre_dueno, telefono) VALUES
('Firulais', 'Perro', 'Labrador',        4, 'María Pérez',  '0991234567'),
('Michi',    'Gato',  'Siamés',          2, 'Juan Gómez',   '0987654321'),
('Rocky',    'Perro', 'Pastor Alemán',   6, 'Ana Torres',   '0961122334');
