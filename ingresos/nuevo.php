<?php
date_default_timezone_set('America/La_Paz');
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
}
require_once('../app/models/proyecto.php');
require_once('../app/config/database.php');
require_once('../app/models/lugar.php');

use App\Models\Proyecto;
use App\Models\Lugar;

$idProyecto = "0";
$detalle = "";
if (isset($_GET['proid'])) {
  $proyecto = new Proyecto($_GET['proid']);
  if ($proyecto->idProyecto != 0) {
    $detalle = strtoupper($proyecto->proyecto);
    $idProyecto = $_GET['proid'];
  }
}
$lugares = Lugar::all();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>EGRESOS - PAGO</title>
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
            <h1>Nuevo pago (ingreso)</h1>
          </div>
          <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-secondary" onclick="history.back()"><i class="fa fa-arrow-left"></i> Volver </button>
          </div>
          <div class="row" id="card-egresos">
            <form id="form_nuevo">
              <input type="hidden" id="type_file_upload" name="tipo_file" value="">
              <div class="card shadow">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tipo_detalle" placeholder="Proyecto" autocomplete="off" value="<?= $detalle ?>" required>
                        <label for="tipo_detalle">Proyecto</label>
                        <input type="hidden" name="idProyecto" id="idProyecto" value="<?= $idProyecto ?>">
                        <div id="suggestion_proy" class="suggestions"></div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Descripcion" name="concepto" required>
                        <label for="">Concepto</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="modoPago" class="form-select" required>
                          <option value="">-- SELECCIONE --</option>
                          <option value="EFECTIVO">EFECTIVO</option>
                          <option value="CHEQUE">CHEQUE</option>
                          <option value="DEPOSITO">DEPOSITO</option>
                          <option value="GIRO">GIRO</option>
                          <option value="TARJETA">TARJETA</option>
                          <option value="BANCO">BANCO</option>
                          <option value="QR">QR</option>
                        </select>
                        <label for="">Modo de pago</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="afiliado_to" placeholder="Usuario Origen" autocomplete="off" required>
                        <label for="afiliado_from">Pagado por:</label>
                        <input type="hidden" name="idAfiliado" value="0" id="idAfiliado">
                        <div id="suggestions_afiliado" class="suggestions"></div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Usuario destino" autocomplete="off" value="<?= strtoupper($user->alias) ?>" disabled>
                        <label for="">Recibido por:</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" placeholder="Monto del pago" name="monto" step="any" required>
                        <label for="">Monto</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="date" class="form-control" placeholder="fecha registro" value="<?= date('Y-m-d') ?>" disabled>
                        <label for="">Fecha de registro</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Nro Nota | Fact" value="" name="nro" autocomplete="off">
                        <label for="">Nro. Nota o Factura</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="lugar" class="form-select">
                          <?php foreach ($lugares as $lugar) : ?>
                            <option value="<?= $lugar['lugar'] ?>"><?= strtoupper($lugar['lugar']) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <label for="">Lugar de pago</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Id Venta" value="" name="referencia">
                        <label for="">ID Venta (opcional)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="adelanto" class="form-select">
                          <option value="PAGO" selected>PAGO</option>
                          <option value="ADELANTO">ADELANTO</option>
                        </select>
                        <label for="">Adelanto</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                      <h4 id="comprobante_pago_file">¿Agregar un comprobante de pago?</h4>
                      <button id="btn_file_upload" type="button" class="btn btn-primary shadow-lg" data-bs-toggle="modal" data-bs-target="#modal_egreso_comprobante"><i class="fa fa-solid fa-upload"></i></button>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success shadow">GUARDAR</button>
                  </div>
                </div>
              </div><!-- end card -->
            </form>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/draw.js"></script>
  <script src="./js/pago.js"></script>
  <script src="./js/handlers.js"></script>
</body>

</html>