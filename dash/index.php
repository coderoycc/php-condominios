<?php
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>DASHBOARD</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body>
  <?php include("../common/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../common/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid px-4">
          <h1 class="mt-4">INICIO</h1>
          <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Menu de inicio</li>
          </ol>

          <div class="row">
            <?php if ($user->rol != 'ALMACEN') : ?>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4 shadow">
                  <div class="card-body">
                    <h4><i class="fa fa-plus"></i> Nuevo Envio</h4>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="../envios/nuevo.php">Nuevo envio</a>
                    <div class="small text-white"><i class="fa fa-angle-right"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4 shadow">
                  <div class="card-body">
                    <h4><i class="fa fa-eye"></i> Mis envios</h4>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="../envios/">Envios</a>
                    <div class="small text-white"><i class="fa fa-angle-right"></i></div>
                  </div>
                </div>
              </div>
              <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4 shadow">
                  <div class="card-body">
                    <h4><i class="fa fa-solid fa-dolly"></i> Recepción</h4>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="../recepcion/">Lista</a>
                    <div class="text-white"><i class="fa fa-angle-right"></i></div>
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="col-xl-3 col-md-6">
                <div style="background-color:var(--bs-teal);" class="card text-white mb-4 shadow">
                  <div class="card-body">
                    <h4><i class="fa fa-solid fa-dolly"></i> Ver Almacén</h4>
                  </div>
                  <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="text-white stretched-link" href="../recepcion/almacen.php">Almacén</a>
                    <div class="small text-white"><i class="fa fa-angle-right"></i></div>
                  </div>
                </div>
              </div>
            <?php endif; ?>
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