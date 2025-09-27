-- create_tables.sql
-- Script para crear las tablas necesarias: `users` y `contactos`
-- Ajusta el nombre de la base de datos si es necesario antes de ejecutar.

-- Asegúrate de usar un charset moderno
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Tabla de administradores/usuarios
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  'user' VARCHAR(32) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de contactos (se adapta al uso en index.php y admin.php)
CREATE TABLE IF NOT EXISTS `contactos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `contrasena` VARCHAR(255) DEFAULT NULL,
  `telefono` VARCHAR(50) DEFAULT NULL,
  `servicio` VARCHAR(100) DEFAULT NULL,
  `mensaje` TEXT DEFAULT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ejemplo: insertar un admin (reemplaza HASH_AQUI por el resultado de password_hash)
-- INSERT INTO users (username, password) VALUES ('admin', 'HASH_AQUI');

-- Ejemplo: insertar contacto de prueba con contraseña (inserta el hash generado con password_hash)
-- INSERT INTO contactos (nombre, email, contrasena, telefono, servicio, mensaje) VALUES ('Prueba', 'prueba@example.com', 'HASH_DE_CONTRASENA', '+5712345678', 'medicina-estetica', 'Mensaje de prueba');

SET FOREIGN_KEY_CHECKS = 1;
