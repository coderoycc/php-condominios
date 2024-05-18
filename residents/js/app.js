let table = null;
$(document).ready(() => {
  get_data()
})


async function get_data() {
  const res = await $.ajax({
    url: '../app/user/get_residents',
    type: 'GET',
    dataType: 'html'
    // data: data
  });
  $("#content_table").html(res)
  table = $("#table_residentes").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
    columnDefs: [
      // { orderable: false, targets: [5, 7] }
    ],
  })
}