# 01_biblioteca

Sistema web de **administración de biblioteca** desarrollado como proyecto final de **Programación Web Avanzada**.

## Tecnologías

- HTML5 + **Bootstrap 5** (interfaz responsiva)
- **CSS** propio (`assets/css/estilos.css`)
- **JavaScript** (`assets/js/app.js`): filtro de búsqueda en vivo y auto-cierre de alertas
- **PHP** (PDO con consultas preparadas, sin frameworks PHP)
- **MySQL / MariaDB** (administrable con phpMyAdmin)

## Estructura del proyecto

```
01_biblioteca/
├── biblioteca.sql
├── README.md
├── index.php                 Enrutador principal y layout
├── config.php                Conexión a la base de datos
├── config_modulos.php        Definición de módulos y roles
├── core/
│   ├── db.php                Conexión PDO y utilidades
│   ├── auth.php              Sesiones, login y control de roles
│   └── crud.php              Generador genérico de listar/crear/editar/eliminar
├── modulos/                  Un archivo por módulo con lógica adicional
├── vistas/                   Login, registro, inicio y perfil
└── assets/
    ├── css/estilos.css
    └── js/app.js
```

## Instalación local (Laragon / XAMPP / WAMP)

1. Copia la carpeta del proyecto en `C:/laragon/www/` o `htdocs/`.
2. Abre phpMyAdmin y crea la base de datos **`biblioteca`**.
3. Importa el archivo **`biblioteca.sql`** (pestaña *Importar*).
4. Ajusta en `config.php` el usuario y la contraseña de MySQL.
5. Abre `http://localhost/01_biblioteca/`.

## Usuarios de prueba

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Administrador | admin@biblioteca.com | admin123 |
| Bibliotecario | bibliotecario@biblioteca.com | biblio123 |
| Usuario | usuario@biblioteca.com | user123 |

## Roles y permisos

- **Administrador**: Libros (CRUD); Préstamos y devoluciones (CRUD).
- **Bibliotecario**: Libros (solo lectura); Préstamos y devoluciones (CRUD).
- **Usuario**: Préstamos y devoluciones (solo lectura).

## Publicación

- **GitHub:** sube esta carpeta a un repositorio llamado `pwapractica5`.
  La base de datos `biblioteca.sql` debe quedar en la raíz del repositorio.
- **Hospedaje gratuito:** sube por FTP los archivos a `htdocs` del hosting
  (000webhost, InfinityFree, etc.), importa la base de datos desde su phpMyAdmin
  y actualiza `config.php` con los datos del servidor.
