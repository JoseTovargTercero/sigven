<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');
/*
function getCentro($value, $accion){
  global $conexion_app;
  $value = trim($value);
  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion_app, "SELECT mcp, centro, nombre FROM `rep_24` WHERE cedula = ?");
  } else {
    $stmt = mysqli_prepare($conexion_app, "SELECT CODIGO FROM `tablamesa` WHERE centro = ?");
  }
  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1) ? array($row['centro'], $row['nombre'], $row['mcp']) : $row['CODIGO'];
  }
  return false;
}*/



header('Content-Type: application/json');
/*
$cedula = $_POST["responsable"];

$result = getOperador($cedula);


if ($result) {
  $centro = $result[2];

}else {
  echo "Reporte de apertura enviado correctamente.";
}*/

// Suponiendo que tienes los datos almacenados en un array
$data = [
    ['nombre' => 'Elemento 1', 'detalle' => 'Detalle 1'],
    ['nombre' => 'Elemento 2', 'detalle' => 'Detalle 2'],
];

// Empaqueta los datos en un JSON
$response = ['pendientes' => $data];

// Imprime el JSON
echo json_encode($response);
