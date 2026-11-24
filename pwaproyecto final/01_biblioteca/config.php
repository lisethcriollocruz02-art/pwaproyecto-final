<?php
/**
 * config.php - Configuración central del sistema.
 * Ajusta los datos de conexión según tu servidor (Laragon/XAMPP/hospedaje).
 */

// Datos de conexión a MySQL / MariaDB
define('DB_HOST', 'localhost');
define('DB_NAME', 'biblioteca');
define('DB_USER', 'root');
define('DB_PASS', '');

// Nombre visible del sistema (aparece en la barra de navegación)
define('APP_NOMBRE', 'Sistema de Administración de una Biblioteca');

// Zona horaria
date_default_timezone_set('America/Guayaquil');

// Errores visibles en desarrollo; coméntalos al publicar en producción
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
