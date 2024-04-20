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
  <title>Registro </title>
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
            <div class="col-lg-7">
              <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                  <h3 class="text-center font-weight-light my-4">Crear Cuenta</h3>
                </div>
                <div class="card-body">
                  <form id="form_register">
                    <div class="row mb-3">
                      <div class="col-md-12">
                        <div class="form-floating mb-3 mb-md-0">
                          <input class="form-control" id="inputFirstName" type="text" placeholder="Enter your first name" name="alias" />
                          <label for="inputFirstName">Usuario o Alias</label>
                        </div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                          <input class="form-control" id="inputPassword" type="password" placeholder="Crea contraseña" name="password" />
                          <label for="inputPassword">Contraseña</label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-floating mb-3 mb-md-0">
                          <input class="form-control" id="inputPasswordConfirm" type="password" placeholder="Confirmar Contraseña" />
                          <label for="inputPasswordConfirm">Confirma contraseña</label>
                        </div>
                      </div>
                    </div>
                    <div class="mt-4 mb-0">
                      <div class="d-grid"><button type="button" class="btn btn-primary btn-block" onclick="register()">Crear Cuenta</button></div>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center py-3">
                  <div class="small"><a href="login.php">¿Ya tienes cuenta? Ingresar</a></div>
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
    async function register() {
      const data = $("#form_register").serialize();
      console.log(data)
      const res = await $.ajax({
        url: '../app/cusuario/create',
        type: 'POST',
        data,
        dataType: 'json',
      })
      if (res.status == 'success') {
        $.toast({
          heading: 'Registro exitoso',
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
          heading: 'Error',
          text: 'Ocurrió un error',
          showHideTransition: 'slide',
          icon: 'error'
        })
      }
    }
    $("#inputPasswordConfirm").on('input', () => {
      const password = $("#inputPassword").val()
      const passwordConfirm = $("#inputPasswordConfirm").val()
      if (password === passwordConfirm) {
        $("#inputPasswordConfirm").removeClass("is-invalid")
        $("#inputPasswordConfirm").addClass("is-valid")
      } else {
        $("#inputPasswordConfirm").removeClass("is-valid")
        $("#inputPasswordConfirm").addClass("is-invalid")
      }
    })
  </script>
</body>

</html>