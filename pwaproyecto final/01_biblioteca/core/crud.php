<?php
/**
 * crud.php - Generador genérico de operaciones CRUD.
 *
 * Permite listar, crear, editar y eliminar registros de cualquier tabla
 * declarada en el módulo, sin repetir código en cada archivo.
 *
 * Configuración esperada en $CFG:
 *   tabla      -> nombre de la tabla en MySQL
 *   titulo     -> título visible del módulo
 *   campos     -> [ 'columna' => ['label'=>.., 'tipo'=>text|number|date|datetime|time|select|textarea|checkbox, 'opciones'=>[]] ]
 *   roles      -> roles que pueden ver el módulo
 *   roles_crud -> roles que pueden crear/editar/eliminar
 *   lista      -> columnas que se muestran en la tabla
 */
require_once __DIR__ . '/auth.php';

function campo_input($nombre, $def, $valor = '') {
    $tipo  = $def['tipo'] ?? 'text';
    $req   = !empty($def['requerido']) ? 'required' : '';
    $label = $def['label'] ?? $nombre;
    $html  = '<div class="col-md-6 mb-3"><label class="form-label">' . e($label) . '</label>';

    switch ($tipo) {
        case 'textarea':
            $html .= '<textarea class="form-control" name="' . $nombre . '" rows="3" ' . $req . '>' . e($valor) . '</textarea>';
            break;
        case 'select':
            $html .= '<select class="form-select" name="' . $nombre . '" ' . $req . '>';
            foreach (($def['opciones'] ?? []) as $k => $v) {
                $sel = ((string)$k === (string)$valor) ? 'selected' : '';
                $html .= '<option value="' . e($k) . '" ' . $sel . '>' . e($v) . '</option>';
            }
            $html .= '</select>';
            break;
        case 'checkbox':
            $chk = ($valor == 1) ? 'checked' : '';
            return '<div class="col-md-6 mb-3"><div class="form-check mt-4">'
                 . '<input class="form-check-input" type="checkbox" name="' . $nombre . '" value="1" ' . $chk . '>'
                 . '<label class="form-check-label">' . e($label) . '</label></div></div>';
        default:
            $html .= '<input type="' . $tipo . '" class="form-control" name="' . $nombre . '" value="' . e($valor) . '" ' . $req . '>';
    }
    return $html . '</div>';
}

/** Renderiza la página del módulo: listado, formulario y acciones */
function render_crud($CFG) {
    requiere_login();
    $u = usuario();
    $tabla = $CFG['tabla'];
    $puede_crud = in_array($u['rol'], $CFG['roles_crud'] ?? [], true);
    $accion = $_GET['accion'] ?? 'listar';
    $id     = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $msg = '';

    // -------- Eliminar --------
    if ($accion === 'eliminar' && $puede_crud && $id > 0) {
        try {
            exec_sql('DELETE FROM ' . $tabla . ' WHERE id = ?', [$id]);
            $msg = '<div class="alert alert-success">Registro eliminado correctamente.</div>';
        } catch (PDOException $ex) {
            $msg = '<div class="alert alert-warning">No se pudo eliminar: el registro tiene datos relacionados.</div>';
        }
        $accion = 'listar';
    }

    // -------- Guardar (crear o editar) --------
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $puede_crud) {
        $cols = [];
        $vals = [];
        foreach ($CFG['campos'] as $col => $def) {
            if (!empty($def['auto'])) continue;
            $cols[] = $col;
            $v = $_POST[$col] ?? (($def['tipo'] ?? '') === 'checkbox' ? 0 : '');
            if (($def['tipo'] ?? '') === 'checkbox') $v = isset($_POST[$col]) ? 1 : 0;
            $vals[] = $v;
        }
        try {
            if ($id > 0) {
                $set = implode(', ', array_map(fn($c) => "$c = ?", $cols));
                $vals[] = $id;
                exec_sql("UPDATE $tabla SET $set WHERE id = ?", $vals);
                $msg = '<div class="alert alert-success">Registro actualizado.</div>';
            } else {
                $ph = implode(', ', array_fill(0, count($cols), '?'));
                exec_sql("INSERT INTO $tabla (" . implode(',', $cols) . ") VALUES ($ph)", $vals);
                $msg = '<div class="alert alert-success">Registro creado.</div>';
            }
            $accion = 'listar';
        } catch (PDOException $ex) {
            $msg = '<div class="alert alert-danger">Error al guardar: ' . e($ex->getMessage()) . '</div>';
        }
    }

    echo '<div class="card shadow-sm mb-4"><div class="card-body">';
    echo '<div class="d-flex justify-content-between align-items-center mb-3">';
    echo '<h4 class="mb-0">' . e($CFG['titulo']) . '</h4>';
    if ($puede_crud && $accion === 'listar') {
        echo '<a class="btn btn-primary btn-sm" href="?p=' . e($CFG['slug']) . '&accion=nuevo">+ Nuevo</a>';
    }
    echo '</div>' . $msg;

    // -------- Formulario --------
    if ($accion === 'nuevo' || $accion === 'editar') {
        $reg = $id > 0 ? q1("SELECT * FROM $tabla WHERE id = ?", [$id]) : [];
        echo '<form method="post" action="?p=' . e($CFG['slug']) . '&accion=' . ($id > 0 ? 'editar&id=' . $id : 'nuevo') . '"><div class="row">';
        foreach ($CFG['campos'] as $col => $def) {
            if (!empty($def['auto'])) continue;
            echo campo_input($col, $def, $reg[$col] ?? ($def['default'] ?? ''));
        }
        echo '</div><button class="btn btn-success">Guardar</button> ';
        echo '<a class="btn btn-secondary" href="?p=' . e($CFG['slug']) . '">Cancelar</a></form>';
        echo '</div></div>';
        return;
    }

    // -------- Listado --------
    $cols = $CFG['lista'] ?? array_slice(array_keys($CFG['campos']), 0, 5);
    $filas = q("SELECT * FROM $tabla ORDER BY id DESC");
    echo '<input id="buscador" class="form-control mb-3" placeholder="Buscar en la tabla...">';
    echo '<div class="table-responsive"><table class="table table-striped table-hover align-middle">';
    echo '<thead class="table-dark"><tr><th>#</th>';
    foreach ($cols as $c) echo '<th>' . e($CFG['campos'][$c]['label'] ?? $c) . '</th>';
    if ($puede_crud) echo '<th class="text-end">Acciones</th>';
    echo '</tr></thead><tbody>';
    if (!$filas) {
        echo '<tr><td colspan="' . (count($cols) + 2) . '" class="text-center text-muted py-4">No hay registros todavía.</td></tr>';
    }
    foreach ($filas as $f) {
        echo '<tr><td>' . (int)$f['id'] . '</td>';
        foreach ($cols as $c) {
            $v = $f[$c] ?? '';
            $def = $CFG['campos'][$c] ?? [];
            if (($def['tipo'] ?? '') === 'checkbox') {
                $v = $v ? '<span class="badge bg-success">Sí</span>' : '<span class="badge bg-secondary">No</span>';
            } elseif (($def['tipo'] ?? '') === 'select' && isset($def['opciones'][$v])) {
                $v = e($def['opciones'][$v]);
            } else {
                $v = e($v);
            }
            echo '<td>' . $v . '</td>';
        }
        if ($puede_crud) {
            echo '<td class="text-end"><a class="btn btn-sm btn-outline-primary" href="?p=' . e($CFG['slug']) . '&accion=editar&id=' . (int)$f['id'] . '">Editar</a> '
               . '<a class="btn btn-sm btn-outline-danger" href="?p=' . e($CFG['slug']) . '&accion=eliminar&id=' . (int)$f['id']
               . '" data-confirmar="1">Eliminar</a></td>';
        }
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    echo '<p class="text-muted small mb-0">Total: ' . count($filas) . ' registro(s).</p>';
    echo '</div></div>';
}
?>
