<?php
require_once('../helpers/middlewares/web_login.php');
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Login</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
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
                      <input class="form-control" id="inputEmail" type="text" placeholder="Usuario Alias" name="user" required />
                      <label for="inputEmail">Usuario</label>
                    </div>
                    <div class="form-floating mb-3">
                      <input class="form-control" id="inputPassword" placeholder="contraseña" name="password" type="password" required />
                      <label for="inputPassword">Contraseña</label>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                      <button type="submit" class="btn btn-primary text-white" id="btn_logg">Ingresar</button>
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
  <script>
    $(document).on('submit', '#form_login', async (e) => {
      e.preventDefault();
      const data = $("#form_login").serialize();
      try {
        const res = await $.ajax({
          url: '../app/auth/login_web',
          type: 'POST',
          data: data,
          dataType: 'json'
        });
        if (res.success) {
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
        }
      } catch (error) {
        const resJson = error.responseJSON;
        // console.log(error.responseJSON)
        $.toast({
          heading: 'INGRESO ERRONEO',
          text: resJson.message,
          showHideTransition: 'slide',
          icon: 'error',
          hideAfter: 6000
        })
      }
    })
  </script>
</body>

</html>