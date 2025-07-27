<?php

/** Error reporting */
include('../../configurar/configuracion.php');
require '../../elecciones/configuracion/conexion.php';

error_reporting(0);
$centro = $_GET['c'];

/*
if ($centro == '') {
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}*/

$primeraHoja = 1;


$query = "SELECT centro FROM tablamesa WHERE CODIGO='$centro' LIMIT 1";
$result = $conexion_app->query($query);
$centro_nombre = ($result && $row = $result->fetch_assoc()) ? $row['centro'] : null;


$data = array();

// SACAR A LOS JEFES DE COMUNIDAD
$stmt = $conexion_app->prepare("SELECT * FROM `operadores` 
 WHERE CODIGO = '$centro' AND juventud = 2");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['CODIGO']]['comunidad'] = $row;
    }
}


// SACAR A LOS ELECTORES
$stmt = $conexion_app->prepare("SELECT unificado.*, cdula AS cedula, f.hora_reporte FROM `unificado`
LEFT JOIN flujo_electoral AS f ON f.cedula=unificado.cdula
 WHERE unificado.cv = '$centro' AND f.hora_reporte is null");
$stmt->execute();
$result = $stmt->get_result();
$electores = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data['cv']['electores'][$row['cdula']] = $row;
    }
}
// Cerrar la conexión
$stmt->close();


// pr con pre para los electores
echo "<pre>";
print_r($data);
echo "</pre>";




$contador = 1;
$row = 9;

$hora = date('h:i a');


foreach ($electores as $item) {
}
