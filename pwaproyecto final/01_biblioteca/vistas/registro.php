<?php
/**
 * vistas/registro.php - Registro de nuevos usuarios (rol Usuario/Cliente por defecto).
 */
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $pass   = $_POST['contrasena'] ?? '';
    if ($nombre && $correo && strlen($pass) >= 4) {
        try {
            // Id del rol básico asignado al auto-registro
            $rol = q1('SELECT id FROM roles WHERE nombre = ?', [ROL_BASICO]);
            exec_sql('INSERT INTO usuarios (nombre, correo, contrasena, id_rol) VALUES (?,?,?,?)',
                     [$nombre, $correo, password_hash($pass, PASSWORD_DEFAULT), $rol['id']]);
            header('Location: index.php?p=login&ok=1');
            exit;
        } catch (PDOException $ex) {
            $msg = '<div class="alert alert-danger">El correo ya está registrado.</div>';
        }
    } else {
        $msg = '<div class="alert alert-warning">Complete todos los campos (contraseña de 4+ caracteres).</div>';
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm mt-4"><div class="card-body p-4">
      <h4 class="mb-3">Crear cuenta</h4>
      <?= $msg ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Correo</label>
          <input type="email" name="correo" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Contraseña</label>
          <input type="password" name="contrasena" class="form-control" required></div>
        <button class="btn btn-success w-100">Registrarme</button>
      </form>
      <hr><a href="index.php?p=login" class="small">Volver al inicio de sesión</a>
    </div></div>
  </div>
</div>
