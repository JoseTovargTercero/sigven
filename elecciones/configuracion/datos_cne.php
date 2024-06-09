<?php

function getDatosCne($cedula){
    $matriz = array();
    $cuenta = 0;
    $ar = fopen("http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula=" . $cedula . "", "r") or
        die("error");

        if ($ar == 'error') {
            return array('errorCne', 'errorCne');
        }
    while (!feof($ar)) {
        $linea = fgets($ar);

        if ($cuenta == 1) {
            $matriz[] = $linea;
            $cuenta = 0;
        }

        if (
            preg_match("/Cижdula:/", $linea) ||
            preg_match("/Nombre:/", $linea) ||
            preg_match("/Estado:/", $linea) ||
            preg_match("/Municipio:/", $linea) ||
            preg_match("/Parroquia:/", $linea) ||
            preg_match("/Centro:/", $linea) ||
            preg_match("/Direcciиоn:/", $linea)
        ) {
            $cuenta = 1;
        }
    }
    fclose($ar);


    if (sizeof($matriz) > 4) {
        $ci = strip_tags($matriz[0]);
        $nombre = strip_tags($matriz[0]);
        $estado = strip_tags($matriz[2]);
        $municipio = strip_tags($matriz[3]);
        $parroquia = strip_tags($matriz[4]);
        $centro = strip_tags($matriz[4]);


        return array(trim($nombre), trim($centro));
    }
}



?>