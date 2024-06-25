<?php
/** Error reporting */
error_reporting(0);
include('../../configurar/configuracion.php');
require '../../elecciones/configuracion/conexion.php';

$municipio_id = $_POST['municipio_id'];
$continente_id = $_POST['continente_id'];
$pais_id = $_POST['pais_id'];


if($pais_id == '' || $continente_id == '' || $municipio_id == '') {
    define('PAGINA_INICIO', '../reportes.php');
    header('Location: ' . PAGINA_INICIO);
    exit();
}

$primeraHoja = 1;
$query = "SELECT pais.name1, continente.name2 FROM pais

LEFT JOIN continente ON pais.continente_id=continente.id


 WHERE pais.id='$pais_id'";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $nombreUbch = $row['name1'];
            $nombrePq = $row['name2'];
        }
}

// Declarar la consulta
$stmt = $conexion_app->prepare("SELECT cdula FROM `unox10`");
// Ejecutar la consulta
$stmt->execute();
// Obtener los resultados
$result = $stmt->get_result();
$cdulas = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cdulas[] = $row['cdula'];
    }
}
// Cerrar la conexión
$stmt->close();
$conexion_app->close();

// Función para verificar si un valor existe en el array
function verificarCdula($valor, $array) {
    return in_array($valor, $array) ? 'X' : '';
}



/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';
/** Include template */
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load("PHPExcel/Examples/templates/templatePadron.xls");

// Set document properties
$objPHPExcel->getProperties()->setCreator("SIGVEN Amazonas")
							 ->setLastModifiedBy("SIGVEN Amazonas")
							 ->setKeywords("SIGVEN")
							 ->setCategory("Reportes");


$sql2 = "SELECT nombre, cedula, telefono, cv, cargo FROM estructuraraas WHERE comunidad= ? AND tipocargo='1'";
$jefeCalle = $base->prepare($sql2);


$sql3 = "SELECT nombre, cedula, telefono, cv, calle, voto, act FROM padronelectoral WHERE comunidad= ? AND calle = ? ORDER BY `voto` DESC";
$padron = $base->prepare($sql3);

$sql4 = "SELECT * FROM ciudad WHERE pais_id='$pais_id'";
$comunidades = $base->prepare($sql4);

$contador = 1;
$rowA = 9;
$rowB = 9;
$rowC = 9;
$rowD = 9;
$rowE = 9;
$rowF = 9;
$rowG = 9;
$rowH = 9;
$rowI = 9;



$comunidades->execute(array());
while ($idRow = $comunidades->fetch(PDO::FETCH_ASSOC)) {

    $idCom = $idRow['id'];

if ($primeraHoja == 1) {
$primeraHoja = 2;



$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A4', ucwords(mb_strtolower($idRow['name'])))
->setCellValue('A6', ucwords(mb_strtolower($nombreUbch)).' - '.ucwords(mb_strtolower($nombrePq)));


$jefeCalle->execute(array($idCom));

$jefeCalleRegistrosArray = array();
while ($jefeCalleRegistros = $jefeCalle->fetch(PDO::FETCH_ASSOC)) {
    $jefeCalleRegistrosArray[] = $jefeCalleRegistros;
}

// Añadir fila adicional al final del array
$jefeCalleRegistrosArray[] = array(
    'nombre' => 'No disponible',
    'cedula' => 'No disponible',
    'telefono' => 'No disponible',
    'cv' => 'No disponible',
    'cargo' => 'Sin calle'
);


foreach ($jefeCalleRegistrosArray as $registro) {

    $cedula = $registro['cedula'];
 

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowA++, $contador++)
                ->setCellValue('B'.$rowB++, 'JC: '.ucwords(mb_strtolower($registro['cargo'])))
                ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($registro['nombre'])))
                ->setCellValue('D'.$rowD++, $registro['cedula'])
                ->setCellValue('E'.$rowE++, $registro['telefono'])
                ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($registro['cv'])))
                ->setCellValue('G'.$rowG++, 'VD')
                ->setCellValue('H'.$rowH++, '')
                ->setCellValue('I'.$rowI++, verificarCdula($registro['cedula'], $cdulas));
    
                
    $celda = $rowA - 1;


    $objPHPExcel->getActiveSheet()->getStyle('A'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('F'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);



                  
    $cargo = $registro['cargo'];



    $padron->execute(array($idCom, $cargo));
    while ($rowPadron = $padron->fetch(PDO::FETCH_ASSOC)) {

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$rowA++, $contador++)
        ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($rowPadron['calle'])))
        ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($rowPadron['nombre'])))
        ->setCellValue('D'.$rowD++, $rowPadron['cedula'])
        ->setCellValue('E'.$rowE++, $rowPadron['telefono'])
        ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($rowPadron['cv'])))
        ->setCellValue('G'.$rowG++, $rowPadron['voto'])
        ->setCellValue('H'.$rowH++, ($rowPadron['act'] == '0' ? '' : '1'))
        ->setCellValue('I'.$rowI++, verificarCdula($rowPadron['cedula'], $cdulas));
    }


}



// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle($idRow['name']);

$contador = 1;
$rowA = 9;
$rowB = 9;
$rowC = 9;
$rowD = 9;
$rowE = 9;
$rowF = 9;
$rowG = 9;
$rowH = 9;

}else {

 

// Clone worksheet
$clonedSheet = clone $objPHPExcel->getActiveSheet();


$clonedSheet
->setCellValue('A4', ucwords(mb_strtolower($idRow['name'])))
->setCellValue('A6', ucwords(mb_strtolower($nombreUbch)).' - '.ucwords(mb_strtolower($nombrePq)));






$jefeCalle->execute(array($idCom));

$jefeCalleRegistrosArray = array();
while ($jefeCalleRegistros = $jefeCalle->fetch(PDO::FETCH_ASSOC)) {
    $jefeCalleRegistrosArray[] = $jefeCalleRegistros;
}

// Añadir fila adicional al final del array
$jefeCalleRegistrosArray[] = array(
    'nombre' => 'No disponible',
    'cedula' => 'No disponible',
    'telefono' => 'No disponible',
    'cv' => 'No disponible',
    'cargo' => 'Sin calle'
);


foreach ($jefeCalleRegistrosArray as $registro) {


                    $clonedSheet
                    ->setCellValue('A'.$rowA++, $contador++)
                    ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($registro['cargo'])))
                    ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($registro['nombre'])))
                    ->setCellValue('D'.$rowD++, $registro['cedula'])
                    ->setCellValue('E'.$rowE++, $registro['telefono'])
                    ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($registro['cv'])))
                    ->setCellValue('G'.$rowG++, '')
                    ->setCellValue('H'.$rowH++, '')
                    ->setCellValue('I'.$rowI++, verificarCdula($registro['cedula'], $cdulas));


    
        $cargo = $registro['cargo'];
    
    
    
        $padron->execute(array($idCom, $cargo));
        while ($rowPadron = $padron->fetch(PDO::FETCH_ASSOC)) {
    
            $cedula = $rowPadron['cedula'];

    
    
            $clonedSheet
            ->setCellValue('A'.$rowA++, $contador++)
            ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($rowPadron['calle'])))
            ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($rowPadron['nombre'])))
            ->setCellValue('D'.$rowD++, $rowPadron['cedula'])
            ->setCellValue('E'.$rowE++, $rowPadron['telefono'])
            ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($rowPadron['cv'])))
            ->setCellValue('G'.$rowG++, $rowPadron['voto'])
            ->setCellValue('H'.$rowH++, ($rowPadron['act'] == '0' ? '' : '1'))
            ->setCellValue('I'.$rowI++, verificarCdula($rowPadron['cedula'], $cdulas));
        }
}

// Rename cloned worksheet
$clonedSheet->setTitle($idRow['name']);
$objPHPExcel->addSheet($clonedSheet);


$contador = 1;
$rowA = 9;
$rowB = 9;
$rowC = 9;
$rowD = 9;
$rowE = 9;
$rowF = 9;
$rowG = 9;
$rowH = 9;
}



}




$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Padron Electoral - '.$nombreUbch.' - SIGVEN - '.date('Y_m_d h-i a').'.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');