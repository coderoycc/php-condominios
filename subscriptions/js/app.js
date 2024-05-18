$(document).ready(() => {
  data_list();
})

async function data_list() {
  const res = await $.ajax({
    url: '../app/department/subscribed',
    method: 'GET',
    dateType: 'html',
  })
  $("#subscriptions_data").html(res)
  $("#table_subs").DataTable({
    language: lenguaje,
    info: false,
    scrollX: true,
  })
}