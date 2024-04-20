<?php
if (isset($_COOKIE['user_obj'])) {
  $user = json_decode($_COOKIE['user_obj']);
} else {
  header('Location: ../auth/login.php');
  die();
}
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');
require_once('../app/models/envio.php');

use App\Models\Envio;

$envios = Envio::get_mis_envios($user->idUsuario, $user->rol);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>Lista envios</title>
  <link rel="stylesheet" href="../assets/datatables/datatables.bootstrap5.min.css">
  <link href="../css/styles.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/custom.css">
  <link rel="stylesheet" href="../assets/jquery/jqueryToast.min.css">
  <script src="../assets/fontawesome/fontawesome6.min.js"></script>
  <script src="../assets/jquery/jquery.js"></script>
  <script src="../assets/jquery/jqueryToast.min.js"></script>
</head>

<body>
  <?php include("../common/header.php"); ?>
  <div id="layoutSidenav"> <!-- contenedor -->
    <?php include("../common/sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main id="main_egresos">
        <div class="container-fluid px-4">
          <div class="mt-4">
            <h1>Envios</h1>
          </div>
          <div class="buttons-head col-md-6 col-sm-12 mb-3">
            <a class="btn btn-primary" type="button" href="./nuevo.php"><i class="fa fa-solid fa-plus"></i> Nuevo Envio</a>
          </div>
          <div class="row" id="card-egresos">
            <div class="card shadow">
              <div class="card-header">
                <h4>
                  <i class="fa fa-table"></i> Lista de proyectos
                </h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table style="width:100%" class="table table-hover" id="tabla_mis_envios">
                    <thead>
                      <tr>
                        <th class="text-center">N° ID</th>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Remitente</th>
                        <th class="text-center">Destinatario</th>
                        <th class="text-center">Lugar Destino</th>
                        <th class="text-center">Fecha envio</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center"><i class="fa fa-solid fa-print"></i></th>
                      </tr>
                    </thead>
                    <tbody id="t_body_envios">
                      <?php foreach ($envios as $envio) :
                        $clss = '';
                        switch ($envio['estado']) {
                          case 'ENVIADO':
                            $clss = 'bg-warning';
                            break;
                          case 'EN ALMACEN':
                            $clss = 'bg-primary';
                            break;
                          case 'ENTREGADO':
                            $clss = 'bg-success';
                            break;
                          default:
                            $clss = '';
                            break;
                        }
                        $imagenes = $envio['capturas'] ?? '';
                        $codigo = $imagenes != '' ? "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#modal_ver_capturas' data-id='" . $envio['idEnvio'] . "'>" . $envio['idEnvio'] . '-' . $envio['codigo'] . "</button>" : $envio['idEnvio'] . '-' . $envio['codigo'];
                      ?>
                        <tr>
                          <td class="text-center"><?= $envio['idEnvio'] ?></td>
                          <td class="text-center"><?= $codigo ?></td>
                          <td class="text-center align-middle"><?= $envio['nombre_origen'] ?> | <?= $envio['ci_origen'] ?></td>
                          <td class="text-center align-middle"><?= $envio['nombre_destino'] ?> | <?= $envio['ci_destino'] ?></td>
                          <td class="text-center"><?= $envio['destino'] ?></td>
                          <td class="text-center"><?= date('d/m/Y', strtotime($envio['fecha_envio'])) ?></td>
                          <td class="text-center align-middle"><span class="badge <?= $clss ?>"><?= $envio['estado'] ?></span></td>
                          <td class="align-middle">
                            <div class="d-flex gap-1">
                              <a href="../reports/pdfEnvio.php?enid=<?= $envio['idEnvio'] ?>" target="_blank" class="btn btn-secondary"><i class="fa fa-solid fa-print"></i></a>
                              <?php if ($user->rol == 'ADMIN') : ?>
                                <a href="./edit.php?enid=<?= $envio['idEnvio'] ?>" class="btn btn-primary"><i class="fa fa-pen"></i></a>
                                <button type="button" class="btn btn-danger" data-bs-toggle='modal' data-bs-target='#modal_eliminar_envio' data-id="<?= $envio['idEnvio'] ?>"><i class="fa fa-trash"></i></button>
                              <?php endif; ?>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div><!-- fin contenedor -->

  <?php include('./modals.php'); ?>
  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../js/scripts.js"></script>
  <script src="../assets/datatables/datatables.jquery.min.js"></script>
  <script src="../assets/datatables/datatables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
</body>

</html>