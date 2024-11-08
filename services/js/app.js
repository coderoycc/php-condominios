$(document).ready(() => {
  loadDataByQuery();
})

$(document).on('submit', '#fill_amounts_form', send_amounts)
$(document).on('submit', '#update_amounts', update_amounts)
$(document).on('submit', '#pay_form', add_vouchers)
$(document).on('change', '#year_codes', load_data_year)
$(document).on('show.bs.modal', '#modal_register_payment', modalRegisterOpen)
$(document).on('show.bs.modal', '#modal_edit_payment', modalEditOpen)
$(document).on('show.bs.modal', '#modal_detail_payment', modalOpenDetailPayment)
$(document).on('show.bs.modal', '#modal_pay_voucher', modalOpenPay)
async function modalRegisterOpen(e) {
  const key = e.relatedTarget.dataset.key;
  const id = e.relatedTarget.dataset.id;
  fill_amounts(id, key)
}
async function modalOpenPay(e) {
  const key = e.relatedTarget.dataset.key;
  const id = e.relatedTarget.dataset.id;
  const year = e.relatedTarget.dataset.year;
  const month = e.relatedTarget.dataset.month;
  const res = await $.ajax({
    url: '../app/services/pay_voucher',
    data: { key, id, year, month },
    type: 'GET',
    dataType: 'html'
  });
  $("#data_pay_amounts").html(res);
}
async function modalOpenDetailPayment(e) {
  const key = e.relatedTarget.dataset.key;
  const id = e.relatedTarget.dataset.id;
  const year = e.relatedTarget.dataset.year;
  const month = e.relatedTarget.dataset.month;
  const res = await $.ajax({
    url: '../app/services/detail_payment',
    data: { key, id, year, month },
    type: 'GET',
    dataType: 'html'
  });
  $("#data_datail_amounts").html(res)
}
async function modalEditOpen(e) {
  const key = e.relatedTarget.dataset.key;
  const id = e.relatedTarget.dataset.id;
  const year = e.relatedTarget.dataset.year;
  const month = e.relatedTarget.dataset.month;
  edit_amount(year, month, id, key)
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
    case 'topay':
      getAllServicesToPay()
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
  $("#table_services_content").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [5] }
    ],
  })
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
  const fecha = $("#date_add_new").val()
  const month = fecha.split('-')[1];
  const year = fecha.split('-')[0];
  data.push({ name: 'month', value: month })
  data.push({ name: 'year', value: year })
  if (fecha == '') {
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
    $("#modal_register_payment").modal('hide');
    setTimeout(() => {
      window.location.href = `?req=view`
    }, 2090);
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
  if (res.success) {
    toast('Montos Agregados', '', 'success', 2090);
    $("#modal_edit_payment").modal('hide');
    setTimeout(() => {
      location.reload()
    }, 2090);
  } else {
    toast('Error al actualizar montos', res.message, 'error', 5000);
  }
}
async function load_data_year(e) {
  const year = e.target.value;
  const id = $("#depa_id_codes").val();
  see_codes(id, year);
}

async function edit_amount(year, month, sub_id, key) {
  const res = await $.ajax({
    url: '../app/services/edit_amounts',
    data: { month, year, sub_id, key },
    type: 'GET',
    dataType: 'html'
  });
  $("#data_edit_amounts").html(res);
}
async function add_vouchers(e) {
  e.preventDefault();
  // enviar en un FormData el formulario
  const formData = new FormData(document.getElementById('pay_form'));
  const res = await $.ajax({
    url: '../app/services/add_vouchers_payment',
    data: formData,
    type: 'POST',
    dataType: 'json',
    processData: false,
    contentType: false,
    cache: false,
  });
  console.log(res)
}