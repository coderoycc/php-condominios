$(document).ready(() => {
  loadDataByQuery();
})

$(document).on('submit', '#fill_amounts_form', send_amounts)
$(document).on('submit', '#update_amounts', update_amounts)
$(document).on('change', '#year_codes', load_data_year)
$(document).on('show.bs.modal', '#modal_register_payment', modalRegisterOpen)
async function modalRegisterOpen(e) {
  const key = e.relatedTarget.dataset.key;
  const id = e.relatedTarget.dataset.id;
  fill_amounts(id, key)
}
function changeButtons(req) {
  $(".btn-menu").removeClass('active')
  $(`#btn-${req}`).addClass('active');
}
function loadDataByQuery() {
  const query = getQueryValueFromUrl('req') || 'add';
  changeButtons(query);
  console.log(query)
  switch (query) {
    case 'add':
      list_subscriptions_enable()
      break;
    case 'view':
      list_service_btn('process')
      break;
    case 'history':
      list_service_btn('history')
      break;
    case '':
      list_service_btn('pagar')
      break;
    default:
      break;
  }
}

function list_service_btn(type) {
  if (type == 'history') {
    getAllServicesHistory()
  } else if (type == 'process') {
    getAllServicesInProcess()
  } else {
    getAllServicesToPay()
  }
}
async function getAllServicesInProcess() {
  $("#type_list").html('Montos registrados')
  const res = await $.ajax({
    url: '../app/services/services_in_process',
    data: {},
    type: 'GET',
    dataType: 'html'
  });
  $("#table_services").html(res);
  $("#table_services_content").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [5] }
    ],
  })
}
async function list_subscriptions_enable() {
  $("#type_list").html('Suscripciones habilitadas para servicios')
  const res = await $.ajax({
    url: '../app/services/list_subs',
    data: {},
    type: 'GET',
    dataType: 'html'
  })
  $("#table_services").html(res);
  $("#table_subscription").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [2, 5] }
    ],
  })

}
async function getAllServicesToPay() {
  $("#type_list").html('Lista de servicios para pagar')
  const res = await $.ajax({
    url: '../app/services/services_to_pay',
    data: {},
    type: "GET",
    dataType: 'html'
  });
  $("#table_services").html(res);
}
async function getAllServicesHistory() {
  $("#type_list").html('Historial del pago de servicios')
  const res = await $.ajax({
    url: '../app/services/history_all',
    data: {},
    type: "GET",
    dataType: 'html'
  });
  $("#table_services").html(res);
  $("#table_services_content").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [5] }
    ],
  })
}


async function see_codes(id, year = null) {
  year = year || new Date().getFullYear();
  const res = await $.ajax({
    url: '../app/services/codes_department',
    data: { id, year },
    type: 'GET',
    dataType: 'html'
  });
  $("#panel_content").html(res);
}
async function fill_amounts(id, key) {
  const res = await $.ajax({
    url: '../app/services/fill_amounts',
    data: { id, key },
    type: 'GET',
    dataType: 'html'
  });
  $("#data_add_amounts").html(res);
}
async function send_amounts(e) {
  e.preventDefault();
  const data = $('#fill_amounts_form').serializeArray();
  const mes = $("#mes").val()
  data.push({ name: 'mes', value: mes })
  if (mes == '') {
    toast('Seleccione un mes', '', 'error', 2090);
    return;
  }
  const res = await $.ajax({
    url: '../app/services/add_amounts',
    data,
    type: 'POST',
    dataType: 'json'
  });
  if (res.success) {
    toast('Montos Agregados', '', 'success', 2090);
    $("#panel_content").children().remove();
  } else {
    toast('Error al agregar montos', res.message, 'error', 5000);
  }
}
async function update_amounts(e) {
  e.preventDefault();
  const data = $("#update_amounts").serializeArray();
  const res = await $.ajax({
    url: '../app/services/update_amounts',
    data,
    type: 'PUT',
    dataType: 'json'
  });
  console.log(res)
}
async function load_data_year(e) {
  const year = e.target.value;
  const id = $("#depa_id_codes").val();
  see_codes(id, year);
}

async function edit_amount(month, depa_id) {
  const year = $('#year_codes').val();
  console.log(month, year, depa_id)
  const res = await $.ajax({
    url: '../app/services/edit_amounts',
    data: { month, year, depa_id },
    type: 'GET',
    dataType: 'html'
  });
  $("#panel_content").html(res);
}