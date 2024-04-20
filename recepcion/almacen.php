<?php
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
} ?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>ALMACEN</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
  <script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>
  <style>
    .check-llegada {
      transform: scale(1.5);
    }
  </style>
</head>

<body>
  <?php include("../common/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../common/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main id="main_egresos">
        <div class="container-fluid px-4">
          <div class="mt-4">
            <h1>Paquetes</h1>
          </div>
          <div class="row" id="card-egresos">
            <div class="card shadow">
              <div class="card-body">
                <div class="table-responsive">
                  <table style="width:100%" class="table table-hover" id="tabla_almacen">
                    <thead>
                      <tr>
                        <th class="text-center">N° ID</th>
                        <th class="text-center">Código</th>
                        <th class="text-center">Fecha envio</th>
                        <th class="text-center">Detalle</th>
                        <th class="text-center">Nombre C.I. Remitente</th>
                        <th class="text-center">En almacén</th>
                      </tr>
                    </thead>
                    <tbody id="t_body_almacen"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
      </main>
    </div>
  </div><!-- fin contenedor -->
  <?php include('modals.php'); ?>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/almacen.js"></script>
</body>

</html>