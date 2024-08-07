<?php
require_once "../helpers/middlewares/web_auth.php";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Envios</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body class="sb-nav-fixed">
  <?php // include('./modals.php');
  ?>
  <?php include("../partials/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../partials/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <div class="mt-4">
            <h2><i class="text-warning fa-solid fa-bell"></i> Envios en progreso</h2>
          </div>
          <!-- <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modal_usuario_nuevo"><i class="fa fa-user-plus"></i> Crear Nuevo Usuario</button>
          </div> -->
          <div class="row" id="cards-usuarios">
            <div class="card mb-4 shadow">

              <div class="card-body" id="progress_content"></div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->

  <?php include './modal_price.php'; ?>
  <?php include "./modal_details.php"; ?>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/progress.js"></script>
  <script src="./js/common_shipp.js"></script>
</body>

</html>