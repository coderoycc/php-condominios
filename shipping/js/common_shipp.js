// funcion escucha para el modal detalles
$(document).on('show.bs.modal', '#modal_show_details', open_modal_details)
$content = $("#modal_details_content");
async function open_modal_details(e) {
  const btn = e.relatedTarget;
  const id = btn.dataset.id
  const res = await $.ajax({
    url: '../app/shipping/get_by_id',
    type: 'GET',
    data: {
      id
    },
    dataType: 'html'
  });
  $content.html(res);
}