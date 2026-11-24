<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm mt-4">
      <div class="card-body p-4">
        <h4 class="mb-1">Iniciar sesión</h4>
        <p class="text-muted small">Accede con tu correo y contraseña.</p>
        <form method="post" action="index.php?p=login">
          <div class="mb-3">
            <label class="form-label">Correo</label>
            <input type="email" name="correo" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="contrasena" class="form-control" required>
          </div>
          <button class="btn btn-primary w-100">Entrar</button>
        </form>
        <hr>
        <a href="index.php?p=registro" class="small">¿No tienes cuenta? Regístrate</a>
      </div>
    </div>
  </div>
</div>
