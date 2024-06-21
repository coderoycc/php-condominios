$(document).ready(() => {
  list_subs();
})

$(document).on('submit', '#fill_amounts_form', send_amounts)
$(document).on('change', '#year_codes', load_data_year)


async function list_subs() {
  const table = await $.ajax({
    url: '../app/services/list_subs',
    data: {},
    type: 'GET',
    dataType: 'html'
  })
  $("#panel_sub").html(table);
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
  }
}
async function load_data_year(e) {
  const year = e.target.value;
  const id = $("#depa_id_codes").val();
  see_codes(id, year);
}