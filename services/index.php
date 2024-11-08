<?php
require_once("../helpers/middlewares/web_auth.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Servicios </title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<?php include("../partials/header.php"); ?>
<div id="layoutSidenav"> <!-- contenedor -->
  <?php include("../partials/sidebar.php"); ?>
  <div id="layoutSidenav_content">
    <main>
      <div class="container-fluid px-4">
        <div class="row mt-2">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted text-uppercase" style="font-size:22px;" id="type_list">Suscripciones habilitadas para servicios</div>
                <div class="gap-3">
                  <a href="./?req=add" type="button" class="btn btn-outline-dark btn-menu" id="btn-add">Registrar pagos</a>
                  <a href="./?req=view" type="button" class="btn btn-outline-info btn-menu" id="btn-view">Últimos pagos Registrados</a>
                  <a href="./?req=history" type="button" class="btn btn-outline-success btn-menu" id="btn-history">Historial</a>
                  <a href="./?req=topay" type="button" class="btn btn-outline-danger btn-menu" id="btn-topay">Para pagar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row" id="table_services"></div>
      </div>
    </main>
  </div>
</div><!-- fin contenedor -->

<!-- Modal add payments -->
<div class="modal fade" id="modal_register_payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="data_add_amounts"></div>
  </div>
</div>
<!-- Modal update payments -->
<div class="modal fade" id="modal_edit_payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="data_edit_amounts"></div>
  </div>
</div>

<!-- Modal detail payments -->
<div class="modal fade" id="modal_detail_payment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="data_datail_amounts"></div>
  </div>
</div>
<!-- Modal pay  -->
<div class="modal fade" id="modal_pay_voucher" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="data_pay_amounts"></div>
  </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../js/scripts.js"></script>
<script src="../assets/datatables/datatables.jquery.min.js"></script>
<script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
<script src="./js/app.js"></script>
</body>

</html>