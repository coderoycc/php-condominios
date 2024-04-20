// Call the dataTables jQuery plugin
$(document).ready(function() {
  const lenguaje = {
    processing: 'Procesando...',
    search: 'Buscar en la tabla',
    lengthMenu: "Mostrar _MENU_ registros",
    infoFiltered: "(filtrado de un total de _MAX_ registros)",
    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
    infoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
    paginate: {
      first: 'Primero',
      previous: 'Ant.',
      next: 'Sig.',
      last: 'Último'
    },
    emptyTable: 'No hay registros...',
    infoEmpty: 'No hay resultados',
    zeroRecords: 'No hay registros...',
  }
  $('#dataTable').DataTable({language: lenguaje,
    scrollX: true,
    autoWidth: false,
    scrollY: '50vh',
    ordering: false});
});
