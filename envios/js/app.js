var tabla = null;
$(document).ready(() => {
  tabla = $("#tabla_mis_envios").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      // { orderable: false, targets: [5, 7] }
    ],
  })
})

$(document).on('show.bs.modal', '#modal_ver_capturas', async (e) => {
  const id = e.relatedTarget.dataset.id
  console.log(id)
  await $("#body_capturas").load('../app/envio/obtenercapturas', { idEnvio: id })
})
$(document).on('hide.bs.modal', '#modal_ver_capturas', () => {
  $("#body_capturas").html('')
})
$(document).on('show.bs.modal', '#modal_eliminar_envio', async (e) => {
  const id = e.relatedTarget.dataset.id
  $("#id_envio_delete").val(id)
})
$(document).on('hide.bs.modal', '#modal_eliminar_envio', () => {
  setTimeout(() => {
    $("#id_envio_delete").val('0')
  }, 990);
})
async function eliminar_envio() {
  const res = await $.ajax({
    url: '../app/envio/delete',
    data: { idEnvio: $("#id_envio_delete").val() },
    type: 'DELETE',
    dataType: 'json',
  });
  if (res.status == 'success') {
    toast('Operación exitosa', res.message, 'success', 1500)
    setTimeout(() => {
      location.reload()
    }, 1500);
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}