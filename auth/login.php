<?php
if (isset($_COOKIE['user_obj'])) {
  header('Location: ../');
  die();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login</title>
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
  <script src="../assets/fontawesome/fontawesome6.min.js" crossorigin="anonymous"></script>
</head>

<body class="bg-primary">
  <div id="layoutAuthentication">
    <div id="layoutAuthentication_content">
      <main>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Login</h3>
                </div>
                <div class="card-body">
                  <form id="form_login">
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputPin" type="text" placeholder="PIN" name="pin" required />
                      <label for="inputPin">PIN</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputEmail" type="text" placeholder="Usuario Alias" name="usuario" required />
                      <label for="inputEmail">Usuario</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputPassword" placeholder="contraseña" name="password" type="password" required />
                      <label for="inputPassword">Contraseña</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                      <button type="submit" class="btn btn-primary" id="btn_logg">Ingresar</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="../js/scripts.js"></script>
  <script>
    $(document).on('submit', '#form_login', async (e) => {
      e.preventDefault();
      const data = $("#form_login").serialize();
      const res = await $.ajax({
        url: '../app/usuario/login',
        type: 'POST',
        data: data,
        dataType: 'json'
      });
      if (res.status == 'success') {
        $("#btn_logg").attr('disabled', 'disabled')
        $.toast({
          heading: 'INGRESO CORRECTO',
          text: 'Redireccionando a la pagina principal',
          showHideTransition: 'slide',
          icon: 'success'
        });
        setTimeout(() => {
          window.location.href = '../';
        }, 1800)
      } else {
        console.warn(res)
        $.toast({
          heading: 'INGRESO ERRONEO',
          text: res.message,
          showHideTransition: 'slide',
          icon: 'error',
          hideAfter: 5000
        })
      }
    })
  </script>
</body>

</html>