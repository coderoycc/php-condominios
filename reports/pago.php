<?php
require_once('../app/config/database.php');
require_once('../app/models/pago.php');
require_once('../app/models/proyecto.php');
require_once('../tcpdf/tcpdf.php');

use App\Models\Pago;
use App\Models\Proyecto;

$subdominio = 'ASWAN';
$nombre_suc = 'Sucursal Central';
$ciudad = 'La Paz - Bolivia';
$height = 10;
if (isset($_GET['pagid'])) {
  $pago = new Pago($_GET['pagid']);
  if ($pago->idPago == 0) {
    header('Location: ../');
    die();
  }
  $proyecto = new Proyecto($pago->idProyecto);
  if ($proyecto->idProyecto == 0) {
    header('Location: ../');
    die();
  }
  $width = 217;
  $height += 250;
  $pageLayout = array($width, $height); //  or array($height, $width) 
  $pdf = new TCPDF('p', 'pt', $pageLayout, true, 'UTF-8', false);

  $pdf->SetCreator('PDF_CREATOR');
  $pdf->SetAuthor('STIS - BOLIVIA');
  $pdf->SetTitle('Nota de pago');
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetMargins(8, 0, 0, false);
  $pdf->SetAutoPageBreak(true, 2);
  $pdf->SetFont('Helvetica', '', 8);
  $pdf->addPage();

  $content = '<h2 style="text-align:center;">NOTA DE PAGO</h2>';
  $pdf->writeHTML($content, true, 0, true, 0);

  $tabla = '<table border="0" cellpadding="0">';
  $tabla .= '<tr><td colspan="500" align="center"><b>' . $subdominio . '</b></td></tr>
            <tr><td colspan="500" align="center">' . $nombre_suc . '</td></tr>
            <tr><td colspan="500" align="center">' . $ciudad . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;">NOTA ORIGINAL</td></tr>
      </table>';

  $tabla .= '<table border="0" cellpadding="0">
  <tr><td colspan="500" style="font-size:30%;"></td></tr>';
  $tabla .= '<tr><td colspan="500" align="center"><b>ID PAGO: </b>' . $pago->idPago . '</td></tr>
            <tr><td colspan="500" align="center"><b>ID PROYECTO: </b> ' . $proyecto->idProyecto . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;">' . strtoupper($proyecto->proyecto) . '</td></tr>';
  $tabla .= '</table>';
  $tabla .= '<table border="0" cellpadding="0">';

  if ($proyecto->tipo == 'EGRESO') { // Verificamos tipo proyecto (ingreso o egreso)
    $pagado = $pago->pagadoPorEgreso()['alias'];
    $recibido = $pago->recibidoPorEgreso()['nombre'];
  } else {
    $pagado = $pago->pagadoPorIngreso()['nombre'];
    $recibido = $pago->recibidoPorIngreso()['alias'];
  }
  $tipoPago = $pago->adelanto ?? 'PAGO';
  $lugar = $pago->lugar ?? '';
  $tabla .= '
            <tr><td colspan="500" align="left"><b>Pagado por: </b>' . strtoupper($pagado) . '</td></tr>
            <tr><td colspan="500" align="left"><b>Recibido por: </b>' . strtoupper($recibido) . '</td></tr>
            <tr><td colspan="500" align="left"><b>Lugar: </b>' . $lugar . '</td></tr>
            <tr><td colspan="500" align="left"><b>Concepto: </b><br>   ' . $tipoPago . ' | ' . $pago->concepto . '</td></tr>
            <tr><td colspan="250" align="left"><b>Monto: </b>' . number_format($pago->monto, 2) . '</td><td colspan="250" align="left"><b>Modo: </b> ' . $pago->modoPago . '</td></tr>
            <tr><td colspan="500" align="left" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;"><b>Fecha y hora:</b> ' . date('d/m/Y H:i', strtotime($pago->fechaRegistro)) . '</td></tr>
      </table>';
  $tabla .= '<table border="0" cellpadding="0"><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr>';
  $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td></tr>';
  $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: center;">Firma pagado por</td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: center;">Firma recibido por</td></tr>';
  $tabla .= '</table>';
  $pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
  $pdf->output('dombre.pdf', 'I');
} else {
  header('Location: ../');
  die();
}
