$(document).ready(() => {
  get_residents();
});
$(document).on('submit', '#formFilter', getData)
$(document).on('show.bs.modal', '#modal_content_lockers', loadModalData)
$(document).on('show.bs.modal', '#modal_enable_new_session', openModalEnableSession)
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
      { orderable: false, targets: [5, 7, 8, 9] }
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

function openModalEnableSession(e) {
  const id = e.relatedTarget.dataset.id;
  const name = e.relatedTarget.dataset.name;
  const key = e.relatedTarget.dataset.key;
  $("#id_user_reset_session").val(id);
  $("#pin_reset_session").val(key);
  $("#name_reset_session").html(name);
}

async function resetResidentSession() {
  const id = $("#id_user_reset_session").val();
  const key = $("#pin_reset_session").val();
  const res = await $.ajax({
    url: '../app/resident/reset_session',
    type: 'POST',
    data: { id, key },
    dataType: 'json',
  });
  if (res.success) {
    toast('Sesión reiniciada', 'El residente puede iniciar sesión', 'success', 3500);
  } else
    toast('Error', 'No se pudo reiniciar la sesión', 'error', 2500);
}