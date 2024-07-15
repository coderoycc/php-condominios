$modalAdd = $('#modal_add_locker');
$modalDel = $("#modal_delete_locker");
$modalEdit = $("#modal_edit_locker");
$modalAdd.on('hide.bs.modal', () => {
  $("#nro_locker").val("");
})
$modalDel.on('show.bs.modal', (e) => {
  $("#delete_locker_id").val(e.relatedTarget.dataset.id);
  $("#delete_nro_locker").html(e.relatedTarget.dataset.nro);
});
$modalEdit.on('show.bs.modal', async (e) => {
  const id = e.relatedTarget.dataset.id;
  const html = await $.ajax({
    url: '../app/locker/edit_locker',
    data: { locker_id: id },
    type: 'GET',
    dataType: 'html'
  });
  $('#modal_content_edit').html(html)
})
$(document).ready(() => {
  listLockers();
})

async function addLocker() {
  const value = $("#nro_locker").val();
  try {
    if (value != 0 && value != "") {
      const detalle = $("#detail_locker").val();
      const inOut = $("#in_out").val();
      const res = await $.ajax({
        url: '../app/locker/create',
        data: {
          nro: value,
          detail: detalle,
          in_out: inOut
        },
        type: 'POST',
        dataType: 'json'
      });
      if (res.success) {
        toast('Nuevo locker', 'Se agrego correctamente', 'success', 3500);
        setTimeout(() => {
          window.location.reload();
        }, 3450);
      }
      $modalAdd.modal('hide');
    } else {
      toast('Número de casillero', 'Campo necesario', 'error', 3800);
    }
  } catch (error) {
    // console.log(error.responseJSON)
    const message = error.responseJSON.message ?? 'Ocurrio un error';
    toast('Error', message, 'error', 3800);
  }
}

async function listLockers() {
  try {
    const res = await $.ajax({
      url: '../app/locker/list',
      type: 'GET',
      data: {},
      dataType: 'html'
    });
    $("#list_lockers").html(res);
  } catch (error) {
    console.log(error)
  }
}

async function delete_locker() {
  const id = $("#delete_locker_id").val();
  try {
    const res = await $.ajax({
      url: '../app/locker/delete',
      data: { id: id },
      type: 'DELETE',
      dataType: 'json'
    });
    if (res.success) {
      toast('Eliminar locker', 'Se elimino correctamente', 'success', 3500);
      setTimeout(() => {
        window.location.reload();
      }, 3450);
    }
  } catch (error) {
    console.log(error)
  }
}