<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Departamentos</title>
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
          <div class="d-flex justify-content-between mt-4 flex-wrap">
            <h3>DEPARTAMENTOS</h3>
            <button class="btn text-white" style="--bs-btn-bg:var(--bs-blue);--btn-custom-bg-hover:var(--bs-complement);" data-bs-toggle="modal" data-bs-target="#depa_add"><i class="fa-solid fa-circle-plus"></i> Nuevo departamento</button>
          </div>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Lista</li>
          </ol>
          <div class="row" id="content_department"></div>
        </div>
      </main>
    </div>
  </div>

  <!-- MODAL ELIMINAR -->
  <div class="modal fade" id="depa_delete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h1 class="modal-title text-white fs-5">¿DAR DE <b id="message_title"></b> AL DEPARTAMENTO?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <input type="hidden" id="id_depa_delete">
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCELAR</button>
          <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal" onclick="delete_department()">SI</button>
        </div>
      </div>
    </div>
  </div>


  <!-- MODAL EDITAR -->
  <div class="modal fade" id="depa_edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h1 class="modal-title text-white fs-5">EDITAR DEPARTAMENTO</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal_content_edit"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal" onclick="update_department()">Actualizar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL AGREGAR -->
  <div class="modal fade" id="depa_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h1 class="modal-title text-white fs-5">AGREGAR NUEVO DEPARTAMENTO</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="form_add_department" onsubmit="return false;">
            <div class="row">
              <div class="col-md-6 mb-2">
                <div class="form-floating">
                  <input type="text" class="form-control" id="add_dep_number" name="depa_num" placeholder="Nro departamento">
                  <label for="add_dep_number">Nro. Departamento</label>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <div class="form-floating">
                  <input type="number" class="form-control" id="add_dep_bedrooms" name="bedrooms" placeholder="Habitaciones">
                  <label for="add_dep_bedrooms">Nro. de habitaciones</label>
                </div>
              </div>
              <div class="col-md-12 mb-2">
                <div class="form-floating">
                  <textarea class="form-control" id="add_dep_desc" name="descrip" placeholder="Descripcion" style="height:100px;resize:none;"></textarea>
                  <label for="add_dep_bedrooms">Descripción</label>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal" onclick="add_depa()">Agregar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL SUSCRIPCIONES -->
  <div class="modal fade" id="subscription_depa" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h1 class="modal-title text-white fs-5">Suscripciones de este departamento</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="subs_depa_content"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </div>
      </div>
    </div>
  </div>


  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
</body>

</html>