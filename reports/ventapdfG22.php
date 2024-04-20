<?php
session_start();
// if (!isset($_COOKIE['user_id']) || !isset($_COOKIE['base_subdominio']) || !isset($_COOKIE['ipconfig'])) {
//   header('Location:../login/logout.php');
// }
// ob_start();
// error_reporting(E_ALL & ~E_NOTICE);
// ini_set('display_errors', 0);
// ini_set('log_errors', 1);
function verdadera_longitud($cadena) {
  $cadena = trim($cadena);
  $alfabeto1m = ['m']; // 2
  $alfabeto1w = ['w']; // 1.85
  $alfabeto1b = ['b', 'd', 'g', 'h', 'k', 'n', 'o', 'p', 'q', 'u', 'v', 'x', 'y']; //1.27
  $alfabeto1a = ['a', 'c', 'e', 'z']; // 1.13
  $alfabeto1s = ['s']; // 1
  $alfabeto1r = ['r', 'f']; // 0.85
  $alfabeto1j = ['j', 't']; // 0.7
  $alfabeto2w = ['W']; // 2.40
  $alfabeto2m = ['M']; // 2.26
  $alfabeto2a = ['A', 'D', 'G', 'H', 'K', 'N', 'Ñ', 'O', 'Q', 'U', 'V', 'X', 'Y']; // 1.86
  $alfabeto2b = ['B', 'C', 'R']; // 1.71
  $alfabeto2e = ['E', 'L', 'T', 'Z']; // 1.56
  $alfabeto2l = ['P', 'S', 'F']; // 1.38
  $alfabeto2j = ['J']; // 1
  $alfabeto2i = ['I']; // 0.87
  $alfabeto31 = ['1', '(', ')', '[', ']', '{', '}']; // 1.18
  $alfabeto32 = ['2', '3', '4', '5', '6', '7', '8', '9', '0']; //1.26
  $longitud = 0;
  for ($i = 0; $i < strlen($cadena); $i++) {
    $char = $cadena[$i];
    if (in_array($char, $alfabeto1m)) {
      $longitud += 2;
    } elseif (in_array($char, $alfabeto1w)) {
      $longitud += 1.85;
    } elseif (in_array($char, $alfabeto1b)) {
      $longitud += 1.27;
    } elseif (in_array($char, $alfabeto1a)) {
      $longitud += 1.13;
    } elseif (in_array($char, $alfabeto1s)) {
      $longitud += 1;
    } elseif (in_array($char, $alfabeto1r)) {
      $longitud += 0.85;
    } elseif (in_array($char, $alfabeto1j)) {
      $longitud += 0.7;
    } elseif (in_array($char, $alfabeto2w)) {
      $longitud += 2.40;
    } elseif (in_array($char, $alfabeto2m)) {
      $longitud += 2.26;
    } elseif (in_array($char, $alfabeto2a)) {
      $longitud += 1.86;
    } elseif (in_array($char, $alfabeto2b)) {
      $longitud += 1.71;
    } elseif (in_array($char, $alfabeto2e)) {
      $longitud += 1.56;
    } elseif (in_array($char, $alfabeto2l)) {
      $longitud += 1.38;
    } elseif (in_array($char, $alfabeto2j)) {
      $longitud += 1;
    } elseif (in_array($char, $alfabeto2i)) {
      $longitud += 0.87;
    } elseif (in_array($char, $alfabeto31)) {
      $longitud += 1.18;
    } elseif (in_array($char, $alfabeto32)) {
      $longitud += 1.26;
    } else {
      $longitud += 1.2;
    }
  }
  return $longitud;
}

require_once('../conexion.php');
// include('../codigo_control/ControlCode.php');
require_once('convertidortexto.php');
date_default_timezone_set('America/La_Paz');
$idventa = $_GET["idta"];
$title1 = 'Reporte de Venta';
$user = $_COOKIE['user_name'];
$subdominio = trim($_COOKIE['base_subdominio']);

$maestro = "SELECT tvm.*,cufd.codigoPuntoVenta,cufd.direccion FROM (SELECT montoTotal,idVenta,fecha,idTienda,idUser,idCliente,cliente,NIT,idRedundante,NoFactura,observaciones,numeroTransferencia,consolidado,ordenSalida,comprobanteIngreso,referenciaOrden,confirmacionEntrega,idVariableDetalle,entidad,tipoCambio,cuenta01,cuenta02,estado,tipoEnvio,idUsuarioEnvio,fechaFacturacion,vigenciaOrden,hora,numGeneracionPdf,facturaAsociada,NoAutorizacion,fechaLimiteEmision,cantidadEnLetras,idUserVentaConsigna,idVentaPaquete,notaEntrega,descuentoGlobal,complemento,horaFacturacion,cantidadEnLetrasFactura,codigoControl,primerMonto,segundoMonto,tercerMonto,codigoRecepcion,cufdVenta,billete FROM tblVentasMaestro UNION SELECT montoTotal,idVenta,fecha,idTienda,idUser,idCliente,cliente,NIT,idRedundante,NoFactura,observaciones,numeroTransferencia,consolidado,ordenSalida,comprobanteIngreso,referenciaOrden,confirmacionEntrega,idVariableDetalle,entidad,tipoCambio,cuenta01,cuenta02,estado,tipoEnvio,idUsuarioEnvio,fechaFacturacion,vigenciaOrden,hora,numGeneracionPdf,facturaAsociada,NoAutorizacion,fechaLimiteEmision,cantidadEnLetras,idUserVentaConsigna,idVentaPaquete,notaEntrega,descuentoGlobal,complemento,horaFacturacion,cantidadEnLetrasFactura,codigoControl,primerMonto,segundoMonto,tercerMonto,codigoRecepcion,cufdVenta,billete FROM tblVentasMaestro_2022) AS tvm LEFT JOIN (SELECT cufd,codigoControl,codigoPuntoVenta,direccion FROM tblCufd) AS cufd ON tvm.codigoControl = cufd.codigoControl AND tvm.cufdVenta = cufd.cufd WHERE tvm.idVenta = 100";

$query_m = sqlsrv_query($con, $maestro);
$dato = sqlsrv_fetch_array($query_m);
if ($dato['observaciones'] == 'ANULADO') {
  $title0 = "FACTURA ANULADA";
} else {
  $title0 = "FACTURA";
}
$clie = $dato['cliente'] ?? 'cliente';
$date = $dato['fechaFacturacion'] ?? date('Y-m-d');
$hora = $dato['horaFacturacion'] ?? date('H:m');
$idcliente = $dato['idCliente'] ?? 10;
$autori2 = $dato['NoAutorizacion'];
$autori = $dato['NoAutorizacion'];
$codigoControl = str_replace("-", "", $dato['codigoControl']);
$idvariable = $dato['idVariableDetalle'];
$cantidadEnLetrasFactura = $dato['cantidadEnLetrasFactura'];
$complemento = $dato['complemento'];
$tipoCambio = $dato['tipoCambio'];
$direcc = $dato['direccion'];
$billete = $dato['billete'];
$descuentoGlobal = round(floatval($dato['descuentoGlobal']), 2);
// $fechave = date_format($date, 'Y-m-d');
$year = date("Y", time());
$autor = "© STIS " . $year;
$fech = date_format($date, 'd/m/Y');
$fecha = date_format($date, 'Ymd');
$styleQr = array(
  'border' => false,
  'padding' => 0,
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false
);
$styleBar = array(
  'position' => '',
  'align' => 'R',
  'stretch' => false,
  'fitwidth' => true,
  'cellfitalign' => '',
  'border' => false,
  'hpadding' => 'auto',
  'vpadding' => 'auto',
  'fgcolor' => array(0, 0, 0),
  'bgcolor' => false, //array(255,255,255),
  'text' => false,
  'font' => 'helvetica',
  'fontsize' => 8,
  'stretchtext' => 4
);
$variables = "SELECT * FROM tblVariablesDetalle WHERE idVariableDetalle = $idvariable";
$consvar = sqlsrv_query($con, $variables);
$vari = sqlsrv_fetch_array($consvar);
$nom_em = $vari['nombreEmpresa'];
$sucurs = $vari['sucursal'];
$ciudad = $vari['ciudad'];
$nit    = $vari['NIT'];
$telefo = $vari['telefono'];
// $autori = $vari['NoAutorizacion'];
$llaved = $vari['llaveDosificacion'];
$numfac = $dato['NoFactura'];
$fechaemi = $vari['fechalimiteEmision'];
$actividad = $vari['actividadEconomica'];
$leyenda1 = $vari['leyenda1'];
$leyenda2 = $vari['leyenda2'];
$p1 = $vari['p1'];
$p2 = $vari['p2'];
$p3 = $vari['p3'];
$p4 = $vari['p4'];
$p5 = $vari['p5'];
$fechaemi = date_format($fechaemi, 'd-m-Y');
$incre = 8.4;

$detalle = "SELECT tblProductos.idProducto, tblProductos.piezasxCaja, cantidad, precioVendido, nombreComercial, precioVendido, LEN(nombreComercial) as longitud , tvdg.textPresentacion, tvdg.piezasXCaja, tvdg.tipoCliente, tvdg.precioFacturado, tblProductos.barras, tbld.cantidad_dev, tvdg.descuento, tbunme.descripcion FROM (SELECT id,idVenta,idSubPaquete,idPaquete,idProducto,textPresentacion,piezasXCaja,tipoCliente,precioFacturado,idTienda,cantidad,precioVendido,lotes,descuento,idUnidadMedida FROM tblVentasDetalle UNION SELECT id,idVenta,idSubPaquete,idPaquete,idProducto,textPresentacion,piezasXCaja,tipoCliente,precioFacturado,idTienda,cantidad,precioVendido,lotes,descuento,idUnidadMedida FROM tblVentasDetalle_2022) AS tvdg LEFT JOIN (SELECT descripcion,codigoClasificador FROM tblUnidadMedida) as tbunme ON tvdg.idUnidadMedida = tbunme.codigoClasificador LEFT JOIN (SELECT SUM(dev.cantidad) as cantidad_dev, dev.lote as love_dev, dev.idProducto, dev.idVenta FROM tblDevolucion dev, tblVentasDetalle tvd WHERE dev.idVenta = tvd.id AND tvd.idVenta = $idventa GROUP BY dev.lote,dev.idProducto,dev.idVenta) tbld ON tvdg.id = tbld.idVenta, tblProductos WHERE tvdg.idProducto = tblProductos.idProducto AND tvdg.idVenta = $idventa;";

$query_d = sqlsrv_query($con, $detalle);
$height = 180;
while ($deta = sqlsrv_fetch_array($query_d)) {
  $canti_total = floatval($deta['cantidad']) - floatval($deta['cantidad_dev']);
  $longitud = intval($deta['longitud']);
  if (floatval($canti_total) > 0) {
    $longitud = verdadera_longitud($deta['nombreComercial']);
    if ($longitud < 30) {
      $factor_salto = 1;
    } else {
      $factor_salto = intval($longitud / 30) + 1;
    }
    $height += ceil($incre * $factor_salto);
  }
}
require_once('../tcpdf/tcpdf.php');
// $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false); 
// $pdf = new CUSTOMPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
//Add a custom size  
// $width = 550;
$width = 217;
$height += 230;
$pageLayout = array($width, $height); //  or array($height, $width) 
$pdf = new TCPDF('p', 'pt', $pageLayout, true, 'UTF-8', false);

$pdf->SetCreator('PDF_CREATOR');
$pdf->SetAuthor($autor);
$pdf->SetTitle($title0);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(8, 0, 0, false);
$pdf->SetAutoPageBreak(true, 2);
$pdf->SetFont('Helvetica', '', 8);
$pdf->addPage();

$content = '';
$linea = '------------------------------------------------------------------------------------';
$content .= '
              <h2 style="text-align:center;">' . $nom_em . '</h2>
  ';
$pdf->writeHTML($content, true, 0, true, 0);
$titulo1 = 'FACTURA ORIGINAL';
$phone = 'Telefono:' . $telefo;
// $actividad = 'Actividad Económica: ' . $actividad;
$desnit = '<b>NIT:  </b>' . $nit;
$titulo2 = '<b>FACTURA NRO:  </b>' . $numfac;
$desauto = '<b>CÓD. AUTORIZACIÓN:  </b>' . $autori2;
$desfecha = '<b>Fecha y Hora:</b>' . $fech . ' ' . $dato['horaFacturacion'];
$desclien = '<b>Señor(es):</b>' . $clie;

if (strlen(trim($complemento)) > 0) {
  $desnitcl = '<b>NIT/CI/CEX:</b><br>' . $dato['NIT'] . "-" . $complemento;
} else {
  $desnitcl = '<b>NIT/CI/CEX:</b><br>' . $dato['NIT'];
}

if ($dato['observaciones'] == 'ANULADO') {
  $img_file2 = '../tcpdf/images/ANULADOMARK.png';
  $pdf->Image($img_file2, 0, 80, 217, 200, 'PNG', '', '', true, 200, '', false, false, 0, false, false, false);
}

$descodcl = '<b>CÓD. CLIENTE:</b><br>' . $dato['idCliente'];

$tabla = '<table border="0" cellpadding="0">';
$tabla .= '<tr><td colspan="500" align="center">' . $sucurs . '</td></tr>
            <tr><td colspan="500" align="center">' . $direcc . '</td></tr>
            <tr><td colspan="500" align="center">' . $phone . '</td></tr>
            <tr><td colspan="500" align="center">' . $ciudad . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;">' . $titulo1 . '</td></tr>';
$tabla .= '</table>';
$tabla .= '<table border="0" cellpadding="0">';
$tabla .= '<tr><td colspan="500" align="center">' . $desnit . '</td></tr>
            <tr><td colspan="500" align="center">' . $titulo2 . '</td></tr>
            <tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;">' . $desauto . '</td></tr>';
// $tabla .= '<tr><td colspan="500" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000;">' . $actividad . '</td></tr>';
$tabla .= '</table>';

$tabla .= '<table border="0" cellpadding="0">';
$tabla .= '
            <tr><td colspan="500" align="left">' . $desclien . '</td></tr>
            <tr><td colspan="250" align="left">' . $desnitcl . '</td><td colspan="250" align="left">' . $descodcl . '</td></tr>
            <tr><td colspan="500" align="left" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">' . $desfecha . '</td></tr>
            <tr><td colspan="500" align="center"><b>Lista de Productos</b></td></tr>
            <tr>';

$tabla .= '
<td colspan="60" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">Canti</td>
<td colspan="200" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">Detalles</td>
<td colspan="80" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">P.Unit</td>
<td colspan="70" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">Desct</td>
<td colspan="90" align="center" style="padding: 8px; text-align: left; border-bottom: 1px solid #000; font-size:80%;">SubTotal</td>
</tr>';

$salto = 160;
$preto = 0;
$query_d = sqlsrv_query($con, $detalle);
$row_count = sqlsrv_has_rows($query_d);
if ($row_count === false) {
  $content .= '<tr><td colspan="8">No hay productos en esta venta.</td></tr>';
  $tabla .= '</table>';
  $pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);
} else {
  $pag = 1;
  $pdf->SetFont('Helvetica', '', 7);
  while ($deta = sqlsrv_fetch_array($query_d)) {
    $canti_total = floatval($deta['cantidad']) - floatval($deta['cantidad_dev']);
    $longitud = intval($deta['longitud']);
    if (floatval($canti_total) > 0) {
      $cantidad = $canti_total / $deta['piezasXCaja'];
      $descuento = round(floatval($deta['descuento']), 2);
      switch ($deta['tipoCliente']) {
        case 'ESPECIAL':
          if ($p2 == 'NORMAL') {
            $preciosalida = round(floatval($deta['precioVendido']), 2);
            $costo = ($preciosalida - $descuento) * $cantidad;
          } else {
            $preciosalida = round(floatval($deta['precioFacturado']), 2) * intval($deta['piezasXCaja']);
            $costo = ($preciosalida - $descuento) * $canti_total / intval($deta['piezasXCaja']);
          }
          break;
        case 'PREMIUM':
          if ($p3 == 'NORMAL') {
            $preciosalida = round(floatval($deta['precioVendido']), 2);
            $costo = ($preciosalida - $descuento) * $cantidad;
          } else {
            $preciosalida = round(floatval($deta['precioFacturado']), 2) * intval($deta['piezasXCaja']);
            $costo = ($preciosalida - $descuento) * $canti_total / intval($deta['piezasXCaja']);
          }
          break;
        case 'VIP':
          if ($p4 == 'NORMAL') {
            $preciosalida = round(floatval($deta['precioVendido']), 2);
            $costo = ($preciosalida - $descuento) * $cantidad;
          } else {
            $preciosalida = round(floatval($deta['precioFacturado']), 2) * intval($deta['piezasXCaja']);
            $costo = ($preciosalida - $descuento) * $canti_total / intval($deta['piezasXCaja']);
          }
          break;
        case 'GOLD':
          if ($p5 == 'NORMAL') {
            $preciosalida = round(floatval($deta['precioVendido']), 2);
            $costo = ($preciosalida - $descuento) * $cantidad;
          } else {
            $preciosalida = round(floatval($deta['precioFacturado']), 2) * intval($deta['piezasXCaja']);
            $costo = ($preciosalida - $descuento) * $canti_total / intval($deta['piezasXCaja']);
          }
          break;
        default:
          if ($p1 == 'NORMAL') {
            $preciosalida = round(floatval($deta['precioVendido']), 2);
            $costo = ($preciosalida - $descuento) * $cantidad;
          } else {
            $preciosalida = round(floatval($deta['precioFacturado']), 2) * intval($deta['piezasXCaja']);
            $costo = ($preciosalida - $descuento) * $canti_total / intval($deta['piezasXCaja']);
          }
          break;
      }
      $preto = $preto + round($costo, 2);

      $longitud = verdadera_longitud($deta['nombreComercial']);
      if ($longitud < 30) {
        $factor_salto = 1;
      } else {
        $factor_salto = intval($longitud / 30) + 1;
      }

      $tabla .= '<tr>
      <td colspan="60" align="center">' . $cantidad . '</td>
      <td colspan="200" align="left">' . $deta['nombreComercial'] . '</td>
      <td colspan="80" align="right">' . number_format(round($preciosalida, 2), 2) . '</td>
      <td colspan="70" align="right">' . number_format(round($descuento, 2), 2) . '</td>
      <td colspan="90" align="right">' . number_format(round($costo, 2), 2) . '</td>
      </tr>';

      $salto += ceil($incre * $factor_salto);
    }
  }

  $tabla .= '</table>';
  $pdf->WriteHTMLCell(0, 0, '', '', $tabla, 0, 0);

  $preto = round($preto, 2);
  $pret = 'Precio Total.- ' . $preto;

  $array_tipo_cambio = explode(' – ', $tipoCambio);
  // print_r($array_tipo_cambio);
  if (in_array('GIFT-CARD', $array_tipo_cambio)) {
    $clave = array_search('GIFT-CARD', $array_tipo_cambio);
    // echo '>>>'.$clave;
    switch (intval($clave)) {
      case 2:
        $gift = round(floatval($dato['tercerMonto']), 2);
        break;
      case 1:
        $gift = round(floatval($dato['segundoMonto']), 2);
        break;
      default:
        $gift = round(floatval($dato['primerMonto']), 2);
        break;
    }
  } else {
    $gift = round(0, 2);
  }


  // $tabla .= '<table border="0" cellpadding="1">';
  // $numerotexto = numtoletras($preto);
  $pdf->SetFont('Helvetica', '', 7);
  $numerotexto = $cantidadEnLetrasFactura;

  $total = $preto - $descuentoGlobal;
  $monto_pagar = $total - $gift;
  $importe_fiscal = $monto_pagar;

  $salto += $incre;
  $pdf->MultiCell(217, 2, 'SUBTOTAL Bs. ' . number_format($preto, 2), 0, 'R', 0, 1, 0, $salto, true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'DESCUENTO Bs. ' . number_format($descuentoGlobal, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'TOTAL Bs. ' . number_format($total, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'MONTO GIFT CARD Bs. ' . number_format($gift, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'MONTO A PAGAR Bs. ' . number_format($monto_pagar, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'IMPORTE BASE CRÉDITO FISCAL Bs. ' . number_format($importe_fiscal, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $pdf->MultiCell(217, 2, 'EFECTIVO. ' . number_format($billete, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;
  $importe_cambio = $billete - $monto_pagar;
  $pdf->MultiCell(217, 2, 'CAMBIO. ' . number_format($importe_cambio, 2), 0, 'R', 0, 1, 0, '', true);
  $salto += $incre;


  $codigo = "";
  $fecha = date_format($date, 'Ymd');
  // $controlCode = new ControlCode();
  $preto = round($preto, 2);
  // echo $autori.'|||'.$numfac.'|||'.$dato['NIT'].'|||'.$fecha.'|||'.$preto.'|||'.$llaved;
  // $codigo = $controlCode->generate($autori, $numfac, $dato['NIT'], $fecha, $preto, $llaved);
  // $descripcion = 'CODIGO CONTROL: ' . $codigo;
  $fecha = date_format($date, 'd/m/Y');
  if (strlen(trim($complemento)) > 0) {
    $nit_cliente = $dato['NIT'] . "-" . $complemento;
  } else {
    $nit_cliente = $dato['NIT'];
  }
  // $code = $nit . "|" . $numfac . "|" . $autori . "|" . $fecha . "|" . $preto . "|" . $preto . "|" . $codigo . "|" . $nit_cliente . "|0.00|0.00|0.00|0.00";
  // $code = "https://siat.impuestos.gob.bo/consulta/QR?nit=$nit&cuf=$autori&numero=$numfac&t=1";
  $code = "https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=$nit&cuf=$autori&numero=$numfac&t=1";
  $style = array(
    'border' => false,
    'padding' => 0,
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false
  );

  $numerotexto = numtoletras($preto);
  $currentY = $pdf->GetY();
  $pdf->MultiCell(217, 2, $numerotexto, 0, '', 0, 1, '', $currentY, true);

  $update_tabla = "UPDATE tblVentasMaestro SET numGeneracionPdf = numGeneracionPdf+1 WHERE idVenta = $idventa;";
  $que = sqlsrv_query($con, $update_tabla);

  $salto += $incre;
  // $pdf->MultiCell(500, 2, $descripcion, 0, '', 0, 1, '', $salto, true);
  $salto += $incre;
  // $pdf->MultiCell(500, 2, 'FECHA LIMITE DE EMISION: ' . $fechaemi, 0, 'L', 0, 1, '', $salto, true);
  $pdf->write2DBarcode($code, 'QRCODE,M', 75, '', 80, 80, $styleQr, 'N');
  // $pdf->MultiCell(500, 2, $leyenda1, 0, 'C', 0, 1, '', '', true);
  $sql_leyenda = "SELECT TOP 1 descripcionLeyenda FROM tblListadoLeyenda ORDER BY NEWID();";
  $query_leyenda = sqlsrv_query($con, $sql_leyenda);
  $leyenda2 = sqlsrv_fetch_array($query_leyenda);

  $pdf->MultiCell(210, 2, 'ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS, EL USO ILÍCITO SERÁ SANCIONADO PENALMENTE DE ACUERDO A LEY', 0, 'C', 0, 1, '', '', true);

  $pdf->MultiCell(210, 2, $leyenda2['descripcionLeyenda'], 0, 'C', 0, 1, '', '', true);

  if (isset($dato['codigoRecepcion']) && strlen(trim($dato['codigoRecepcion'])) > 20) {
    $pdf->MultiCell(210, 2, '“Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación en línea”', 0, 'C', 0, 1, '', '', true);
  } else {
    $pdf->MultiCell(210, 2, '“Este documento es la Representación Gráfica de un Documento Fiscal Digital emitido en una modalidad de facturación fuera de línea”', 0, 'C', 0, 1, '', '', true);
  }
}

$vec[] = array();
$vec = explode(" ", $clie);
$pdf->Ln(4);
$pdf->lastPage();
// $nom = "Reporte-" . $vec[0] . "-" . $fech . "-" . $dato['hora'] . ".pdf";
// $nom = 'Factura-'.$numfac . ".pdf";

if ($dato['observaciones'] == 'ANULADO') {
  $nom = 'Factura_anulada-' . $numfac . ".pdf";
} else {
  $nom = 'Factura-' . $numfac . ".pdf";
}

// ob_end_clean();
// $pdf->output(__DIR__ . $nom, 'I');
// $pdf->output($nom, 'I');
// $pdf->output($_SERVER['DOCUMENT_ROOT'] . 'Ventas/api_facturacion/' . $codigoControl . '/' . $nom, 'FI');
$pdf->output($_SERVER['DOCUMENT_ROOT'] . 'webinventario/VentasFEC/api_facturacion/' . $subdominio . '/' . $codigoControl . '/' . $nom, 'FI');
