$(document).ready(async function () {
  try {
    const res = await $.ajax({
      url: '../app/clugar/getAll',
      type: 'GET',
      dataType: 'json'
    });
    console.log(res)
    $('#t_body_lugares').html(generarTabla(res))
  } catch (error) {
    console.log(error)
  }
})

function generarTabla(data) {
  let html = '';
  data.forEach(element => {
    let fecha = new Date(element.fechaCreacion);
    html += `<tr>
    <td class="text-center">${element.idLugar}</td>
    <td>${element.lugar.toUpperCase()}</td>
    <td>${fecha.toLocaleString()}</td>
    <td class="text-center d-flex justify-content-around">
      <div><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal_nuevo_lugar" data-idlugar="${element.idLugar}" data-lugar="${element.lugar}"><i class="fa fa-pen"></i></button></div>
      <div><button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#modal_delete_lugar" data-idlugar="${element.idLugar}"><i class="fa fa-trash"></i></button></div>
    </td>
  </tr>`
  })
  return html;
}

$(document).on('show.bs.modal', '#modal_delete_lugar', (e) => {
  let idLugar = $(e.relatedTarget).data('idlugar');
  $("#idlugar_delete").val(idLugar)
})

$(document).on('show.bs.modal', '#modal_nuevo_lugar', (e) => {
  let idLugar = $(e.relatedTarget).data('idlugar');
  let lugar = $(e.relatedTarget).data('lugar');
  if (idLugar) {
    $("#idlugar_form").val(idLugar)
    $("#lugar_form").val(lugar)
  }
})

$(document).on('hide.bs.modal', '#modal_nuevo_lugar', () => {
  setTimeout(() => {
    $("#idlugar_form").val(0);
    $("#lugar_form").val('')
  }, 900);
})

async function deletelugar() {
  const idLugar = $("#idlugar_delete").val()
  try {
    const res = await $.ajax({
      url: '../app/clugar/delete',
      type: 'DELETE',
      data: { idLugar },
      dataType: 'json'
    });
    if (res.status == 'success') {
      $.toast({
        heading: 'Operación exitosa',
        icon: 'success',
        position: 'top-right',
        hideAfter: 1100
      })
      setTimeout(() => {
        location.reload()
      }, 1200);
    } else {
      $.toast({
        heading: 'Ocurrió un error',
        icon: 'danger',
        position: 'top-right',
        hideAfter: 1100
      })
    }
  } catch (error) {

  }
}

async function lugarUp() {
  const lugar = $("#lugar_form").val();
  const idLugar = $("#idlugar_form").val();
  try {
    let res;
    if (idLugar != 0 || idLugar != '0') {
      res = await $.ajax({
        url: '../app/clugar/update',
        type: 'POST',
        data: { lugar, idLugar },
        dataType: 'json'
      });

    } else {
      res = await $.ajax({
        url: '../app/clugar/create',
        type: 'POST',
        data: { lugar },
        dataType: 'json'
      });
    }
    if (res.status == 'success') {
      $.toast({
        heading: 'Operación exitosa',
        icon: 'success',
        position: 'top-right',
        hideAfter: 1100
      })
      setTimeout(() => {
        location.reload()
      }, 1200);
    } else {
      $.toast({
        heading: 'Ocurrió un error',
        icon: 'danger',
        position: 'top-right',
        hideAfter: 1100
      })
    }
  } catch (error) {
    console.log(error)
  }
  $("#modal_egreso_nuevo").modal('hide')
}