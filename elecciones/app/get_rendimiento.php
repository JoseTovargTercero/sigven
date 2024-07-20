<?php
require('../configuracion/conexion.php');
require('../../configurar/configuracion.php');
require('../configuracion/datos_cne.php');
// header json
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$hora = date('H_s_a');
$cedula = $_POST["responsable"];
$result = getOperador($cedula);


if (!$result) {
  echo json_encode([
    'centro' => 'No disponibles', 
    'reportes_del_centro' => '0', 
    'electores_centro' => '0', 
    'total_1x10_centro'=> '0', 
    'total_asistencia_1x10' => '0',
    'meta_inicial' => '0',
    'hora' => "$hora"]
  );
}

if ($cedula=='') {
  echo json_encode([
    'centro' => 'La información no esta disponible', 
    'reportes_del_centro' => '0', 
    'electores_centro' => '0', 
    'total_1x10_centro'=> '0', 
    'total_asistencia_1x10' => '0',
    'meta_inicial' => '0',
    'hora' => "$hora"]
  );
}
function abrev($nombre){
  // Lista de sustituciones
  $sustituciones = [
    'UNIDAD EDUCATIVA INTEGRAL BOLIVARIANA' => 'U.E.I.B',
    'UNIDAD EDUCATIVA' => 'UE',
    'CENTRO EDUCATIVO INTEGRAL BOLIVARIANO' => 'CEIB',
    'ESCUELA BASICA' => 'EB',
    'LICEO BOLIVARIANO' => 'LB',
    'CENTRO MOVIL' => 'CM',
    'ESCUELA TECNICA' => 'ET',
  ];

  // Reemplazar cada coincidencia en la lista de sustituciones
  foreach ($sustituciones as $original => $abreviado) {
    $nombre = str_replace($original, $abreviado, $nombre);
  }

  return trim($nombre);
}




$centro = $result[2];
$centro_nombre = $result[3];

/// cuenta de 'flujo_electoral' donde la columna centro sea igual a $centro
$sql = "SELECT COUNT(centro) AS cantidad FROM flujo_electoral WHERE centro = '$centro'";
$result = $conexion_app->query($sql);
$row = $result->fetch_assoc();
$cantidad_remportes = $row['cantidad'];






// ubtener la meta inicial
$sql = "SELECT META FROM metas_iniciales WHERE CODIGO = '$centro'";
$result = $conexion_app->query($sql);
$row = $result->fetch_assoc();
$meta_inicial = $row['META'];




// Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
$sql = "SELECT cedula FROM unox107 WHERE centro = ?";
$stmt = $conexion_app->prepare($sql);
$stmt->bind_param("s", $centro);
$stmt->execute();
$result = $stmt->get_result();
$total_1107 = $result->num_rows;
if ($total_1107 > 0) {
  while($row = $result->fetch_assoc()) {
      $cedulas[$row['cedula']] = true;
    }
  }
  $stmt->close();


  // Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
  $sql = "SELECT cdula FROM unox10 WHERE cv = ?";
  $stmt = $conexion_app->prepare($sql);
  $stmt->bind_param("s", $centro);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      if ($row['cdula']!='') {
        $cedulas[$row['cdula']] = true;
      }
      }
    }

if (count($cedulas) > $meta_inicial) {
  $meta_inicial = count($cedulas);
}


// usando $centro cuenta los electores de 'rep_24' por la columna 'centro'
$sql = "SELECT COUNT(centro) AS cantidad FROM rep_24 WHERE centro = '$centro'";
$result = $conexion_app->query($sql);
$row = $result->fetch_assoc();
$cantidad_elecotores = $row['cantidad'];



$asistentes1x10 = 0;
$stmt = mysqli_prepare($conexion_app, "SELECT cedula FROM `flujo_electoral` WHERE centro = ?");
$stmt->bind_param('s', $centro);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if ($cedulas[$row['cedula']]) {
      $asistentes1x10++;
    }
  }
}
$stmt->close();


echo json_encode([
  'centro' => abrev($centro_nombre), 
  'reportes_del_centro' => $cantidad_remportes, 
  'electores_centro' => "$cantidad_elecotores", 
  'total_1x10_centro'=> count($cedulas), 
  'total_asistencia_1x10' => $asistentes1x10,
  'meta_inicial' => ($cantidad_110 > $meta_inicial ? $cantidad_110 : $meta_inicial),
  'hora' => "$hora"
]);


?>
