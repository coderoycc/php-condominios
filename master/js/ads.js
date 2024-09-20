$(document).ready(() => {
  getAdsList();
})

$(document).on('click', '#btn_new_add', getFormNewAd);
$(document).on('submit', '#form_new_ad', saveNewAdd);
$(document).on('change', '#type_ad', changeOption)
$(document).on('submit', '#form_company', saveCompany)
$(document).on('click', '.nav-link.ads', (e) => {
  const btn = e.target.dataset
  if (btn.type === 'publicidad')
    getAdsList();
  else if (btn.type === 'publicitador')
    getAdvertiserList();
});

async function getAdsList(empresa = '0') {
  const data = empresa == '0' ? {} : { ader_id: empresa };
  const res = await $.ajax({
    url: '../app/ads/list_ad',
    type: 'GET',
    data,
    dataType: 'html'
  });
  $("#content_tabs").html(res);
}
async function getAdvertiserList() {
  const res = await $.ajax({
    url: '../app/ads/list_advertiser',
    type: 'GET',
    dataType: 'html'
  });
  $("#content_tabs").html(res);
}
async function getFormNewAd(e) {
  $(".nav-link.ads").removeClass('active')
  const res = await $.ajax({
    url: '../app/ads/form_new_ad',
    type: 'GET',
    dataType: 'html'
  });
  $("#content_tabs").html(res);
}
function changeOption(e) {
  const option = e.target.value
  if (option == 'VIDEO') {
    $("#add_content").html(`<div class="form-floating mb-3">
        <input type="text" class="form-control" name="video_url" placeholder="https://youtube.com/" required>
        <label for="video_url">URL del video</label>
      </div>`)
  }
  else if (option == 'IMAGEN' || option == 'GIF') {
    $("#add_content").html(`<div class="mb-3">
  <label for="formFile" class="form-label">Seleccionar</label>
  <input class="form-control" type="file" name="file" accept="image/*,image/gif" required>
</div>`)
  } else {
    $("#add_content").html(``)
  }
}
async function saveNewAdd(e) {
  e.preventDefault()
  const data = new FormData(e.target);
  const res = await $.ajax({
    url: '../app/ads/create_ad',
    type: 'POST',
    data,
    processData: false,
    contentType: false,
    dataType: 'json'
  });
  if (res.success) {
    toast('Agregado', res.message, 'success', 1500)
    setTimeout(() => {
      location.reload()
    }, 1600);
  } else {
    toast('Error', res.message, 'error', 3500)
  }
}
async function saveCompany(e) {
  e.preventDefault()
  const data = new FormData(e.target);
  const res = await $.ajax({
    url: '../app/company/create',
    type: 'POST',
    data,
    processData: false,
    contentType: false,
    dataType: 'json'
  });
  $("#modal_add_ad").modal('hide');
  if (res.success) {
    toast('Agregado', res.message, 'success', 1500)
    setTimeout(() => {
      location.reload();
    }, 1550);
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}