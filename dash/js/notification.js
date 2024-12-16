$(document).ready(() => {
  getNotifications();
});
$(document).on('change', '#type_status', load_notifications);

async function getNotifications(seen = undefined) {
  console.log('PETICION NUEVA')
  const response = await $.ajax({
    url: '../app/notification/all',
    data: { seen },
    type: 'GET',
    dataType: 'html',
  });
  $("#notification_list").html(response)
}

function load_notifications(e) {
  const value = $(e.target).val();
  if (value == '') {
    getNotifications();
  } else if (value == 'Vistos') {
    getNotifications(1);
  } else if (value == 'No vistos') {
    getNotifications(0);
  }
}

async function changeSeen(id) {
  const data = { id };
  const response = await $.ajax({
    url: '../app/logevent/seen',
    data,
    type: 'PUT',
    dataType: 'JSON',
  });
  if (response.success) {
    toast('Notificacion actualizada', 'Se ha cambiado el estado ', 'success', 2100)
    getNotifications();
    loadNotifications(); // script.js 
  } else {
    toast('Ocurrio un error', response.message, 'danger', 2300)
  }
}