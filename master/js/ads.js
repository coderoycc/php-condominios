$(document).ready(() => {
  getAdsList();
})

$(document).on('click', '#btn_new_add', getFormNewAd);
$(document).on('submit', '#form_new_ad', saveNewAdd);
$(document).on('submit', '#form_edit_ad', updateAd);
$(document).on('change', '#type_ad', changeOption)
$(document).on('submit', '#form_company', saveCompany)
$(document).on('submit', '#form_company_edit', updateCompany)

$(document).on('show.bs.modal', '#delete_confirm_ad', openModalDeleteAd)
$(document).on('show.bs.modal', '#delete_confirm_company', openModalDeleteCompany)
$(document).on('show.bs.modal', '#modal_edit_company', openModalCompany)
$(document).on('click', '.nav-link.ads', (e) => {
  const btn = e.target.dataset
  if (btn.type === 'publicidad')
    getAdsList();
  else if (btn.type === 'publicitador')
    getAdvertiserList();
});
function openModalDeleteAd(e) {
  const btn = e.relatedTarget
  $("#id_ad_delete").val(btn.dataset.id)
}
function openModalDeleteCompany(e) {
  const btn = e.relatedTarget;
  console.log(btn.dataset)
  $("#id_company_delete").val(btn.dataset.id)
}
async function getAdsList(empresa = '0') {
  const data = empresa == '0' ? {} : { ader_id: empresa };
  const res = await $.ajax({
    url: '../app/ads/list_ad',
    type: 'GET',
    data,
    dataType: 'html'
  });
  $("#content_tabs").html(res);
  $("#table_ads").DataTable({
    language: lenguaje,
    info: false,
  })
  $("#table_ads_length").remove()
  $("#table_ads_filter").addClass('float-end')
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
      getAdvertiserList()
    }, 1550);
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}
async function form_edit_ad(id) {
  const res = await $.ajax({
    url: '../app/ads/form_edit_ad',
    type: 'GET',
    data: { id },
    dataType: 'html'
  });
  $("#content_tabs").html(res);
}
async function delete_ad() {
  const id = $("#id_ad_delete").val()
  const res = await $.ajax({
    url: '../app/ads/delete',
    type: 'DELETE',
    data: { id },
    dataType: 'json'
  });
  if (res.success) {
    toast('Eliminado', res.message, 'success', 1500);
    getAdsList()
  } else {
    toast('Error', res.message, 'error', 1500);
  }
}
async function delete_company() {
  const id = $("#id_company_delete").val()
  const res = await $.ajax({
    url: '../app/company/delete',
    type: 'DELETE',
    data: { id },
    dataType: 'json'
  });
  if (res.success) {
    toast('Eliminado', res.message, 'success', 1500);
    getAdvertiserList()
  } else {
    toast('Error', res.message, 'error', 1500);
  }
}
async function updateAd(e) {
  e.preventDefault();
  const data = $("#form_edit_ad").serializeArray();
  const res = await $.ajax({
    url: '../app/ads/update',
    type: 'PUT',
    data,
    dataType: 'json'
  });
  if (res.success) {
    toast('Actualizado', res.message, 'success', 1500)
    setTimeout(() => {
      location.reload()
    }, 1550);
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}
function openModalCompany(e) {
  const btn = e.relatedTarget;
  console.log(btn.dataset)
  $("#name_edit").val(btn.dataset.name);
  $("#id_company_edit").val(btn.dataset.id);
  $("#description_edit").val(btn.dataset.description);
  $("#phone_edit").val(btn.dataset.phone);
  $("#url_edit").val(btn.dataset.url);
}
async function updateCompany(e) {
  e.preventDefault();
  const data = $("#form_company_edit").serializeArray();
  const res = await $.ajax({
    url: '../app/company/update',
    type: 'PUT',
    data,
    dataType: 'json'
  });
  if (res.success) {
    toast('Actualizado', res.message, 'success', 1500)
    $("#modal_edit_company").modal('hide')
    getAdvertiserList()
  } else {
    toast('Error', res.message, 'error', 1500)
  }
}