<?php
require_once("../helpers/middlewares/web_auth.php");

require_once('../app/config/accesos.php');
require_once('../app/config/database.php');

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Usuarios</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body>
  <?php include('./modals.php');
  ?>
  <?php include("../partials/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../partials/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="mt-4">
            <h1>Usuarios del sistema</h1>
          </div>
          <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modal_usuario_nuevo"><i class="fa fa-user-plus"></i> Crear Nuevo Usuario</button>
          </div>
          <div class="row" id="cards-usuarios">
            <div class="card mb-4 shadow">
              <div class="card-header">
                <h4>
                  <i class="fas fa-table me-1"></i>
                  Lista de usuarios
                </h4>
              </div>
              <div class="card-body">

                <table style="width:100%" class="table table-striped" id="table_usuarios">
                  <thead>
                    <tr>
                      <th class="text-center">ID</th>
                      <th class="text-center">NOMBRE</th>
                      <th class="text-center">USUARIO</th>
                      <th class="text-center">ROL</th>
                      <th class="text-center">ACCIONES</th>
                    </tr>
                  </thead>
                  <tbody id="tbl_users"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
</body>

</html>