<?php
date_default_timezone_set('America/La_Paz');
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
}
require_once('../app/models/lugar.php');
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');

use App\Models\Lugar;

$lugares = Lugar::all();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Nuevo envio</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/jquery/jqueryValidationEngine.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
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
            <h1>Nuevo envio</h1>
          </div>
          <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <button type="button" id="btn_volver_page" class="btn btn-secondary" onclick="history.back()"><i class="fa fa-arrow-left"></i> Volver </button>
          </div>
          <div class="row" id="card-egresos">
            <form id="form_nuevo">
              <input type="hidden" name="id_usuario_envio" value="<?= $user->idUsuario ?>">
              <div class="card shadow">
                <div class="card-body">
                  <div class="collapse multi-collapse show" id="collapse_nuevo_1">
                    <div class="row">
                      <p class="fs-4 fw-bold"><i class="fa fa-solid fa-boxes-packing"></i> Datos remitente</p>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[required,maxSize[60]]" id="nombre_remitente" value="" placeholder="Remitente" name="nombre_origen" autocomplete="off">
                          <label for="nombre_remitente">Nombre remitente</label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[required,maxSize[12]]" id="ci_remitente" value="" name="ci_origen" placeholder="Carnet de identidad" autocomplete="off">
                          <label for="ci_remitente">C.I. / NIT. remitente</label>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[custom[celular]]" placeholder="Celular remitente" name="celular_origen" value="">
                          <label for="">Celular (opcional)</label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <input type="hidden" name="origen" value="<?= $user->idLugar ?>">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control" value="<?= $user->lugar ?>" title="<?= $user->lugar ?>" disabled>
                          <label for="">Lugar Origen</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <textarea class="form-control validate[required,maxSize[250]]" placeholder="Observacion" name="detalle_envio" style="height:135px;resize:none;"></textarea>
                          <label for="">Detalle envio</label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-floating mb-2">
                          <input type="number" name="cantidad" value="" step="1" placeholder="Cantidad" class="form-control validate[required]">
                          <label for="cantidad">Cantidad</label>
                        </div>
                        <div class="form-floating mb-3">
                          <input type="number" step="any" name="costo" placeholder="Costo envio" class="form-control validate[required]" value="0">
                          <label for="">Costo envio (Bs.) </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-floating mb-2">
                          <select name="pagado" class="form-select">
                            <option value="PAGADO" class="bg-success" style="--bs-bg-opacity: .5;" selected>PAGADO</option>
                            <option value="POR PAGAR" class="bg-danger" style="--bs-bg-opacity: .5;">POR PAGAR</option>
                            <option value="SERVICIO INTERNO" class="bg-warning" style="--bs-bg-opacity: .5;">SERVICIO INTERNO</option>
                          </select>
                          <label for="">¿Envio por pagar?</label>
                        </div>
                        <div class="form-floating mb-3">
                          <input type="number" name="peso" step="any" placeholder="Peso" class="form-control validate[required]">
                          <label for="peso">Peso [kg]</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-2">
                          <input type="text" class="form-control" placeholder="Observación envio" name="observaciones" value="">
                          <label for="">Observaciones (opcional)</label>
                        </div>
                        <div class="form-floating mb-3">
                          <input type="date" class="form-control validate[required,future[<?= date('Y-m-d') ?>]]" placeholder="Observación envio" name="fecha_envio" value="<?= date('Y-m-d') ?>">
                          <label for="">Fecha de envio</label>
                        </div>
                      </div>
                      <p class="fs-4 fw-bold"><i class="fa fa-solid fa-people-carry-box"></i> Datos receptor</p>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[required,maxSize[70]]" placeholder="Nombre receptor" name="nombre_destino">
                          <label for="">Nombre receptor</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[maxSize[12]]" id="ci_receptor" name="ci_destino" placeholder="Carnet de identidad" autocomplete="off">
                          <label for="ci_receptor">C.I. (opcional)</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <input type="text" class="form-control validate[required,custom[celular]]" id="celular_destino" value="" name="celular_destino" placeholder="Celular Destino" autocomplete="off">
                          <label for="celular_destino">Celular receptor</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <input id="fecha_estimada" type="datetime-local" class="form-control" placeholder="Fecha llegada" name="fecha_estimada" required value="<?= date('Y-m-d H:i', (time() + 24 * 3600)) ?>">
                          <label for="">Fecha - hora llegada (estimado)</label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-floating mb-3">
                          <select name="destino" class="form-select">
                            <?php foreach ($lugares as $lugar) : ?>
                              <?php if ($lugar['idLugar'] != $user->idLugar) : ?>
                                <option value="<?= $lugar['idLugar'] ?>"><?= $lugar['lugar'] ?></option>
                              <?php endif; ?>
                            <?php endforeach; ?>
                          </select>
                          <label for="">Destino</label>
                        </div>
                      </div>
                      <div class="mt-2 mb-2">
                        <button id="btn_agregar_fotos" type="button" class="btn btn-primary float-end " data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="collapse_nuevo_1 collapse_nuevo_2"><i class="fa fa-camera"></i> ¿Agregar fotos?</button>
                      </div>
                    </div>
                  </div>
                  <div class="collapse multi-collapse" id="collapse_nuevo_2">
                    <div class="row">
                      <div class="col-md-6">
                        <div style="width:100%;height:290px;border:1px solid red">
                          <video muted="muted" id="video" style="width:100%;height:100%;margin:0px;"></video>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <select id="camaras_select" class="form-select mb-2"></select>
                        <button type="button" class="btn btn-success rounded-pill" onclick="capturar()"><i class="fa fa-camera"></i> Capturar</button>
                        <button onclick="volverForm()" type="button" class="btn btn-secondary" data-bs-toggle="collapse" data-bs-target=".multi-collapse" aria-expanded="false" aria-controls="collapse_nuevo_1 collapse_nuevo_2">Seguir editando</button>
                        <div class="d-flex flex-wrap gap-3 mt-3" id="imgs_capturas"></div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="d-flex justify-content-center">
                      <button type="submit" class="btn btn-success shadow" id="btn_submit_form">GUARDAR</button>
                    </div>
                  </div>
                </div>
              </div><!-- end card -->
            </form>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->
  <script src="../assets/jquery/jqueryValidationEngine-es.min.js"></script>
  <script src="../assets/jquery/jqueryValidation.min.js"></script>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/nuevo.js"></script>
</body>

</html>