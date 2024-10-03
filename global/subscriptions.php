<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Suscripciones</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body class="sb-nav-fixed">
  <?php include("../partials/header.php"); ?>
  <div id="layoutSidenav">
    <?php include("../partials/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h2 class="mt-4">SUSCRIPCIONES</h2>
          <div class="row" id="content"></div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal change subscription -->
  <div class="modal fade" id="modal_change_subscription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="form_change_subscription">
          <input type="hidden" name="idsub" id="id_sub_current" />
          <input type="hidden" name="key" id="key_sub_data" />
          <div class="modal-header">
            <h1 class="modal-title fs-5">Cambiar suscripción</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12 mb-2">
                <div class="form-floating">
                  <select class="form-select" name="type" id="option_type_sub"></select>
                  <label for="option">Tipo de suscripción</label>
                </div>
              </div>
              <div class="text-muted fw-semibold">Precios</div>
              <div class="col-md-4 mb-2">
                <div class="form-floating">
                  <input class="form-control" placeholder="Precio" type="number" id="current_price" disabled />
                  <label for="current_price">Actual</label>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <div class="form-floating">
                  <input class="form-control" placeholder="Precio" type="number" id="current_price_new" disabled />
                  <label for="current_price">Nuevo</label>
                </div>
              </div>
              <div class="col-md-4 mb-2">
                <div class="form-floating">
                  <input class="form-control" placeholder="Precio" type="number" id="price_to_add" disabled />
                  <label for="price_to_add">Diferencia</label>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal add subscription -->
  <div class="modal fade" id="modal_add_subscription" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Agregar una suscripción</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="content_add_sub"></div>
      </div>
    </div>
  </div>
  <!-- Modal suspend subscription -->
  <div class="modal fade" id="modal_suspend" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Suspender suscripción</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" value="" id="subscription_id_suspend" />
          <input type="hidden" value="" id="data_key_subscription" />
          <div class="alert alert-warning" role="alert">
            ¿Esta seguro de suspender está suscripción?
            <br>
            Suscripción del departamento <span class="pl-1 fw-semibold" id="dep_sub_suspend"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="supendSub()" class="btn btn-primary text-white" data-bs-dismiss="modal"><i class="fa-lg fa-solid fa-floppy-disk"></i> Sí, suspender</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/subscriptions.js"></script>
</body>

</html>