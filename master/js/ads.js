$(document).ready(() => {
  getAdsList();
})

$(document).on('click', '#btn_new_add', getFormNewAd);
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