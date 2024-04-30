<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Casilleros</title>
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
  <div id="layoutSidenav">
    <?php include("../partials/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="d-flex justify-content-between my-4 flex-wrap">
            <h3>CASILLEROS</h3>
            <button class="btn text-white" style="--bs-btn-bg:var(--bs-blue);--btn-custom-bg-hover:var(--bs-complement);" type="button" data-bs-toggle="modal" data-bs-target="#modal_add_locker"><i class="fa-solid fa-circle-plus"></i> Nuevo casillero</button>
          </div>
          <!-- <div class="row mb-3">
            <div class="col-md-4">
              <div class="input-group">
                <input class="form-control" type="text" placeholder="Buscar" aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-secondary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </div> -->
          <div class="row">
            <div class="col-md-2 col-sm-3">
              <div class="card text-bg-secondary mb-3" style="max-width: 18rem;">
                <div class="card-header fw-bold text-center fs-5">Casillero Nº 1</div>
                <div class="card-body">
                  <p class="card-text"></p>
                </div>
                <div class="card-footer text-center">
                  <button type="button" class="btn btn-sm btn-primary text-white" title="EDITAR"><i class="fa fa-solid fa-pencil"></i></button>
                  <button type="button" class="btn btn-sm btn-danger text-white" title="ELIMINAR" data-bs-toggle="modal" data-bs-target="#modal_delete_locker" data-id=""><i class="fa fa-solid fa-trash"></i></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>


  <!-- MODAL ADD LOCKER -->
  <div class="modal fade" id="modal_add_locker" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Nuevo casillero</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary">Guardar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- MODAL DELETE LOCKER -->
  <div class="modal fade" id="modal_delete_locker" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h1 class="modal-title fs-5">¿Eliminar casillero <b id="delete_nro_locker">2</b>?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger text-white" onclick="delete_locker()">Eliminar</button>
        </div>
      </div>
    </div>
  </div>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
</body>

</html>