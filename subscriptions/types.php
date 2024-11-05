<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Planes</title>
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
            <h3> <i class="fa-solid fa-comments-dollar"></i> Planes de suscripción</h3>
            <!-- <button class="btn text-white" style="--bs-btn-bg:var(--bs-blue);--btn-custom-bg-hover:var(--bs-complement);" type="button" data-bs-toggle="modal" data-bs-target="#modal_add_type"><i class="fa-solid fa-circle-plus"></i> Nuevo plan</button> -->
          </div>
          <div class="row" id="list_subscription_types"></div>
        </div>
      </main>
    </div>
  </div>

  <!-- MODAL ADD Type -->
  <div class="modal fade" id="modal_add_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Nuevo plan</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="add_type_form" onsubmit="return false;">
          <div class="modal-body">
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="tag_add" name="tag" placeholder="Texto etiqueta">
              <label for="tag_add">Etiqueta | Nombre</label>
            </div>
            <div class="form-floating mb-3">
              <input type="number" class="form-control" id="precio_add" name="precio" placeholder="numero" step="any" min="0">
              <label for="precio_add">Precio</label>
            </div>
            <div class="form-floating mb-3">
              <textarea class="form-control" placeholder="numero" name="descrip" style="height:120px;resize:none"></textarea>
              <label for="tag_add">Descripción</label>
            </div>
            <div class="d-flex justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="CASILLERO" id="ver_servicios">
                <label class="form-check-label" for="ver_servicios">
                  Ver servicios
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="PERFIL" id="ver_casillero" checked>
                <label class="form-check-label" for="ver_casillero">
                  Ver casilleros
                </label>
              </div>
            </div>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="addType()">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL EDIT TYPE -->
  <div class="modal fade" id="modal_edit_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5">Editar Tipo de suscripción</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div id="content_edit"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="update()">Guardar</button>
        </div>
      </div>
    </div>
  </div>


  <!-- MODAL DELETE TYPE -->
  <div class="modal fade" id="modal_delete_type" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-seconary ">
          <input type="hidden" id="delete_type_id">
          <h1 class="modal-title fs-5">¿Dar de baja/alta a la suscripción <b id="delete_type"></b>?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-footer d-flex justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success text-white" data-bs-dismiss="modal" onclick="down_up()">SI</button>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/types.js"></script>
</body>

</html>