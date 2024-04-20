var enviado = false;
async function enviarFormEdit(e) {
  if (!enviado) {
    enviado = false;
    const data = $(e).serializeArray();
    console.log(data);
    const form = new FormData();
    data.forEach(d => {
      form.append(d.name, d.value)
    })
    const res = await $.ajax({
      url: '../app/envio/update',
      data: form,
      processData: false,
      contentType: false,
      type: 'POST',
      dataType: 'json'
    });
    if (res.status == 'success') {
      enviado = true;
      $.toast({
        heading: '<b>ENVIO ACTUALIZADO</b>',
        text: 'Proceso exitoso',
        icon: 'success',
        position: 'top-right',
        stack: 3,
        hideAfter: 1500
      });
      setTimeout(() => {
        window.location.href = './';
      }, 1550);
    } else {
      enviado = false;
      $.toast({
        heading: '<b>OCURRIÓ UN ERROR</b>',
        text: 'No se pudo actualizar el envio',
        icon: 'danger',
        position: 'top-right',
        stack: 2,
        hideAfter: 2800
      })
    }
  }
}

$("#form_edit").validationEngine({
  promptPosition: "topLeft",
  scroll: false,
  focusFirstField: false,
  onValidationComplete: function (form, status) {
    console.log('enviar', form, status)
    if (status)
      enviarFormEdit(form)
    else
      toast('Formulario inválido', 'Verifique los campos', 'error', 2600)
  }
});