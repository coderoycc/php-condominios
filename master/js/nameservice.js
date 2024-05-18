$(document).ready(() => {
  name_list();
})
$(document).on('show.bs.modal', "#service_edit", open_modal_edit);
$(document).on('show.bs.modal', "", open_modal_delete)
function open_modal_delete(e) {
  const id = $(e.relatedTarget).data('id');
  $("#id_service_delete").val(id);
}
function open_modal_edit(e) {
  const id = $(e.relatedTarget).data('id');
  const name = $(e.relatedTarget).data('service');
  const acronym = $(e.relatedTarget).data('acronimo');
  $("#id_service_edit").val(id);
  $("#name_edit").val(name);
  $("#acronimo_edit").val(acronym);
}
async function name_list() {
  const res = await $.ajax({
    type: "GET",
    url: "../app/master/list_service_names?type=web",
    dataType: "html"
  });
  $("#nameservices_content").html(res);
}

async function add_service_name() {
  const name = $("#name_service").val()
  const acronym = $("#acronym_service").val()
  const res = await $.ajax({
    type: "POST",
    url: "../app/master/add_service_name",
    data: {
      name: name,
      acronym: acronym
    },
    dataType: "json"
  });
  if (res.success) {
    toast('Operación exitosa', 'Nombre de servicio agregado', 'success', 2200);
    setTimeout(() => {
      location.reload();
    }, 2500);
  } else {
    toast('Error', res.message, 'error', 2500);
  }
}
async function delete_nameservice() {
  const id = $("#id_service_delete").val()
  const res = await $.ajax({
    type: "DELETE",
    url: "../app/master/delete_nameservice",
    data: {
      id: id
    },
    dataType: "json"
  });
  if (res.success) {
    toast('Operación exitosa', 'Servicio eliminado', 'success', 2200);
    setTimeout(() => {
      location.reload();
    }, 2290);
  } else {
    toast('Error', res.message, 'error', 2500);
  }
}
async function update_nameservice() {
  const name = $("#name_edit").val()
  const acronym = $("#acronimo_edit").val()
  const id_edit = $("#id_service_edit").val()
  const res = await $.ajax({
    type: "PUT",
    url: "../app/master/update_service_name",
    data: {
      name: name,
      acronym: acronym,
      id: id_edit
    },
    dataType: "json"
  });
  if (res.success) {
    toast('Operación exitosa', 'Nombre de servicio actualizado', 'success', 2000);
    setTimeout(() => {
      location.reload();
    }, 2100);
  } else {
    toast('Error', res.message, 'error', 2500);
  }
}