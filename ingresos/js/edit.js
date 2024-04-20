$(document).on('submit', '#form_update', async (e) => {
  e.preventDefault();
  const data = $(e.target).serializeArray();
  let formData = new FormData();
  formData.append('idPago', e.target.dataset.idpago);
  $.each(data, (_, e) => {
    formData.append(e.name, e.value)
  });
  if (imagen != null) formData.append('imagen', imagen);
  if (audio != null) formData.append('audio', audio);
  const res = await $.ajax({
    url: '../app/cpago/updateIngreso',
    data: formData,
    contentType: false,
    processData: false,
    type: 'POST',
    dataType: 'json'
  });
  if (res.status == 'success') {
    $.toast({
      heading: '<b>PAGO ACTUALIZADO</b>',
      icon: 'success',
      position: 'top-right',
      stack: 3,
      hideAfter: 1500
    });
    const pago = JSON.parse(res.pago)
    setTimeout(() => {
      window.location.href = `./pagoslist.php?proid=${pago.idProyecto}`;
    }, 1500);
  } else {
    $.toast({
      heading: '<b>OCURRIÓ UN ERROR</b>',
      icon: 'danger',
      position: 'top-right',
      stack: 2,
      hideAfter: 2800
    })
  }
})