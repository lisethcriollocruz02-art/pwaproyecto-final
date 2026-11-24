-- ===========================================================
-- Base de datos: Sistema de Administración de una Biblioteca
-- Programación Web Avanzada - Proyecto final
-- Importar desde phpMyAdmin (pestaña Importar)
-- ===========================================================

CREATE DATABASE IF NOT EXISTS `biblioteca` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca`;

DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS `libros`;
DROP TABLE IF EXISTS `prestamos`;

-- Tabla de roles
CREATE TABLE roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(30) NOT NULL
);
INSERT INTO roles (nombre) VALUES ('Administrador', 'Bibliotecario', 'Usuario');

-- Tabla de usuarios del sistema
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  correo VARCHAR(100) UNIQUE NOT NULL,
  contrasena VARCHAR(255) NOT NULL,
  id_rol INT NOT NULL,
  FOREIGN KEY (id_rol) REFERENCES roles(id)
);

-- Usuarios de prueba (un usuario por rol)
INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES ('Administrador', 'admin@biblioteca.com', 'admin123', 1);
INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES ('Bibliotecario', 'bibliotecario@biblioteca.com', 'biblio123', 2);
INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES ('María Lopez', 'usuario@biblioteca.com', 'user123', 3);

-- Tabla: libros
CREATE TABLE `libros` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255) NOT NULL,
  autor VARCHAR(255) NOT NULL,
  categoria VARCHAR(255) NOT NULL,
  esta_disponible BOOLEAN NOT NULL DEFAULT FALSE,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla: prestamos
CREATE TABLE `prestamos` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario DECIMAL(12,2) NOT NULL,
  id_libro DECIMAL(12,2) NOT NULL,
  fecha_prestamo DATE NOT NULL,
  fecha_devolucion DATE NOT NULL,
  fecha_regreso DATE NULL,
  creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Datos de ejemplo (3 registros por tabla para probar el CRUD)
INSERT INTO `libros` (`titulo`, `autor`, `categoria`, `esta_disponible`) VALUES ('Cien años de soledad', 'Gabriel García Márquez', 'General', 'TRUE'), ('El principito', 'Antoine de Saint-Exupéry', 'Premium', 'FALSE'), ('Don Quijote de la Mancha', 'Miguel de Cervantes', 'Ocasional', 'TRUE');

INSERT INTO `prestamos` (`id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion`, `fecha_regreso`) VALUES (1, 1, '2026-01-15', '2026-01-15', '2026-01-15'), (2, 2, '2026-02-20', '2026-02-20', '2026-02-20'), (3, 3, '2026-03-10', '2026-03-10', '2026-03-10');

