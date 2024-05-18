<?php
session_start();

use App\Models\Subscription;
use App\Models\Subscriptiontype;
use App\Providers\DBWebProvider;
use Helpers\Resources\HandleDates;

require_once(__DIR__ . '/../../PHPExcel/Classes/PHPExcel.php');
require_once(__DIR__ . '/../../app/config/database.php');
require_once(__DIR__ . '/../../app/config/accesos.php');
require_once(__DIR__ . '/../../app/models/subscription.php');
require_once(__DIR__ . '/../../app/models/department.php');
require_once(__DIR__ . '/../../app/providers/db_Provider.php');
require_once(__DIR__ . '/../../helpers/resources/dates.php');
$id = $_POST['id'] ?? null;
$start = isset($_POST['inicio']) ? $_POST['inicio'] : date('Y') . '01-01T00:00:00';
$end = isset($_POST['fin']) ? $_POST['fin'] : date('Y') . '12-31T23:59:59';
if ($id == null) {
  echo '<h3 align="center">OCURRIÓ UN ERROR [PARÁMETRO FALTANTE]. </h3><hr /><h4 align="center">Consejo 1: Trata de volver a <a href="../../">iniciar sesión</a></h4><hr /><h4 align="center">Consejo 2: Comuníquese con el administrador.</h4>';
  die();
}
$con = DBWebProvider::getSessionDataDB();
if ($con == null) {
  echo '<h3 align="center">OCURRIÓ UN ERROR. </h3><hr /><h4 align="center">Consejo 1: Trata de volver a <a href="../../">iniciar sesión</a></h4><hr /><h4 align="center">Consejo 2: Comuníquese con el administrador.</h4>';
  die();
}
$typeSub = new Subscriptiontype($con, $id);
// var_dump($typeSub);
// die();
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("STIS - BOLIVIA")
  ->setLastModifiedBy("STIS - BOLIVIA")
  ->setTitle("Suscripciones por plan")
  ->setSubject("suscripciones")
  ->setDescription("Reporte")
  ->setKeywords("reporte de suscripciones")
  ->setCategory("Reportes");
$hoja = $objPHPExcel->getActiveSheet();
$hoja->getColumnDimension('A')->setWidth(10);
$hoja->getColumnDimension('B')->setWidth(35);
$hoja->getColumnDimension('C')->setWidth(30);
$hoja->getColumnDimension('D')->setWidth(20);
$hoja->getColumnDimension('E')->setWidth(20);
$hoja->getColumnDimension('F')->setWidth(15);
$hoja->getColumnDimension('G')->setWidth(10);
$hoja->getColumnDimension('H')->setWidth(20);
$hoja->getColumnDimension('I')->setWidth(20);

$hoja->mergeCells('A1:I1');
$hoja->mergeCells('A2:I2');
$estiloCombinado = array(
  'font' => array(
    'bold' => true,
    'size' => 16,
    'color' => array('rgb' => '363636'),
  ),
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'c2c2c2'),
  ),
);
$estiloHeader = array(
  'alignment' => array(
    'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
  ),
  'fill' => array(
    'type' => PHPExcel_Style_Fill::FILL_SOLID,
    'color' => array('rgb' => 'DAEEF3'),
  )
);

$hoja->getStyle('A1')->applyFromArray($estiloCombinado);
$hoja->getStyle('A2')->applyFromArray($estiloCombinado);
$hoja->setCellValue('A1', ' REPORTE DE SUSCRIPCIONES POR TIPO DE PLAN ');
$hoja->setCellValue('A2', 'Suscripción plan ' . strtoupper($typeSub->name));
$hoja->setCellValue('A3', 'No.');
$hoja->setCellValue('B3', 'RAZON SOCIAL');
$hoja->setCellValue('C3', 'NIT.');
$hoja->setCellValue('D3', 'SUSCRITO EN');
$hoja->setCellValue('E3', 'CÓDIGO');
$hoja->setCellValue('F3', 'ESTADO');
$hoja->setCellValue('G3', 'PERIODOS');
$hoja->setCellValue('H3', 'PRECIO');
$hoja->setCellValue('I3', 'SUBTOTAL');
$hoja->getStyle('A3:I3')->applyFromArray($estiloHeader);

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
$i = 4;
$n = 1;


$suscripciones = Subscription::get_subscriptions_by_typeId($con, $id, ['start' => $start, 'end' => $end]);
// var_dump($suscripciones);
// die();
foreach ($suscripciones as $sub) {
  $hoja->setCellValue('A' . $i, $n);
  $hoja->setCellValue('B' . $i, $sub['paid_by_name'] ?? 'S/N');
  $hoja->setCellValue('C' . $i, $sub['nit'] ?? '000');
  $hoja->setCellValue('D' . $i, date('d/m/Y', strtotime($sub['subscribed_in'])));
  $hoja->setCellValue('E' . $i, $sub['code'] ?? 'XXXXXX');
  $hoja->setCellValue('F' . $i, HandleDates::expired($sub['expires_in']) ? 'VENCIDO' : 'VIGENTE');
  $hoja->setCellValue('G' . $i, $sub['period']);
  $hoja->setCellValue('H' . $i, $sub['price'] == null ? number_format(0, 2) : number_format($sub['price'], 2));
  $hoja->setCellValue('I' . $i, $sub['price'] == null ? number_format(0, 2) : number_format($sub['price'] * $sub['period'], 2));
  $i++;
  $n++;
}
// cambiar formato de una columna
$hoja->getStyle('C3:C' . $i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
// alinear texto a la derecha
$hoja->getStyle('C3:C' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$hoja->getStyle('F3:F' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

// cambiar nombre de hoja
$objPHPExcel->getActiveSheet()->setTitle('Suscripciones');
$i = $i - 1;
$hoja->getStyle('A3:I' . $i)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
$hoja->getStyle('I3:I' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="suscripciones_' . strtolower($typeSub->name) . '.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
// Limpia la memoria
$objPHPExcel->disconnectWorksheets();
unset($objPHPExcel);
