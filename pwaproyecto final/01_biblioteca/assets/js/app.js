/**
 * app.js - Interacciones del lado del cliente.
 * 1) Auto-cierre de alertas
 * 2) Búsqueda en vivo de las tablas visibles
 */
document.addEventListener('DOMContentLoaded', function () {
  // Auto-cierre de alertas después de 4 segundos
  document.querySelectorAll('.alert').forEach(function (a) {
    setTimeout(function () { a.style.transition = 'opacity .4s'; a.style.opacity = 0; }, 4000);
  });

  // Confirmación antes de eliminar un registro
  document.querySelectorAll('[data-confirmar]').forEach(function (enlace) {
    enlace.addEventListener('click', function (ev) {
      if (!window.confirm('¿Eliminar este registro?')) { ev.preventDefault(); }
    });
  });

  // Buscador en vivo: filtra las filas de la primera tabla de la página
  var input = document.getElementById('buscador');
  if (input) {
    input.addEventListener('keyup', function () {
      var t = this.value.toLowerCase();
      document.querySelectorAll('table tbody tr').forEach(function (tr) {
        tr.style.display = tr.innerText.toLowerCase().indexOf(t) > -1 ? '' : 'none';
      });
    });
  }
});
