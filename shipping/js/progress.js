$(document).ready(() => {
  getData()
});
$(document).on('show.bs.modal', '#modal_add_price', show_modal_price)
async function getData() {
  const res = await $.ajax({
    url: '../app/shipping/get_all',
    type: "get",
    dataType: "html",

  });
  $("#progress_content").html(res)
}
function show_modal_price(e) {
  const id = $(e.relatedTarget).data('id');
  $("#id_shipping_modal").val(id)
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
    toast('Operación exitosa', res.message, 'success', 3500);
    setTimeout(() => {
      location.reload()
    }, 3400);
  } else
    toast('Error', res.message, 'error', 3500)
}