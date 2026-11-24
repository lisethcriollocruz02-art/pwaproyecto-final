<?php
/**
 * config_modulos.php - Declaración de módulos y permisos por rol.
 * Cada módulo indica la tabla, los campos del formulario y qué roles pueden verlo.
 */
require_once __DIR__ . '/core/crud.php';

define('ROL_BASICO', 'Usuario');  // Rol asignado al auto-registrarse

$MODULOS = array(
    'libros' => array(
        'titulo' => 'Libros',
        'descripcion' => 'Gestión de libros.',
        'tabla' => 'libros',
        'slug' => 'libros',
        'roles' => array('Administrador', 'Bibliotecario'),
        'roles_crud' => array('Administrador'),
        'lista' => array('titulo', 'autor', 'categoria', 'esta_disponible'),
        'campos' => array(
            'titulo' => array('label' => 'Título', 'tipo' => 'text', 'requerido' => true),
            'autor' => array('label' => 'Autor', 'tipo' => 'text', 'requerido' => true),
            'categoria' => array('label' => 'Categoría', 'tipo' => 'text', 'requerido' => true),
            'esta_disponible' => array('label' => 'Disponible', 'tipo' => 'checkbox'),
        ),
    ),
    'prestamos' => array(
        'titulo' => 'Préstamos y devoluciones',
        'descripcion' => 'Gestión de préstamos y devoluciones.',
        'tabla' => 'prestamos',
        'slug' => 'prestamos',
        'roles' => array('Administrador', 'Bibliotecario', 'Usuario'),
        'roles_crud' => array('Administrador', 'Bibliotecario'),
        'lista' => array('id_usuario', 'id_libro', 'fecha_prestamo', 'fecha_devolucion', 'fecha_regreso'),
        'campos' => array(
            'id_usuario' => array('label' => 'ID usuario', 'tipo' => 'number', 'requerido' => true),
            'id_libro' => array('label' => 'ID libro', 'tipo' => 'number', 'requerido' => true),
            'fecha_prestamo' => array('label' => 'Fecha préstamo', 'tipo' => 'date', 'requerido' => true),
            'fecha_devolucion' => array('label' => 'Fecha devolución', 'tipo' => 'date', 'requerido' => true),
            'fecha_regreso' => array('label' => 'Fecha regreso', 'tipo' => 'date'),
        ),
    ),
);
