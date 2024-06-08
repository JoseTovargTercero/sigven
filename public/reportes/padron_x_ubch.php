<?php
/** Error reporting */
error_reporting(0);
include('../../configurar/configuracion.php');
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


$sql = "SELECT cedulaCarnet FROM psuv WHERE cedulaCarnet = ?";
$carnetizado = $base->prepare($sql);

$sql2 = "SELECT nombre, cedula, telefono, cv, cargo FROM estructuraraas WHERE comunidad= ? AND tipocargo='1'";
$jefeCalle = $base->prepare($sql2);


$sql3 = "SELECT nombre, cedula, telefono, cv, calle, voto FROM padronelectoral WHERE comunidad= ? AND calle = ? ORDER BY `voto` DESC";
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



$comunidades->execute(array());
while ($idRow = $comunidades->fetch(PDO::FETCH_ASSOC)) {

    $idCom = $idRow['id'];

if ($primeraHoja == 1) {
$primeraHoja = 2;



$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A4', ucwords(mb_strtolower($idRow['name'])))
->setCellValue('A6', ucwords(mb_strtolower($nombreUbch)).' - '.ucwords(mb_strtolower($nombrePq)));


$jefeCalle->execute(array($idCom));
while ($jefeCalleRegistros = $jefeCalle->fetch(PDO::FETCH_ASSOC)) {


    $cedula = $jefeCalleRegistros['cedula'];
    $carnetizado->execute(array($cedula));
    if ($carnetizado->fetchColumn() != '') {
    $c = 'S';
    }else {
        $c = '';
    }

    // Add some data
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowA++, $contador++)
                ->setCellValue('B'.$rowB++, 'JC: '.ucwords(mb_strtolower($jefeCalleRegistros['cargo'])))
                ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($jefeCalleRegistros['nombre'])))
                ->setCellValue('D'.$rowD++, $jefeCalleRegistros['cedula'])
                ->setCellValue('E'.$rowE++, $jefeCalleRegistros['telefono'])
                ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($jefeCalleRegistros['cv'])))
                ->setCellValue('G'.$rowG++, 'VD')
                ->setCellValue('H'.$rowH++, $c);

    
                
    $celda = $rowA - 1;


    $objPHPExcel->getActiveSheet()->getStyle('A'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('B'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('F'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$celda)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);



                  
    $cargo = $jefeCalleRegistros['cargo'];



    $padron->execute(array($idCom, $cargo));
    while ($rowPadron = $padron->fetch(PDO::FETCH_ASSOC)) {

        $cedula = $rowPadron['cedula'];
        $carnetizado->execute(array($cedula));
        if ($carnetizado->fetchColumn() != '') {
        $c = 'S';
        }else {
            $c = '';
        }


        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$rowA++, $contador++)
        ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($rowPadron['calle'])))
        ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($rowPadron['nombre'])))
        ->setCellValue('D'.$rowD++, $rowPadron['cedula'])
        ->setCellValue('E'.$rowE++, $rowPadron['telefono'])
        ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($rowPadron['cv'])))
        ->setCellValue('G'.$rowG++, $rowPadron['voto'])
        ->setCellValue('H'.$rowH++, $c);
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
    while ($jefeCalleRegistros = $jefeCalle->fetch(PDO::FETCH_ASSOC)) {
    
    
        $cedula = $jefeCalleRegistros['cedula'];
        $carnetizado->execute(array($cedula));
        if ($carnetizado->fetchColumn() != '') {
        $c = 's';
        }else {
            $c = '';
        }
    
    
    

                    $clonedSheet
                    ->setCellValue('A'.$rowA++, $contador++)
                    ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($jefeCalleRegistros['cargo'])))
                    ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($jefeCalleRegistros['nombre'])))
                    ->setCellValue('D'.$rowD++, $jefeCalleRegistros['cedula'])
                    ->setCellValue('E'.$rowE++, $jefeCalleRegistros['telefono'])
                    ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($jefeCalleRegistros['cv'])))
                    ->setCellValue('G'.$rowG++, '')
                    ->setCellValue('H'.$rowH++, $c);


    
        $cargo = $jefeCalleRegistros['cargo'];
    
    
    
        $padron->execute(array($idCom, $cargo));
        while ($rowPadron = $padron->fetch(PDO::FETCH_ASSOC)) {
    
            $cedula = $rowPadron['cedula'];
            $carnetizado->execute(array($cedula));
            if ($carnetizado->fetchColumn() != '') {
            $c = 's';
            }else {
                $c = '';
            }
    
    
            $clonedSheet
            ->setCellValue('A'.$rowA++, $contador++)
            ->setCellValue('B'.$rowB++, ucwords(mb_strtolower($rowPadron['calle'])))
            ->setCellValue('C'.$rowC++, ucwords(mb_strtolower($rowPadron['nombre'])))
            ->setCellValue('D'.$rowD++, $rowPadron['cedula'])
            ->setCellValue('E'.$rowE++, $rowPadron['telefono'])
            ->setCellValue('F'.$rowF++, ucwords(mb_strtolower($rowPadron['cv'])))
            ->setCellValue('G'.$rowG++, $rowPadron['voto'])
            ->setCellValue('H'.$rowH++, $c);
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