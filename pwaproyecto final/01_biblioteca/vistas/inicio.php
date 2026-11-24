<?php
/**
 * vistas/inicio.php - Panel de bienvenida con indicadores del rol.
 */
$u = usuario();
?>
<div class="p-4 mb-4 rounded-3 bg-primary text-white shadow-sm">
  <h3 class="mb-1">Hola, <?= e($u['nombre']) ?></h3>
  <p class="mb-0">Has iniciado sesión como <strong><?= e($u['rol']) ?></strong>.
     Usa el menú superior para acceder a los módulos permitidos.</p>
</div>

<div class="row g-3">
  <?php foreach ($MODULOS as $slug => $m): ?>
    <?php if (!in_array($u['rol'], $m['roles'], true)) continue; ?>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h6 class="card-title"><?= e($m['titulo']) ?></h6>
            <p class="card-text small text-muted"><?= e($m['descripcion'] ?? 'Gestión de registros del sistema.') ?></p>
            <a class="btn btn-sm btn-outline-primary" href="index.php?p=<?= e($slug) ?>">Abrir</a>
          </div>
        </div>
      </div>
  <?php endforeach; ?>
</div>
