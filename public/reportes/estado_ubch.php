<?php

/** Error reporting */
include('../../configurar/configuracion.php');
require '../../elecciones/configuracion/conexion.php';


$centro = $_GET['c'];


if ($centro == '') {
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

$primeraHoja = 1;
$query = "SELECT * FROM tablamesa WHERE CODIGO='$centro'";
$search = $conexion_app->query($query);
if ($search->num_rows > 0) {
    while ($row = $search->fetch_assoc()) {
        $centro_nombre = $row['centro'];
    }
}


// LOISTO
// Declarar la consulta
$stmt = $conexion_app->prepare("SELECT unox10.*, cdula AS cedula, f.hora_reporte  FROM `unox10`
LEFT JOIN flujo_electoral AS f ON f.cedula=unox10.cdula
 WHERE unox10.cv = '$centro'");
// Ejecutar la consulta
$stmt->execute();
// Obtener los resultados
$result = $stmt->get_result();
$electores = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $electores[$row['cdula']] = $row;
    }
}
// Cerrar la conexión
$stmt->close();

// Declarar la consulta
$stmt = $conexion_app->prepare("SELECT unox107.*, f.hora_reporte  FROM `unox107`
LEFT JOIN flujo_electoral AS f ON f.cedula=unox107.cedula
 WHERE unox107.centro = '$centro'");
// Ejecutar la consulta
$stmt->execute();
// Obtener los resultados
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $electores[$row['cedula']] = $row;
    }
}
// Cerrar la conexión
$stmt->close();





// Función para verificar si un valor existe en el array
function verificarCdula($valor, $array)
{
    if (@$array[$valor]) {
        return $array[$valor][0];
    } else {
        return '';
    }
    return in_array($valor, $array) ? 'X' : '';
}




/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';
/** Include template */
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load("PHPExcel/Examples/templates/templateFlujo.xls");

// Set document properties
$objPHPExcel->getProperties()->setCreator("SIGVEN Amazonas")
    ->setLastModifiedBy("SIGVEN Amazonas")
    ->setKeywords("SIGVEN")
    ->setCategory("Reportes");


$contador = 1;
$row = 9;

$hora = date('h:i a');

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A7', 'Hora de reporte: ' . $hora)
    ->setCellValue('A6', ucwords(mb_strtolower($centro_nombre)));


foreach ($electores as $item) {
    $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A' . $row, $contador++)
    ->setCellValue('B' . $row, ucwords(mb_strtolower($item['nombre'])))
    ->setCellValue('C' . $row, $item['cedula'])
    ->setCellValue('D' . $row, $item['jefe'])
    ->setCellValue('E' . $row, $item['telefono'])
    ->setCellValue('F' . $row, ($item['hora_reporte'] != '' ? '':'PENDIENTE'));
    $row++;
}


// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Flujo electoral');



$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $centro_nombre . ' - SIGVEN - ' . date('Y_m_d h-i a') . '.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
