<?php
/**
 * index.php - Punto de entrada. Enruta las peticiones y dibuja el layout.
 */
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/config_modulos.php';

$p = $_GET['p'] ?? 'login';

// Rutas especiales
if ($p === 'logout') { logout(); }

// Procesar login
$error = '';
if ($p === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (login($_POST['correo'] ?? '', $_POST['contrasena'] ?? '')) {
        header('Location: index.php?p=inicio');
        exit;
    }
    $error = 'Correo o contraseña incorrectos.';
}

// Aviso de usuario creado
$aviso = ($p === 'login' && isset($_GET['ok'])) ? '<div class="alert alert-success">Cuenta creada. Ya puedes iniciar sesión.</div>' : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e(APP_NOMBRE) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/estilos.css" rel="stylesheet">
</head>
<body>

<?php if (usuario()): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php?p=inicio"><?= e(APP_NOMBRE) ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav me-auto">
        <?php foreach ($MODULOS as $slug => $m): ?>
          <?php if (in_array(usuario()['rol'], $m['roles'], true)): ?>
            <li class="nav-item"><a class="nav-link" href="index.php?p=<?= e($slug) ?>"><?= e($m['titulo']) ?></a></li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
      <a class="nav-link text-light me-3" href="index.php?p=perfil">Mi perfil</a>
      <span class="navbar-text text-light me-3">
        <?= e(usuario()['nombre']) ?> <span class="badge bg-info text-dark"><?= e(usuario()['rol']) ?></span>
      </span>
      <a class="btn btn-outline-light btn-sm" href="index.php?p=logout">Salir</a>
    </div>
  </div>
</nav>
<?php endif; ?>

<main class="container py-4">
<?php
if ($p === 'login' && $error !== '') {
    echo '<div class="alert alert-danger">' . e($error) . '</div>';
}

if ($p === 'login') {
    include __DIR__ . '/vistas/login.php';
} elseif ($p === 'registro') {
    include __DIR__ . '/vistas/registro.php';
} elseif ($p === 'perfil') {
    requiere_login();
    include __DIR__ . '/vistas/perfil.php';
} elseif ($p === 'inicio') {
    requiere_login();
    include __DIR__ . '/vistas/inicio.php';
} elseif (isset($MODULOS[$p])) {
    $m = $MODULOS[$p];
    requiere_rol($m['roles']);
    if (!empty($m['archivo'])) {
        include __DIR__ . '/modulos/' . $m['archivo'];
    } else {
        render_crud($m);
    }
} else {
    echo '<div class="alert alert-warning">Página no encontrada.</div>';
}
?>
</main>

<footer class="text-center text-muted py-3 small border-top mt-4">
  <?= e(APP_NOMBRE) ?> &middot; Programación Web Avanzada &middot; <?= date('Y') ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
