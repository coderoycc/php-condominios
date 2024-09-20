$(document).ready(() => {
  getData()
});

async function getData() {
  const res = await $.ajax({
    url: '../app/shipping/get_all',
    type: "get",
    dataType: "html",

  });
  $("#progress_content").html(res)
}

