<?php
require_once("../helpers/middlewares/web_auth.php");
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
          <h1 class="mt-4">INICIO</h1>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Menu de inicio</li>
          </ol>

          <div class="row">

            <div class="col-xl-3 col-md-6">
              <div class="card bg-primary text-white mb-4 shadow">
                <div class="card-body">
                  <h4><i class="fa fa-users"></i> Ver a residentes</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="text-white stretched-link" href="../global/residents.php">Ver</a>
                  <div class="small text-white"><i class="fa fa-angle-right"></i></div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card bg-info text-white mb-4 shadow">
                <div class="card-body">
                  <h4><i class="fa fa-eye"></i> Ver Suscripciones</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="text-white stretched-link" href="../global/subscriptions.php">Ir</a>
                  <div class="small text-white"><i class="fa fa-angle-right"></i></div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6">
              <div class="card bg-success text-white mb-4 shadow">
                <div class="card-body">
                  <h4><i class="fa fa-solid fa-dolly"></i> Servicios a pagar</h4>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                  <a class="small text-white stretched-link" href="../services/?req=topay">Ver lista</a>
                  <div class="text-white"><i class="fa fa-angle-right"></i></div>
                </div>
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
</body>

</html>