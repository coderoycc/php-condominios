var tabla = null;
$(document).ready(async () => {
  listarEntregados({})
});

async function listarEntregados(data) {
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
    url: '../app/envio/lista_entregados',
    type: 'GET',
    dataType: 'json',
    data: { idUsuario: cookie_u.idUsuario, ...data },
  });
  console.log(res);
  if (res.status == 'success') {
    $("#t_body_entregados").html(generarTabla(res.envios));
    tabla = $("#table_entregados").DataTable({
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
    let fechaLlegada = new Date(element.fecha_llegada).toLocaleDateString()
    let fechaEntrega = new Date(element.fecha_entrega).toLocaleDateString()
    let opciones = `
      <div><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#modal_entregar_envio" data-codigo="${element.codigo}" data-id="${element.idEnvio}">Entregar</button></div>
    `;
    // console.log(monto)
    html += `<tr>
    <td class="text-center">${element.idEnvio}</td>
    <td class="text-center">${element.idEnvio}-${element.codigo}</td>
    <td>${fechaLlegada}</td>
    <td>${fechaEntrega}</td>
    <td>${element.observacion_llegada}</td>
    <td>${element.nombre_destino}</td>
    <td align="center">${element.ci_destino}</td>
    <td align="center">${element.celular_destino}</td>
  </tr>`
  })
  return html;
}