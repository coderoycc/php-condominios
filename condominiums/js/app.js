$(document).ready(() => {
  load()
});
$(document).on('submit', '#form_new_condominius', sendForm)
$(document).on('input', '#form_new_condominius', changesForm)
$(document).on('show.bs.modal', '#editar_condominio', loadDataModalEdit)
async function load() {
  const res = await $.ajax({
    url: '../app/condominius/all',
    data: {},
    type: 'GET',
    dataType: 'html',
  });
  $('#condominios_content').html(res);
}
async function sendForm(e) {
  e.preventDefault();
  const data = $("#form_new_condominius").serializeArray();
  console.log(data)
  const res = await $.ajax({
    url: '../app/condominius/create',
    data,
    type: 'POST',
    dataType: 'JSON',
  });
  if (res.success) {
    toast('Condominio creado', 'Se creó el condominio', 'success', 2000)
    $("#nuevo_condominio").modal('hide')
    setTimeout(() => {
      location.reload()
    }, 2100);
  } else {
    toast('Ocurrió un error', res.message ?? 'NO se creó el condominio', 'error', 2800)
    if (res.errors) {
      errorsForm(res.errors)
    }
  }
}
async function loadDataModalEdit(e) {
  const id = $(e.relatedTarget).data('id');
}
function errorsForm(arr) {
  arr.forEach((e) => {
    $(`#${e.name}_feed`).html(e.error);
    $(`input[name="${e.name}"]`).addClass('is-invalid');
  })
}
function changesForm(e) {
  $(e.target).removeClass('is-invalid');
}