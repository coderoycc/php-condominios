$(document).ready(() => {
  get_residents();
});
$(document).on('submit', '#formSubs', getData)
$(document).on('show.bs.modal', '#modal_change_subscription', loadModalData)
$(document).on('change', '#option_type_sub', changePriceToAdd)
$(document).on('submit', '#add_form_new', sendForm)
$(document).on('submit', '#form_change_subscription', sendChangePlan)
$(document).on('hide.bs.modal', '#modal_change_subscription', () => {
  $("#form_change_subscription")[0].reset()
})
$(document).on('show.bs.modal', '#modal_add_subscription', loadModalSubscription)
$(document).on('hide.bs.modal', '#modal_add_subscription', () => {
  $("#content_add_sub").html('')
})
async function get_residents(data = {}) {
  const res = await $.ajax({
    url: '../app/master/residents_sub',
    type: 'GET',
    data,
    dataType: 'html',
  });
  $("#content").html(res)
  $("#table_residents").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [4, 6, 7, 8, 9] }
    ],
    searching: false,
  });
}
async function getData(e) {
  e.preventDefault();
  const data = $(e.target).serializeArray();
  get_residents(data);
}
async function loadModalData(e) {
  const btn = e.relatedTarget;
  $("#id_sub_current").val(btn.dataset.idsub);
  $("#key_sub_data").val(btn.dataset.key)
  const res = await $.ajax({
    url: '../app/subscription/types',
    type: 'GET',
    data: { pin: btn.dataset.key },
    dataType: 'json',
  });
  htmlOptionsTypes(res.data, btn.dataset.typeid)
}
function htmlOptionsTypes(types, currentId) {
  let html = '';
  types.forEach(type => {
    if (type.id_subscription_type == currentId) {
      $("#current_price").val(type.price)
      html += `<option value="${type.id_subscription_type}" selected data-price="${type.price}">${type.name}</option>`;
    } else {
      html += `<option value="${type.id_subscription_type}" data-price="${type.price}">${type.name}</option>`;
    }
  });
  $("#option_type_sub").html(html)
}
function changePriceToAdd(e) {
  const option = $(this).find('option:selected');
  const price = option[0].dataset.price ?? '0';
  const currentPrice = $("#current_price").val();
  const newPrice = parseFloat(price) - parseFloat(currentPrice);
  $("#current_price_new").val(price)
  $("#price_to_add").val(newPrice.toFixed(2))
}
async function sendChangePlan(e) {
  e.preventDefault()
  const data = $("#form_change_subscription").serializeArray()
  const res = await $.ajax({
    url: '../app/subscription/change_plan',
    type: 'POST',
    data,
    dataType: 'json',
  });
  if (res.success) {
    $("#modal_change_subscription").modal('hide');
    toast('Plan cambiado', 'El plan se ha cambiado', 'success', 2300);
    const data = $("#formSubs").serializeArray();
    get_residents(data);
  } else {
    toast('Ocurrió un error', res.message, 'error', 2300)
  }
}
async function loadModalSubscription(e) {
  const btn = e.relatedTarget;
  const html = await $.ajax({
    url: '../app/subscription/get_new_subscription',
    type: 'GET',
    data: { pin: btn.dataset.key, iddepa: btn.dataset.depa, user_id: btn.dataset.user },
    dataType: 'html',
  })

  $("#content_add_sub").html(html)
}

async function sendForm(e) {
  e.preventDefault();
  const data = $("#add_form_new").serializeArray();
  const res = await $.ajax({
    url: '../app/subscription/add_subscription',
    type: 'POST',
    data,
    dataType: 'json',
  });
  console.log(res)
  if (res.success) {
    $("#modal_add_subscription").modal('hide');
    toast('Proceso exitoso', 'Suscripciónn agregado', 'success', 2400);
    const formFilter = $("#formSubs").serializeArray();
    get_residents(formFilter);
  } else {
    toast('Ocurrio un error', 'No se pudo agregar la suscripción', 'danger', 2300)
  }
}