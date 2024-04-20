<?php
if (!isset($_POST['nameFile']) || $_POST['nameFile'] == '') {
  die('Sin Comprobante');
}
$subdominio = 'domain';
$nameFile = $_POST['nameFile'];
$content = '';
$url = '../public/' . $subdominio . '/' . $nameFile;
if (preg_match("/^audio/", $nameFile)) {
  $content = '<audio controls id="audio_comp"><source src="' . $url . '" type="audio/mpeg"></audio>';
} else {
  $content = '<img src="' . $url . '" class="img-fluid" alt="">';
}
?>
<h5 class="text-center">Vista del comprobante de pago</h5>
<div class="d-flex justify-content-center">
  <?= $content ?>
</div>
<div class="d-flex justify-content-center mt-2">
  <button type="button" class="btn btn-danger" id="btn_delete_comp" data-idpago="<?= $_POST['idPago'] ?>"><i class="fa fa-trash"></i> Eliminar comprobante</button>
</div>