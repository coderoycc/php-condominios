$(document).ready(() => {
  subscriptionTypeList();
})

async function subscriptionTypeList() {
  try {
    const res = await $.ajax({
      url: '../app/subscription/types_web',
      type: 'GET',
      data: {},
      dataType: 'html'
    });
    $("#list_subscription_types").html(res);
  } catch (error) {
    console.log(error)
  }
}

$(document).on('input', "#tag_add", (e) => {
  let v = e.target.value;
  v = v.substring(0, 1).toUpperCase() + v.substring(1);
  e.target.value = v;
})
async function addType() {
  const form = $("#add_type_form").serializeArray()
  const verCasillero = $("#ver_casillero").is(':checked') ? 1 : 0;
  const verServicio = $("#ver_servicios").is(':checked') ? 1 : 0;
  form.push({ name: "verCasillero", value: verCasillero })
  form.push({ name: "verServicio", value: verServicio })
  const res = await $.ajax({
    url: '../app/subscription/add_type',
    type: 'POST',
    data: form,
    dataType: 'json'
  });
  if (res.success) {
    toast('Proceso exitoso', 'Plan agregado', 'success', 2000)
    setTimeout(() => {
      location.reload();
    }, 2500);
  } else {
    toast('Error agregar', res.message, 'error', 2000)
  }
}
$(document).on('show.bs.modal', '#modal_delete_type', (e) => {
  $("#delete_type_id").val(e.relatedTarget.dataset.id)
  $("#delete_type").html(e.relatedTarget.dataset.name.toUpperCase())
})


async function delete_type() {
  const res = await $.ajax({
    url: '../app/subscription/delete_type',
    type: 'DELETE',
    data: { id: $("#delete_type_id").val() },
    dataType: 'json'
  });
  if (res.success) {
    toast('Proceso exitoso', 'Plan eliminado', 'success', 2000)
    setTimeout(() => {
      location.reload();
    }, 2100);
  } else {
    toast('No se pudo eliminar', res.message, 'error', 3600)
  }
}