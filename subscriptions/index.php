<?php
require_once("../helpers/middlewares/web_auth.php");
require_once("../app/models/department.php");
require_once("../app/config/database.php");

use App\Config\Database;
use App\Models\Department;

$con = Database::getInstanceByPinExterno($condominio->pin);
$dptos = Department::get_with_subs($con, []);
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Suscripciones</title>
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
          <div class="d-flex justify-content-between my-4 flex-wrap">
            <h3> <i class="fa-solid fa-users-between-lines"></i> Departamentos y sus suscripciones</h3>
          </div>
          <div class="row">
            <div class="col-md-4">
              <table class="table table-striped" id="table_dptos" style="width:100%">
                <thead>
                  <tr class="center">
                    <th>ID</th>
                    <th>Nro. Depto.</th>
                    <th>Suscripción</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dptos as $dpto): ?>
                    <tr>
                      <td><?= $dpto['id_department'] ?></td>
                      <td><?= $dpto['dep_number'] ?></td>
                      <td>
                        <button type="button" class="btn btn-outline-info btn-subs-history" title="Historial" data-depa="<?= $dpto['id_department'] ?>"><i class="fa-fw fa-solid fa-clock-rotate-left"></i> Historial</button>
                        <?php if ($dpto['id_subscription']): ?>
                          <button type="button" class="btn btn-outline-primary btn-subs-current" data-idsub="<?= $dpto['id_subscription'] ?>" title="Suscripcion"><i class="fa-fw fa-solid fa-check"></i></button>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <div class="col-md-8" id="content_depa_sub"></div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
</body>

</html>