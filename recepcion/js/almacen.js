var tabla = null;
$(document).ready(async () => {
  listar()
});

async function listar() {
  if (tabla) {
    tabla.destroy();
  }
  const user = getCookie('user_obj');
  if (!(user && user != '')) {
    window.location.href = '../auth/login.php';
    return;
  }
  const cookie_u = JSON.parse(decodeURIComponent(user));
  const res = await $.ajax({
    url: '../app/envio/lista_envios_a_recibir',
    type: 'GET',
    dataType: 'json',
    data: { idLugar: cookie_u.idLugar, estado: 'ENVIADO' },
  });
  console.log(res);
  if (res.status == 'success') {
    $("#t_body_almacen").html(generarTabla(res.envios));
    tabla = $("#tabla_almacen").DataTable({
      language: lenguaje,
      info: false,
      scrollX: true,
      columnDefs: [
        // { orderable: false, targets: [5, 7] }
      ],
    })
  }
}

function generarTabla(data) {
  let html = '';
  data.forEach((element) => {
    let fechaEnvio = new Date(element.fecha_envio);

    let opciones = `
      <div><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal_entregar_envio" data-codigo="${element.codigo}" data-id="${element.idEnvio}">Entregar</button></div>
    `;
    let codigo = (element.capturas != '' && element.capturas != null) ?
      `<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal_ver_capturas' data-id="${element.idEnvio}">${element.idEnvio}-${element.codigo}</button>
    </div>`: `${element.idEnvio}-${element.codigo}`;
    html += `<tr id="row-tb-${element.idEnvio}">
    <td class="text-center">${element.idEnvio}</td>
    <td class="text-center">${codigo}</td>
    <td>${fechaEnvio.toLocaleDateString()}</td>
    <td>${element.detalle_envio}</td>
    <td align="center">${element.nombre_origen}-${element.ci_origen}</td>
    <td align="center">
      <input type="checkbox" class="check-llegada" data-id="${element.idEnvio}">
    </td>
  </tr>`
  })
  return html;
}

$(document).on('change', '.check-llegada', async (e) => {
  const id = e.target.dataset.id;
  const res = await $.ajax({
    url: '../app/envio/registra_llegada',
    type: 'PUT',
    dataType: 'json',
    data: { idEnvio: id },
  });
  if (res.status == 'success') {
    toast("Envio marcado como recibido", "Eliminado de la lista", "success", time = 2500)
    setTimeout(() => {
      console.log(id)
      $(`#row-tb-${id}`).remove();
    }, 2000);
  } else {
    toast("Error al marcar como recibido", "Error", "error", time = 2500)
  }
})
$(document).on('show.bs.modal', '#modal_ver_capturas', async (e) => {
  const id = e.relatedTarget.dataset.id
  console.log(id)
  await $("#body_capturas").load('../app/envio/obtenercapturas', { idEnvio: id })
})
$(document).on('hide.bs.modal', '#modal_ver_capturas', () => {
  $("#body_capturas").html('')
})