<?php
require_once("../helpers/middlewares/web_auth.php");
$ciudades = ['LA PAZ', 'COCHABAMBA', 'SANTA CRUZ', 'ORURO', 'PANDO'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>DASHBOARD</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body class="sb-nav-fixed">
  <?php include("../partials/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../partials/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="d-flex justify-content-between mt-4">
            <h3>ADMINISTRACIÓN DE CONDOMINIOS</h3>
            <div>
              <button class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#nuevo_condominio">Nuevo condominio</button>
              <button class="btn btn-info text-white">Configuraciones</button>
            </div>
          </div>
          <div class="row mt-3" id="condominios_content"></div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->


  <!-- Agregar nuevo condominio -->
  <div class="modal fade" id="nuevo_condominio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="form_new_condominius">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar nuevo condominio</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" class="form-control" name="name" placeholder="Nombre" required>
                  <label for="name">Nombre del condominio</label>
                  <div id="name_feed" class="invalid-feedback">
                    Please provide a valid city.
                  </div>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-floating">
                  <input type="text" class="form-control" name="pin" placeholder="pin" required>
                  <label for="floatingPassword">PIN, identificador</label>
                  <div id="pin_feed" class="invalid-feedback">
                    Please provide a valid city.
                  </div>
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <div class="form-floating">
                  <input type="text" class="form-control" name="address" placeholder="Direccion" required>
                  <label for="address">Dirección</label>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="form-floating">
                  <select name="city" class="form-select" required>
                    <option value="" selected>Seleccionar</option>
                    <?php foreach ($ciudades as $ciudad) : ?>
                      <option value="<?= $ciudad ?>"><?= $ciudad ?></option>
                    <?php endforeach; ?>
                  </select>
                  <label for="phone">Ciudad</label>
                </div>
              </div>
              <div class="col-md-6 mb-3 d-flex justify-content-center align-items-center">
                <div class="form-check form-switch">
                  <input class="form-check-input" name="qr" type="checkbox" role="switch" id="enable-qr-check" checked>
                  <label class="form-check-label" for="enable-qr-check">Habilitar pagos QR</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary text-white">Crear</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Editar nuevo condominio -->
  <div class="modal fade" id="editar_condominio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <form id="form_edit_condominius">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5">Editar condominio</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="modal_body_edit"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary text-white">Actualizar</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
</body>

</html>