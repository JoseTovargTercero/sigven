<?php
require('../configuracion/conexion.php');
require('../../configurar/configuracion.php');
require('../configuracion/datos_cne.php');



//$cedula = $_POST["responsable"];
$cedula = '17165224';


// consulta a la tabla 'flujo_electoral' en donde responsable = $cedula y agrupados por 'centro' me regresas el centro que tenga mayor cantidad, la cantidad y el nombre
$sql = "SELECT flujo_electoral.centro, COUNT(flujo_electoral.centro) AS cantidad, t.centro AS nombreCentro FROM flujo_electoral
LEFT JOIN tablamesa AS t ON t.CODIGO = flujo_electoral.centro
 WHERE responsable = '$cedula' GROUP BY centro ORDER BY cantidad DESC LIMIT 1";
$result = $conexion_app->query($sql);
$row = $result->fetch_assoc();
$centro = $row['centro'];
$centro_nombre = $row['nombreCentro'];
$cantidad_remportes = $row['cantidad'];
//echo $centro;

// usando $centro cuenta los electores de 'rep_24' por la columna 'centro'
$sql = "SELECT COUNT(centro) AS cantidad FROM rep_24 WHERE centro = '$centro'";
$result = $conexion_app->query($sql);
$row = $result->fetch_assoc();
$cantidad_elecotores = $row['cantidad'];


// consulta en 'unox10' los registros en donde cv=$centro y agregas a un array la columna cdula
$sql = "SELECT cdula FROM unox10 WHERE cv = '$centro'";
$result = $conexion_app->query($sql);
$cedulas = [];
while($row = $result->fetch_assoc()){
  $cedulas[] = $row['cdula'];
}


$asistentes1x10 = 0;
$stmt = mysqli_prepare($conexion_app, "SELECT cedula FROM `flujo_electoral` WHERE responsable = ?");
$stmt->bind_param('s', $cedula);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if (in_array($row['cedula'], $cedulas)) {
      $asistentes1x10++;
    }
  }
}
$stmt->close();


echo json_encode(['cod_centro' => $centro, 'centro' => $centro_nombre, 'reportes_del_centro' => $cantidad_remportes, 'electores' => $cantidad_elecotores, 'total_1x10_centro'=> count($cedulas), 'total_asistencia_1x10' => $asistentes1x10]);



?>
