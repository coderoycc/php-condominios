$(document).ready(() => {
  get_residents();
});
$(document).on('submit', '#formFilter', getData)
$(document).on('show.bs.modal', '#modal_content_lockers', loadModalData)
async function get_residents(data = {}) {
  const res = await $.ajax({
    url: '../app/master/residents',
    type: 'GET',
    data,
    dataType: 'html',
  });
  $("#content").html(res)
  $("#table_residents").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      { orderable: false, targets: [4, 6, 7, 8, 9] }
    ],
    searching: false,
  });
}
async function getData(e) {
  e.preventDefault();
  const data = $(e.target).serializeArray();
  get_residents(data);
}
async function loadModalData(e) {
  const btn = e.relatedTarget;

  const res = await $.ajax({
    url: '../app/master/locker_content',
    type: 'GET',
    data: { id: btn.dataset.user, depa_id: btn.dataset.depa, key: btn.dataset.key },
    dataType: 'html',
  });
  $("#content_lockers").html(res)
}