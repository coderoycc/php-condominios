<?php

use App\Config\Database;
use App\Models\Subscriptiontype;

require_once(__DIR__ . "/../helpers/middlewares/web_auth.php");
require_once("../app/config/database.php");
require_once("../app/models/subscriptiontype.php");

$con = Database::getInstanceByPinExterno($condominio->pin);
$types = Subscriptiontype::getTypes(null, $con);
// var_dump($types);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Reportes</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/charjs/Chart.min.css" />

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
          <div class="d-flex justify-content-between mt-4 flex-wrap">
            <h2 style="color:var(--bs-verde);"><i class="fa-solid fa-chart-pie"></i> Reportes de suscripciones</h2>
            <div class="d-flex gap-2">
              <div class="form-floating">
                <input type="date" class="form-control" id="date_inicio" value="<?= date('Y') . "-01-01" ?>" placeholder="Inicio fecha">
                <label for="date_inicio">Fecha inicio</label>
              </div>
              <div class="form-floating">
                <input type="date" class="form-control" id="date_final" placeholder="Final fecha" value="<?= date('Y') . "-12-31" ?>">
                <label for="date_final">Fecha final</label>
              </div>
              <button type="button" class="btn btn-info text-white" onclick="load_report()"><i class="fa-solid fa-rotate-right"></i></button>
            </div>
          </div>
          <div class="row mt-2">
            <ul class="nav nav-tabs justify-content-center fs-5" id="tab_names" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link tabs_reports active" id="home-tab" data-bs-toggle="tab" data-bs-target="#tab_general" type="button" role="tab" aria-controls="tab_general" aria-selected="true" data-id="0">Todos</button>
              </li>
              <?php foreach ($types as $type) : ?>
                <li class="nav-item" role="presentation">
                  <button class="nav-link tabs_reports" id="tab-<?= $type->id_subscription_type ?>" data-bs-toggle="tab" data-bs-target="#tab_<?= strtolower($type->name) ?>" type="button" role="tab" aria-controls="tab_" aria-selected="false" data-id="<?= $type->id_subscription_type ?>"><?= $type->name ?></button>
                </li>
              <?php endforeach; ?>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade show active" id="tab_name" role="tabpanel" tabindex="0">

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
  <script src="../assets/charjs/Chart.min.js"></script>
  <script src="./js/reports.js"></script>
</body>

</html>