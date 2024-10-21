$(document).ready(() => {
  getAllServicesToPay();
})

$(document).on('submit', '#fill_amounts_form', send_amounts)
$(document).on('submit', '#update_amounts', update_amounts)
$(document).on('change', '#year_codes', load_data_year)


// async function list_subs() {
//   const table = await $.ajax({
//     url: '../app/services/list_subs',
//     data: {},
//     type: 'GET',
//     dataType: 'html'
//   })
//   $("#panel_sub").html(table);
// }
function list_service_btn(type) {
  type = type == 'history' ? 'historial' : 'para pagar'
  $("#type_list").html(type)
  if (type == 'historial') {
    getAllServicesHistory()
  } else {
    getAllServicesToPay()
  }
}

async function getAllServicesToPay() {
  const res = await $.ajax({
    url: '../app/services/services_to_pay',
    data: {},
    type: "GET",
    dataType: 'html'
  });
  $("#table_services").html(res);
}
async function getAllServicesHistory() {
  const res = await $.ajax({
    url: '../app/services/',
    data: {},
    type: "GET",
    dataType: 'html'
  });
  $("#table_services").html(res);
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
async function fill_amounts(id) {
  const res = await $.ajax({
    url: '../app/services/fill_amounts',
    data: { id },
    type: 'GET',
    dataType: 'html'
  });
  $("#panel_content").html(res);
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