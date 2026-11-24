<?php
/**
 * auth.php - Sesiones, autenticación y control de roles.
 */
require_once __DIR__ . '/db.php';

function iniciar_sesion() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}

/**
 * Intenta iniciar sesión con correo y contraseña.
 * Devuelve true si los datos son correctos.
 */
function login($correo, $contrasena) {
    iniciar_sesion();
    $u = q1('SELECT * FROM usuarios WHERE correo = ?', [$correo]);
    if (!$u) return false;

    // Base de datos de práctica: acepta texto plano o hash generado con password_hash()
    $ok = ($contrasena === $u['contrasena']) || password_verify($contrasena, $u['contrasena']);
    if (!$ok) return false;

    // Si la contraseña estaba en texto plano (datos demo del .sql), se guarda ya cifrada
    if (strlen($u['contrasena']) < 60) {
        exec_sql('UPDATE usuarios SET contrasena = ? WHERE id = ?',
                 [password_hash($contrasena, PASSWORD_DEFAULT), $u['id']]);
    }

    session_regenerate_id(true);  // evita fijación de sesión
    $_SESSION['usuario'] = [
        'id'     => $u['id'],
        'nombre' => $u['nombre'],
        'correo' => $u['correo'],
        'id_rol' => $u['id_rol'],
        'rol'    => rol_nombre($u['id_rol'])
    ];
    return true;
}

function logout() {
    iniciar_sesion();
    session_destroy();
    header('Location: index.php');
    exit;
}

function usuario() {
    iniciar_sesion();
    return $_SESSION['usuario'] ?? null;
}

/** Exige sesión activa; si no la hay redirige al login */
function requiere_login() {
    if (!usuario()) {
        header('Location: index.php?p=login');
        exit;
    }
}

/** Exige que el usuario tenga uno de los roles indicados */
function requiere_rol($roles) {
    requiere_login();
    $u = usuario();
    $roles = is_array($roles) ? $roles : [$roles];
    if (!in_array($u['rol'], $roles, true)) {
        http_response_code(403);
        echo '<div class="alert alert-danger m-4">No tienes permisos para acceder a esta sección.</div>';
        echo '<p class="m-4"><a class="btn btn-secondary btn-sm" href="index.php?p=inicio">Volver al inicio</a></p>';
        exit;
    }
}

/** Nombre del rol a partir de su id (cacheado) */
function rol_nombre($id_rol) {
    static $cache = [];
    if (!isset($cache[$id_rol])) {
        $r = q1('SELECT nombre FROM roles WHERE id = ?', [$id_rol]);
        $cache[$id_rol] = $r ? $r['nombre'] : 'Usuario';
    }
    return $cache[$id_rol];
}
?>
