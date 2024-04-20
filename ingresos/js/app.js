var tabla = null;
$(document).ready(async () => {
  listar()
})
async function listar(estado) {
  if (tabla) {
    tabla.destroy();
  }
  const res = await $.ajax({
    url: '../app/cproyecto/getProjects',
    type: 'GET',
    dataType: 'json',
    data: { tipo: 'INGRESO' },
  });
  if (res.status == 'success') {
    const data = JSON.parse(res.data);
    const pagos = JSON.parse(res.pagos);
    $("#t_body_ingresos").html(generarTabla(data, pagos));
    tabla = $("#table_ingresos").DataTable({
      language: lenguaje,
      info: false,
      scrollX: true,
      columnDefs: [
        { orderable: false, targets: [5, 6] }
      ],
    })
  }
}

function generarTabla(data, pagos) {
  let html = '';
  const cookie = JSON.parse(decodeURIComponent(getCookie('user_obj')));
  data.forEach((element) => {
    let fecha = new Date(element.fechaCreacion);
    let opciones = `
      <div><a class="btn btn-success rounded-circle" href="./nuevo.php?proid=${element.idProyecto}"><i class="fa fa-solid fa-circle-plus"></i></a></div>
      <div><a class="btn btn-primary" type="button" href="./pagoslist.php?proid=${element.idProyecto}"><i class="fa fa-eye"></i></a></div>`;
    if (cookie.rol == 'ADMIN' || cookie.rol == 'EDITOR') {
      opciones += `<div><button class="btn btn-info" type="button"  data-bs-toggle="modal" data-bs-target="#modal_ingreso_nuevo" data-idproyecto="${element.idProyecto}"><i class="fa fa-pencil"></i></button></div>`;
    }
    let pago = pagos.find(p => p.idProyecto == element.idProyecto);
    let monto = pago ? pago.total : 0;
    html += `<tr>
    <td class="text-center">${element.idProyecto}</td>
    <td>${element.proyecto.toUpperCase()}</td>
    <td>${element.tipo}</td>
    <td class="text-end">${Number(element.montoRef).toFixed(2)}</td>
    <td class="text-end">${Number(monto).toFixed(2)}</td>
    <td class="text-center">${element.alias.toUpperCase()}</td>
    <td>${fecha.toLocaleDateString()}</td>
    <td align="center">
      <div class="d-flex justify-content-around gap-2">
        ${opciones}
      </div>
    </td>
  </tr>`
  })
  return html;
}

async function ingresoUp() {
  let data = $("#form_ingreso").serialize();
  data += '&tipo=INGRESO';
  const res = await $.ajax({
    url: '../app/cproyecto/create',
    type: 'POST',
    data,
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
  }
  $("#modal_egreso_nuevo").modal('hide')
}

$("#modal_ingreso_nuevo").on('show.bs.modal', async (e) => {
  if (parseInt(e.relatedTarget.dataset.idproyecto) != 0) { // edit
    const res = await $.ajax({
      url: '../app/cproyecto/getProjectID',
      type: 'GET',
      data: { idProyecto: e.relatedTarget.dataset.idproyecto },
      dataType: 'json'
    });
    if (res.status == 'success') {
      const data = JSON.parse(res.data);
      $("#idProyecto_egreso").val(data.idProyecto);
      $("#descripcion_e").val(data.proyecto.toUpperCase());
      $("#monto_e").val(data.montoRef);
    } else {
      console.warn(res);
    }
  } else { //create
    let htmlOptions = `
    <option value="">-- Seleccione una opción --</option>
    <option value="PENDIENTE">PENDIENTE</option>
    <option value="SALDADO" >SALDADO</option>`;
    $("#estado_e").html(htmlOptions);
  }
})

$("#modal_ingreso_nuevo").on('hide.bs.modal', async () => {
  setTimeout(() => {
    $("#idProyecto_egreso").val('0');
    $("#descripcion_e").val('');
    $("#monto_e").val('');
    $("#estado_e").val('');
  }, 800);
})