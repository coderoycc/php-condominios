$(document).ready(() => {
  getNotifications();
});

async function getNotifications() {
  const response = await $.ajax({
    url: '../app/notification/all',
    data: {},
    type: 'GET',
    dataType: 'html',
  });
  $("#notification_list").html(response)
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