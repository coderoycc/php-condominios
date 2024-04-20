<?php
date_default_timezone_set('America/La_Paz');
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
}
require_once('../app/config/database.php');
require_once('../app/models/pago.php');
require_once('../app/models/proyecto.php');

use App\Models\Pago;
use App\Models\Proyecto;

$domain = 'domanin';
if (isset($_GET['pid'])) {
  $pid = $_GET['pid'];
  $pago = new Pago($pid);
  $proyecto = new Proyecto($pago->idProyecto);
}
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
            <h1>Editar Pago</h1>
          </div>
          <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" class="btn btn-secondary" onclick="history.back()"><i class="fa fa-arrow-left"></i> Volver </button>
          </div>
          <div class="row" id="card-egresos">
            <form id="form_update" data-idpago="<?= $pid ?>">
              <input type="hidden" id="type_file_upload" name="tipo_file" value="<?= $pago->nameFile == '' ? '' : 'file' ?>">
              <div class="card shadow">
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="tipo_detalle" placeholder="Tipo de detalle" value="<?= $proyecto->proyecto ?>" disabled>
                        <label for="tipo_detalle">Tipo de detalle</label>
                        <input type="hidden" name="idProyecto" value="<?= $proyecto->idProyecto ?>">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Descripcion" name="concepto" value="<?= $pago->concepto ?>" required>
                        <label for="">Concepto</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="modoPago" class="form-select" required>
                          <option value="">-- SELECCIONE --</option>
                          <option value="EFECTIVO" <?= $pago->modoPago == 'EFECTIVO' ? 'selected' : '' ?>>EFECTIVO</option>
                          <option value="CHEQUE" <?= $pago->modoPago == 'CHEQUE' ? 'selected' : '' ?>>CHEQUE</option>
                          <option value="DEPOSITO" <?= $pago->modoPago == 'DEPOSITO' ? 'selected' : '' ?>>DEPOSITO</option>
                          <option value="GIRO" <?= $pago->modoPago == 'GIRO' ? 'selected' : '' ?>>GIRO</option>
                          <option value="TARJETA" <?= $pago->modoPago == 'TARJETA' ? 'selected' : '' ?>>TARJETA</option>
                          <option value="BANCO" <?= $pago->modoPago == 'BANCO' ? 'selected' : '' ?>>BANCO</option>
                          <option value="QR" <?= $pago->modoPago == 'QR' ? 'selected' : '' ?>>QR</option>
                        </select>
                        <label for="">Modo de pago</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="afiliado_to" placeholder="Usuario" value="<?= strtoupper($pago->pagadoPorIngreso()['nombre']) ?>">
                        <label for="">Pagado por:</label>
                        <input type="hidden" name="idAfiliado" value="<?= $pago->idPagadoPor ?>" id="idAfiliado">
                        <div id="suggestions_afiliado" class="suggestions"></div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Usuario destino" autocomplete="off" value="<?= strtoupper($pago->recibidoPorIngreso()['alias']) ?>" disabled>
                        <label for="afiliado_to">Recibido por:</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="number" class="form-control" placeholder="Monto del pago" name="monto" step="any" value="<?= $pago->monto ?>" required>
                        <label for="">Monto</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Nro Nota | Fact" value="<?= $pago->nroNotaFact ?>" name="nro" autocomplete="off">
                        <label for="">Nro. Nota o Factura</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="date" class="form-control" placeholder="fecha registro" value="<?= date('Y-m-d', strtotime($pago->fechaRegistro)) ?>" disabled>
                        <label for="">Fecha de registro</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="lugar" class="form-select">
                          <?php foreach ($lugares as $lugar) : ?>
                            <option value="<?= $lugar['lugar'] ?>" <?= $pago->lugar == $lugar['lugar'] ? 'selected' : '' ?>><?= strtoupper($lugar['lugar']) ?></option>
                          <?php endforeach; ?>
                        </select>
                        <label for="">Lugar de pago</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <input type="text" class="form-control" placeholder="Id Venta" value="<?= $pago->referencia ?>" name="referencia">
                        <label for="">ID Venta (opcional)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-floating mb-3">
                        <select name="adelanto" class="form-select">
                          <option value="PAGO" <?= $pago->adelanto == 'PAGO' ? 'selected' : '' ?>>PAGO</option>
                          <option value="ADELANTO" <?= $pago->adelanto == 'ADELANTO' ? 'selected' : '' ?>>ADELANTO</option>
                        </select>
                        <label for="">Adelanto</label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="d-flex justify-content-center flex-wrap gap-3">
                      <?php if ($pago->nameFile == '') : ?>
                        <h4 id="comprobante_pago_file">Agregar comprobante</h4>
                        <button id="btn_file_upload" type="button" class="btn btn-primary shadow-lg" data-bs-toggle="modal" data-bs-target="#modal_egreso_comprobante"><i class="fa fa-solid fa-upload"></i></button>
                      <?php else : ?>
                        <h4 id="comprobante_pago_file">Ver comprobante</h4>
                        <button type="button" class="btn btn-warning shadow-lg" data-bs-toggle="modal" data-bs-target="#modal_ver_comprobante" data-namefile="<?= $pago->nameFile ?>" data-idpago="<?= $pago->idPago ?>"><i class="fa fa-solid fa-eye"></i></button>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-success shadow">ACTUALIZAR</button>
                  </div>
                </div>
              </div><!-- end card -->
            </form>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->
  <?php include('./modal_vercomprobante.php'); ?>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/draw.js"></script>
  <script src="./js/pago.js"></script>
  <script src="./js/handlers.js"></script>
  <script src="./js/edit.js"></script>
</body>

</html>