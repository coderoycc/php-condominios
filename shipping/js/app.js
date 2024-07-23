$filterButtons = $(".btn-table-shipp");
$(document).ready(() => {
  load_data('SIN PAGAR')
});

$(document).on('click', '.btn-table-shipp', filter_data);
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

