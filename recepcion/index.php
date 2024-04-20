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
  <title>RECEPCION</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
  <script src="../assets/sweetalert2/sweetalert2.all.min.js"></script>
</head>

<body>
  <?php include('./modals.php'); ?>
  <?php include("../common/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../common/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main id="main_egresos">
        <div class="container-fluid px-4">
          <div class="mt-4">
            <h1>Recepción</h1>
          </div>
          <!-- <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal_egreso_nuevo" data-idproyecto="0"><i class="fa fa-plus"></i> Nuevo Proyecto </button>
            <a class="btn btn-primary" type="button" href="./nuevo.php"><i class="fa fa-solid fa-hand-holding-dollar"></i> Nuevo pago</a>
          </div> -->
          <div class="row" id="card-egresos">
            <div class="card shadow">
              <div class="card-header d-flex flex-wrap justify-content-between">
                <div class="btn-group" role="group" aria-label="Basic example">
                  <button type="button" class="btn btn-outline-success fw-bold filter-btns" data-value="EN ALMACEN">EN ALMACEN</button>
                  <button type="button" class="btn btn-outline-warning fw-bold filter-btns" data-value="ENVIADO">PENDIENTES</button>
                  <button type="button" class="btn btn-outline-primary fw-bold active filter-btns" data-value="">TODOS</button>
                </div>
                <div>
                  <!-- <select class="form-select" id="filter_year">
                    <?php for ($i = 0; $i < 5; $i++) : ?>
                      <option value="<?= date('Y', strtotime('-' . $i . ' year')) ?>"><?= date('Y', strtotime('-' . $i . ' year')) ?></option>
                    <?php endfor; ?>
                  </select> -->
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table style="width:100%" class="table table-hover" id="table_recepcion">
                    <thead>
                      <tr>
                        <th class="text-center">N° ID</th>
                        <th class="text-center">Código</th>
                        <th class="text-center">Fecha envio</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Detalle</th>
                        <th class="text-center">Fecha llegada</th>
                        <th class="text-center">Nombre receptor</th>
                        <th class="text-center">C.I. receptor</th>
                        <th class="text-center">Celular receptor</th>
                        <th class="text-center">Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="t_body_recepcion">

                    </tbody>
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