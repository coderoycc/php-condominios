<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Publicidad</title>
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
          <div class="d-flex justify-content-between my-4 flex-wrap">
            <h3>Publicidad</h3>
            <div class="d-flex justify-content-between gap-3">
              <button class="btn btn-info text-white" type="button" id="btn_new_add"><i class="fa-solid fa-circle-plus"></i> Nueva publicidad</button>
              <button class="btn btn-success text-white" type="button" data-bs-toggle="modal" data-bs-target="#modal_add_ad"><i class="fa-solid fa-circle-plus"></i> Nueva publicitador</button>
            </div>
          </div>

          <div class="row" id="ad_content">
            <div class="d-flex align-items-start flex-md-row flex-column">
              <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link ads active" data-type="publicidad" data-bs-toggle="pill" type="button" role="tab" aria-selected="true">Publicidades</button>
                <button class="nav-link ads" data-type="publicitador" data-bs-toggle="pill" type="button" role="tab" aria-selected="false">Publicitadores</button>
              </div>
              <div class="tab-content w-100" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="content_tabs" role="tabpanel" tabindex="0"></div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->


  <!-- Modal add name service -->
  <div class="modal fade" id="modal_add_ad" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Nuevo publicitador</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="name_service" placeholder="Nombre">
              <label for="name_service">Nombre servicio</label>
            </div>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="acronym_service" placeholder="Acronimo">
              <label for="acronimo">Acrónimo</label>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="add_service_name()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal edit name service -->
  <div class="modal fade" id="service_edit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Editar nombre de servicio</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="id_service_edit">
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name_edit" placeholder="Nombre">
            <label for="name_service">Nombre servicio</label>
          </div>
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="acronimo_edit" placeholder="Acronimo">
            <label for="acronimo">Acrónimo</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update_nameservice()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal edit name service -->
  <div class="modal fade" id="service_delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h1 class="modal-title fs-5">¿Eliminar Servicio?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <input type="hidden" id="id_service_delete">
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary " data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" onclick="delete_nameservice()">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/ads.js"></script>
</body>

</html>