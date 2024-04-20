window.addEventListener('DOMContentLoaded', event => {

  // Toggle the side navigation
  const sidebarToggle = document.body.querySelector('#sidebarToggle');
  if (sidebarToggle) {
    // Uncomment Below to persist sidebar toggle between refreshes
    // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
    //     document.body.classList.toggle('sb-sidenav-toggled');
    // }
    sidebarToggle.addEventListener('click', event => {
      event.preventDefault();
      document.body.classList.toggle('sb-sidenav-toggled');
      localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
    });
  }

  const color = localStorage.color != undefined && localStorage.color != '' ? localStorage.color : '#212529';
  console.log('El color es: ' + color)

  $('#top_nav').css('background-color', color);
  $('#sidenavAccordion').css('background-color', color);
  $(".sb-sidenav-footer").css('background-color', `${color}`)

  let baseurl = location.pathname.split('/');
  baseurl = baseurl[baseurl.length - 2];
  console.log(baseurl)
  $page = $(`a[data-bs-target="#${baseurl}Collapse"]`);
  if ($page) {
    $page.addClass('active');
    $page.removeClass('collapsed');
    $(`#${baseurl}Collapse`).addClass('show')
  }
});

$(document).on('click', '#idLogout', async () => {
  const res = await $.ajax({
    url: '../app/usuario/logout',
    data: {},
    type: 'POST',
    dataType: 'json',
  })
  if (res.status == 'success') {
    setTimeout(() => {
      window.location.href = '../auth/login.php';
    }, 1000);
  } else {
    console.log(res)
    // alert('Error al cerrar sesión');
  }
})

$(document).on('keyup', "#pass_repeat", (e) => {
  if (e.target.value != $('#n_pass').val()) {
    $(e.target).addClass('is-invalid');
    $("#btn_cambiar").prop("disabled", true);
  } else {
    $(e.target).removeClass('is-invalid');
    $(e.target).addClass('is-valid');
    $("#btn_cambiar").prop("disabled", false);
  }
  if (e.target.value == '') {
    $(e.target).removeClass('is-valid');
    $(e.target).removeClass('is-invalid');
  }
})

$(document).on("show.bs.modal", "#modal_usuario", function (event) {
  var button = $(event.relatedTarget) // Botón que activé el modal
  $("#id_user").val(button.data('id'));
})

$(document).on('show.bs.modal', '#modal_cambiar_color', (e) => {
  var button = $(e.relatedTarget);
  $("#id_user_color").val(button.data('id'));
})

$(document).on("hide.bs.modal", "#modal_usuario", function (event) {
  setTimeout(() => {
    $("#pass_repeat").val('')
    $('#n_pass').val('')
    $("#pass").val('')
    $("#pass_repeat").removeClass('is-valid');
    $("#pass_repeat").removeClass('is-invalid');
    $("#btn_cambiar").prop("disabled", false);
  }, 900);
})

const cambiarPass = async () => {
  console.log('asdasdfasdfadsfadsfadsfdsadssd')
  if ($("#pass_repeat").val() == $('#n_pass').val() && $("#pass").val() != '') {
    data = {
      idUsuario: $("#id_user").val(),
      pass: $("#pass").val(),
      newPass: $("#n_pass").val()
    }
    console.log(data)
    const res = await $.ajax({
      data,
      url: "../app/usuario/changepass",
      type: "POST",
      dataType: "JSON",
    })
    if (res.status == 'success') {
      $.toast({
        heading: 'Operación exitosa',
        icon: 'success',
        position: 'top-right',
        hideAfter: 1900
      })
    } else {
      $.toast({
        heading: res.message,
        icon: 'error',
        position: 'top-right',
        hideAfter: 1900
      })
    }
    console.log(data)
  } else {
    console.log($("#pass_repeat").val())
    console.log($('#n_pass').val())
    console.log($("#pass").val())
    $("#btn_cambiar").prop("disabled", true);
    $.toast({
      heading: 'Nueva contraseña diferentes',
      icon: 'warning',
      position: 'top-right',
      hideAfter: 1600
    })
  }
}
const showPass = (curr) => {
  if ($(curr).data('visible') == 'true') {
    $(curr).data('visible', 'false');
    const idInput = $(curr).data('obj');
    $('#' + idInput).attr('type', 'password');
    $(curr).html('<i class="fas fa-eye"></i>');
  } else {
    $(curr).data('visible', 'true');
    const idInput = $(curr).data('obj');
    $('#' + idInput).attr('type', 'text');
    $(curr).html('<i class="fas fa-eye-slash"></i>');
  }
}

$(document).on('input', '#color_menu', (e) => {
  $('#top_nav').css('background-color', e.target.value);
  $('#sidenavAccordion').css('background-color', e.target.value);
  $(".sb-sidenav-footer").css('background-color', e.target.value)
})

const lenguaje = {
  processing: "Procesando...",
  search: "Buscar en la tabla",
  lengthMenu: "Mostrar  _MENU_ filas ",
  paginate: {
    first: "Primero",
    previous: "Ant.",
    next: "Sig.",
    last: "Último",
  },
  emptyTable: "No hay registros...",
  infoEmpty: "No hay resultados",
  zeroRecords: "No hay registros...",
};

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

async function cambiarColor() {
  const color = $('#color_menu').val();
  const idUsuario = $("#id_user_color").val();
  const data = {
    color, idUsuario
  }

  const res = await $.ajax({
    url: '../app/usuario/changecolor',
    data,
    type: "POST",
    dataType: "JSON",
  })
  if (res.status == 'success') {
    $.toast({
      heading: 'Cambio realizado',
      icon: 'success',
      position: 'top-right',
      hideAfter: 1900
    });
    localStorage.setItem('color', color);
    console.log('cambio de color: ', color, localStorage.color)
  } else {
    $.toast({
      heading: 'Ocurrió un error',
      icon: 'danger',
      position: 'top-right',
      hideAfter: 1900
    })
  }
}

function toast(title, text, icon = 'success', time = 1500) {
  $.toast({
    heading: title,
    text,
    icon,
    position: 'top-right',
    hideAfter: time
  })
}