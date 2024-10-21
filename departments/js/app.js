$(document).ready(() => {
  list_data()
})
$(document).on('show.bs.modal', "#depa_edit", open_modal_edit)
$(document).on('show.bs.modal', '#depa_delete', open_modal_delete)
$(document).on('show.bs.modal', '#subscription_depa', open_modal_subscription)
async function list_data() {
  const res = await $.ajax({
    url: '../app/department/list_all',
    type: 'GET',
    dataType: 'html',
  });
  $("#content_department").html(res)
  $("#table_departments").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      // { orderable: false, targets: [5, 7] }
    ],
  })
}
function open_modal_delete(e) {
  const id = e.relatedTarget.dataset.id
  const tipo = e.relatedTarget.dataset.message;
  $("#message_title").html(tipo)
  $("#id_depa_delete").val(id)
}
async function delete_department() {
  const id = $("#id_depa_delete").val()
  const res = await $.ajax({
    url: `../app/department/delete`,
    type: 'DELETE',
    data: { id },
    dataType: 'json',
  });
  if (res.success) {
    toast('Operación exitosa', 'Departamento dado de baja', 'success', 2000)
    setTimeout(() => {
      location.reload()
    }, 2100);
  } else {
    toast('Ocurrió un error', res.message, 'error', 3000)
  }
}
async function open_modal_edit(e) {
  const id = e.relatedTarget.dataset.id
  const res = await $.ajax({
    url: `../app/department/content_edit`,
    type: 'GET',
    data: { id },
    dataType: 'html',
  });
  $("#modal_content_edit").html(res);
}
async function update_department() {
  const data = $("#form_update_department").serializeArray();
  const res = await $.ajax({
    url: `../app/department/update`,
    type: 'PUT',
    data,
    dataType: 'json',
  });
  if (res.success) {
    toast('Operación exitosa', 'Departamento actualizado', 'success', 2000)
    setTimeout(() => {
      location.reload()
    }, 2100);
  } else toast('Ocurrió un error', res.message, 'error', 3000)
}
async function add_depa() {
  const data = $("#form_add_department").serializeArray();
  console.log(data)
  const res = await $.ajax({
    url: `../app/department/create`,
    type: 'POST',
    data,
    dataType: 'json',
  });
  if (res.success) {
    toast('Operación exitosa', 'Departamento creado', 'success', 2000)
    $("#form_add_department")[0].reset()
    setTimeout(() => {
      location.reload()
    }, 2100);
  } else
    toast('Ocurrió un error', res.message, 'error', 3000)
}
async function open_modal_subscription(e) {
  const id = e.relatedTarget.dataset.id
  const res = await $.ajax({
    url: `../app/department/depa_subscription`,
    type: 'GET',
    data: { id },
    dataType: 'html',
  });
  $("#subs_depa_content").html(res);
}