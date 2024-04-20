<?php
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');
require_once('../app/models/envio.php');

use App\Config\Accesos;
use App\Config\Database;
use App\Models\Envio;

$crendenciales = Accesos::getCredentialsEmp($params[0], 1);
if (!isset($crendenciales['base'])) {
  include_once('./not_found.php');
  die();
}
$con = Database::getInstanceX($crendenciales['base']);
$envio = Envio::getEnvioExterno($con, $params[1]);
$con = null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Estado envio</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
</head>

<body>
  <nav class="navbar bg-primary">
    <div class="container">
      <a class="navbar-brand text-white" href="#">
        <b>EMPRESA</b>
      </a>
    </div>
  </nav>
  <div class="d-flex justify-content-center" style="margin-top:50px;">
    <div class="card shadow" style="min-width:auto;max-width:500px;">
      <div class=" card-header d-flex justify-content-between flex-wrap gap-3">
        <p class="fs-4 fw-bold">ENVIO <?= $envio['codigo'] ?? '' ?></p>
        <?php
        switch ($envio['estado']) {
          case 'ENVIADO':
            $colorBg = 'text-bg-primary';
            break;
          case 'ENTREGADO':
            $colorBg = 'text-bg-success';
            break;
          default:
            $colorBg = 'text-bg-warning';
            break;
        }
        ?>
        <p class="fs-5">ESTADO <span class="badge <?= $colorBg ?>"><?= $envio['estado'] ?></span></p>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between flex-wrap">
          <p class="fs-5 ">Origen: <?= $envio['origen'] ?></p>
          <p class="fs-5">Fecha: <?= date('d/m/Y', strtotime($envio['fecha_envio'])) ?></p>
        </div>
        <h5 class="card-title">Detalles de envio</h5>
        <p class="card-text">Remitente: <?= strtoupper($envio['nombre_origen']) ?></p>
        <p class="card-text">Destinatario: <?= strtoupper($envio['nombre_destino']) ?></p>
        <p class="card-text">Lugar destino: <?= strtoupper($envio['destino']) ?></p>
        <p class="card-text">Detalles: <?= $envio['detalle_envio'] ?></p>
        <p class="card-text">Peso: <?= ($envio['peso'] ?? '0.00') ?> kg</p>
        <div class="d-flex justify-content-between flex-wrap">
          <p class="card-text">Costo total: Bs. <?= ($envio['costo'] ?? '0.00') ?></p>
          <?php $colorPagado = $envio['pagado'] == 'PAGADO' ? 'text-bg-success' : 'text-bg-warning' ?>
          <p class="card-text">Pagado: <span class="badge <?= $colorPagado ?>"><?= ($envio['pagado'] ?? 'NO PAGADO') ?></span></p>
        </div>
      </div>
      <div class="card-footer d-flex justify-content-center">
        <a href="../reports/pdfEnvioExterno.php?rq=<?= $params[0] ?>_<?= $params[1] ?>" target="_blank" class="btn rounded-pill btn-secondary text-white">
          <svg xmlns="http://www.w3.org/2000/svg" style="width:25px" viewBox="0 0 512 512">
            <path style="fill:#fff" d="M128 0C92.7 0 64 28.7 64 64v96h64V64H354.7L384 93.3V160h64V93.3c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0H128zM384 352v32 64H128V384 368 352H384zm64 32h32c17.7 0 32-14.3 32-32V256c0-35.3-28.7-64-64-64H64c-35.3 0-64 28.7-64 64v96c0 17.7 14.3 32 32 32H64v64c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V384zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z" />
          </svg>
          Imprimir</a>
      </div>
    </div>
  </div>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>