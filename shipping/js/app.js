$filterButtons = $(".btn-table-shipp");
$(document).ready(() => {
  load_data('SIN PAGAR')
});

$(document).on('click', '.btn-table-shipp', filter_data);
$(document).on('show.bs.modal', '#modal_add_price', show_modal_price)
$(document).on('show.bs.modal', '#modal_add_tracking', show_modal_tracking)
function filter_data(e) {
  e.preventDefault();
  const btn = e.target;
  $filterButtons.removeClass("active");
  $(btn).addClass("active");
  load_data(btn.dataset.status);
}

async function load_data(status) {
  const res = await $.ajax({
    url: '../app/shipping/get_all',
    type: "get",
    data: { status },
    dataType: "html",
  })
  $("#shipp_content").html(res)
}
function show_modal_price(e) {
  const id = $(e.relatedTarget).data('id');
  const price = $(e.relatedTarget).data('price') ?? '';
  $("#id_shipping_modal").val(id)
  $("#price_modal").val(price)
}
async function sendPrice() {
  const id = $("#id_shipping_modal").val()
  const res = await $.ajax({
    url: '../app/shipping/add_price',
    type: "post",
    dataType: "json",
    data: {
      price: $("#price_modal").val(),
      id
    }
  });
  if (res.success) {
    $("#modal_add_price").modal('hide')
    $("#price_modal").val('')
    toast('Operación exitosa', res.message, 'success', 3000);
    load_data('PARA ENVIAR')
  } else
    toast('Error', res.message, 'error', 3500)
}
function show_modal_tracking(e) {
  const id = $(e.relatedTarget).data('id');
  const tracking_id = $(e.relatedTarget).data('tracking') ?? '';
  $("#id_shipping_modal_tracking").val(id)
  $("#tracking_id_value").val(tracking_id)
}
async function send_tracking_id() {
  const id = $("#id_shipping_modal_tracking").val();
  const tracking_id = $("#tracking_id_value").val();
  const res = await $.ajax({
    url: '../app/shipping/add_tracking',
    type: "post",
    dataType: "json",
    data: {
      tracking_id,
      id
    }
  });
  if (res.success) {
    $("#modal_add_tracking").modal('hide')
    $("#tracking_id_value").val('')
    $("#id_shipping_modal_tracking").val('')
    toast('Operación exitosa', res.message, 'success', 3000);
    load_data('ENVIADO')
  } else
    toast('Error', res.message, 'error', 3500)
}