<?php
/**
 * vistas/perfil.php - Cambio de contraseña del usuario en sesión.
 */
$u = usuario();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $actual = $_POST['actual'] ?? '';
    $nueva  = $_POST['nueva'] ?? '';
    $reg = q1('SELECT * FROM usuarios WHERE id = ?', [$u['id']]);
    $ok = ($actual === $reg['contrasena']) || password_verify($actual, $reg['contrasena']);
    if (!$ok) {
        $msg = '<div class="alert alert-danger">La contraseña actual no es correcta.</div>';
    } elseif (strlen($nueva) < 4) {
        $msg = '<div class="alert alert-warning">La nueva contraseña debe tener al menos 4 caracteres.</div>';
    } else {
        exec_sql('UPDATE usuarios SET contrasena = ? WHERE id = ?',
                 [password_hash($nueva, PASSWORD_DEFAULT), $u['id']]);
        $msg = '<div class="alert alert-success">Contraseña actualizada correctamente.</div>';
    }
}
?>
<div class="row justify-content-center"><div class="col-md-6">
  <div class="card shadow-sm"><div class="card-body p-4">
    <h4 class="mb-3">Mi perfil</h4>
    <p class="text-muted">Nombre: <strong><?= e($u['nombre']) ?></strong><br>
       Correo: <?= e($u['correo']) ?><br>Rol: <?= e($u['rol']) ?></p>
    <?= $msg ?>
    <hr><h6>Cambiar contraseña</h6>
    <form method="post">
      <div class="mb-3"><label class="form-label">Contraseña actual</label>
        <input type="password" name="actual" class="form-control" required></div>
      <div class="mb-3"><label class="form-label">Nueva contraseña</label>
        <input type="password" name="nueva" class="form-control" required></div>
      <button class="btn btn-primary">Actualizar</button>
    </form>
  </div></div>
</div></div>
