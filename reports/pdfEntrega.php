<?php // COPIA DE G22
require_once('../app/config/accesos.php');
require_once('../app/config/database.php');
require_once('../tcpdf/tcpdf.php');
require_once('../app/models/envio.php');

use App\Models\Envio;

if (!isset($_GET['enid'])) {
  echo '<h1 align="center">Parametro id necesario</h1>';
  die();
} else {
  $subdominio = 'BOLIVAR';
  $nombre_suc = 'Sucursal Central';
  $ciudad = 'La Paz - Bolivia';
  $envio = new Envio($_GET['enid']);
  if ($envio->idEnvio == 0) {
    header('Location: ../');
    die();
  }

  $width = 217;
  $height = 340;

  // Calculamos alto de pagina (unicamente por el campo detalle_envio) Es el unico que puede ser mas grande
  $tam_fuente = 8;
  $w = $width - 16; // width - margins-x
  $lineas = contarLineas($envio->detalle_envio, $w, $tam_fuente);
  $aumentar = $lineas > 1 ? ($lineas - 1) * $tam_fuente : 0;
  $height += $aumentar;

  // Los mismo para el detalle de entrega
  $lineas = contarLineas($envio->observacion_llegada, $w, $tam_fuente);
  $aumentar = $lineas > 1 ? ($lineas - 1) * $tam_fuente : 0;
  $height += $aumentar;


  $pageLayout = array($width, $height);
  $pdf = new TCPDF('p', 'pt', $pageLayout, true, 'UTF-8', false);

  $pdf->SetCreator('PDF_CREATOR');
  $pdf->SetAuthor('STIS - BOLIVIA');
  $pdf->SetTitle('Nota de entrega');
  $pdf->setPrintHeader(false);
  $pdf->setPrintFooter(false);
  $pdf->SetMargins(8, 0, 8, false);
  $pdf->SetAutoPageBreak(true, 2);
  $pdf->SetFont('Helvetica', '', $tam_fuente);
  $pdf->addPage();

  $content = '<h2 style="text-align:center;">NOTA DE ENTREGA</h2>';
  $pdf->writeHTML($content, true, 0, true, 0);

  $tabla = '<table border="0" cellpadding="0">
  <tr><td colspan="500" align="center"><b>' . $subdominio . '</b></td></tr>
            <tr><td colspan="500" align="center">' . $nombre_suc . '</td></tr>
            <tr><td colspan="500" align="center">' . $ciudad . '</td></tr>
            <tr><td colspan="500" align="center"><b>CÓDIGO: </b>' . $envio->idEnvio . '-' . $envio->codigo . '</td></tr>
            <tr><td colspan="500" align="center" >NOTA ORIGINAL</td></tr>
            <tr><td colspan="500" style="padding: 8px; border-bottom: 1px solid #000;"></td></tr>
      </table>';
  $tabla .= '<table border="0" cellpadding="0">
            <tr><td colspan="500" style="font-size:30%;"></td></tr>
            <tr><td colspan="500" align="center" style="font-size:80%;"><b>DATOS REMITENTE</b></td></tr>
            <tr><td colspan="500" align="left"><b>Nombre: </b>' . $envio->nombre_origen . '</td></tr>
            <tr><td colspan="500" align="left"><b>C.I.: </b>' . $envio->ci_origen . '</td></tr>
            <tr><td colspan="500" align="left"><b>Lugar origen: </b>' . $envio->origen . '</td></tr>
            <tr><td colspan="500" align="left"><b>Celular: </b>' . $envio->celular_origen . '</td></tr>
            <tr><td colspan="500" align="left"><b>Fecha - hora envio: </b>' . date('d/m/Y H:i:s', strtotime($envio->fecha_envio)) . '</td></tr>
            <tr><td colspan="500"><b>Detalles: </b>' . $envio->detalle_envio . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td></tr></table>';
  $fechaEntrega = ($envio->fecha_entrega != null && $envio->fecha_entrega != '') ? date('d/m/Y H:m', strtotime($envio->fecha_entrega)) : 'S/F';
  $tabla .= '<table border="0" cellpadding="0">
            <tr><td colspan="500" style="font-size:30%;"></td></tr>
            <tr><td colspan="500" align="center" style="font-size:80%;"><b>DATOS DESTINATARIO</b></td></tr>
            <tr><td colspan="500" align="left"><b>Nombre: </b>' . $envio->nombre_destino . '</td></tr>
            <tr><td colspan="500" align="left"><b>C.I.: </b>' . $envio->ci_destino . '</td></tr>
            <tr><td colspan="500" align="left"><b>Lugar destino: </b>' . $envio->destino . '</td></tr>
            <tr><td colspan="500" align="left"><b>Celular: </b>' . $envio->celular_destino . '</td></tr>
            <tr><td colspan="500" align="left"><b>Fecha de entrega: </b>' . $fechaEntrega . '</td></tr>
            <tr><td colspan="500"><b>Obs.:</b> ' . $envio->observacion_llegada . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td></tr></table>';

  $tabla .= '<table border="0" cellpadding="0"><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr><tr><td colspan="500"></td></tr>';
  $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;"></td></tr>';
  $tabla .= '<tr><td colspan="200" align="center" style="padding: 8px; text-align: center;">Firma/Sello Emp. Bolivar</td><td colspan="100"></td><td colspan="200" align="center" style="padding: 8px; text-align: center;">Firma destinatario</td></tr>';
  $tabla .= '</table>';
  $pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
  $pdf->output('notaEntrega.pdf', 'I');
}

function contarLineas($cadena, $anchoPagina, $tamanoFuente) {
  $caracteresPorLinea = ceil($anchoPagina / ($tamanoFuente * 0.75));

  $lineas = str_split($cadena, $caracteresPorLinea);

  // Contar el número de líneas
  $numeroLineas = count($lineas);

  return $numeroLineas;
}
