const loader = `
<div class="w-100 d-flex justify-content-center mt-4">
  <div class="spinner-border text-primary" style="--bs-spinner-width:4rem;--bs-spinner-height:4rem;" role="status">
    <span class="visually-hidden"> Cargando...</span>
  </div>
</div>
`
$(document).ready(() => {
  load_data_report(0)
})
async function load_data_report(id) {
  const fechas = get_date();
  if (fechas == '') return;
  $("#tab_name").html(loader);
  let url = `../app/subscription/`;
  if (id == 0) url += `report_all?${fechas}`;
  else url += `report_by?id=${id}&${fechas}`;
  $("#tab_name").load(url)
}

async function on_click_tab(e) {
  $("#tab_name").html('')
  console.log(e.target.dataset.id)
  load_data_report(parseInt(e.target.dataset.id))
}
$(document).on('click', '#tab_names li button.tabs_reports', on_click_tab)

function get_date() {
  const inicio = $("#date_inicio").val();
  const fin = $("#date_final").val();
  if (inicio != '' & fin != '') {
    return `fecha_incio=${inicio}&fecha_final=${fin}`
  } else {
    toast('Agrege una fecha válida', '', 'error', 2900)
    return ''
  }
}
function load_report() {
  $element = $("#tab_names li button.tabs_reports.active")
  const id = $element.data('id')
  if (typeof id != 'undefined') {
    load_data_report(id)
  }
}