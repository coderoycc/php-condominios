$(document).ready(() => {
  $("#table_dptos").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    // lengthMenu: false,
    bLengthChange: false,
    dom: '<f<t>ip>',
    columnDefs: [
      { orderable: false, targets: [2] }
    ],
  })
});

$(document).on('click', '.btn-subs-history', subHistory);
$(document).on('click', '.btn-subs-current', subCurrentDetail);
$(document).on('click', '.btn-close.btn-close-content', closeContent)

function deleteActiveBtns() {
  $('.btn-subs-history').removeClass('active');
  $('.btn-subs-current').removeClass('active');
}
async function subHistory(e) {
  deleteActiveBtns();
  const btn = e.currentTarget;
  $(btn).addClass('active');
}
async function subCurrentDetail(e) {
  deleteActiveBtns();
  const btn = e.currentTarget;
  $(btn).addClass('active');
  const res = await $.ajax({
    url: '../app/subscription/get',
    type: 'get',
    data: { id: btn.dataset.idsub },
    dataType: 'html'
  });
  $("#content_depa_sub").html(res);
}
function closeContent() {
  deleteActiveBtns();
  $("#content_depa_sub").html('');
}