<?php

require('../configuracion/conexion.php');
require('../../configurar/configuracion.php');
require('../configuracion/datos_cne.php');
function getCentro($value, $accion)
{
  global $conexion_app;
  $value = trim($value);
  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion_app, "SELECT mcp, centro, nombre, pq FROM `rep_24` WHERE cedula = ?");
  } else {
    $stmt = mysqli_prepare($conexion_app, "SELECT CODIGO FROM `tablamesa` WHERE centro = ?");
  }
  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1) ? array($row['centro'], $row['nombre'], $row['mcp'], $row['pq']) : $row['CODIGO'];
  }
  return false;
}

//$elector =      $_POST["elector"];
//$responsable =  $_POST["responsable"];

$elector =      "10921393";
$responsable =  "27640176";

/*
if (date('Y-m-d') != '2025-07-27') {
  exit('LOS REPORTES SE DEBEN DE REALIZAR EL DIA DE LAS ELECCIONES');
}
*/
// Si no se reciben datos
/*
if (!isset($_POST["elector"]) || !isset($_POST["responsable"])) {
  echo 'FALTAN DATOS';
  exit;
}
*/


//$elector = "145652805";      // Cédula del elector (prueba)
//$responsable = "27640176";  // Cédula del responsable (prueba)

// Obtener datos del operador responsable
$resp_verificado = getOperador($responsable);

// Verificar si el responsable existe
/*
if (!$resp_verificado) {
  exit('RESPONSABLE NO VERIFICADO');
}
*/
// Extraer datos del operador
$tipo_user = $resp_verificado[0] ?? 0;
$operador_juventud = $resp_verificado[4] ?? 0;
$municipio_resp = $resp_verificado[5] ?? '';
$parroquia_resp = $resp_verificado[6] ?? '';
$verificado = $resp_verificado[1] ? 1 : 0;
// Obtener datos del elector desde el CNE
$datosCne = getCentro($elector, 1);
$voto = 'NC'; // Valor por defecto para el voto

// Verificación de centro electoral
if (!$datosCne) {
  // Si el elector no está en el centro, y el responsable es operador de juventud
  // if ($operador_juventud == '1') {
  $centro = $resp_verificado[2];
  $nombre = 'NO DISPONIBLE';
  $mcp = trim($municipio_resp);
  $pq = trim($parroquia_resp);
  /*} else {
    exit('NE'); // No es elector ni operador de juventud
  }*/
} else {
  // Datos del elector encontrados
}

// VERIFICAR SI EL ELECTOR YA HA VOTADO
$stmt = mysqli_prepare($conexion_app, "SELECT * FROM `flujo_electoral` WHERE cedula = ?");
$stmt->bind_param('s', $elector);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {

  if ($tipo_user == 'PUNTO' && $voto == 'OP') {
    echo "OP";
  } else {
    echo 'OK';
  }
  exit; // El elector ya ha votado, no se permite votar de nuevo
}
$stmt->close();

// DETERMINAR EL TIPO DE VOTO EN BASE A SI FUE CONTACTADO
$idUnoX10 = '0';
$stmt_unox10 = mysqli_prepare($conexion_app, "SELECT id, comunidad FROM `unox10` WHERE cdula = ?");
$stmt_unox10->bind_param('s', $elector);
$stmt_unox10->execute();
$result = $stmt_unox10->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $voto = 'VD';
  $idUnoX10 = 1;
  $comunidad = $row['comunidad'];
} else {
  $voto = 'NC';
  $idUnoX10 = '0';
  $comunidad = 'NDP';
}

$stmt_unox10->close();
// organizar para que 


// DETERMINAR EL TIPO DE VOTO EN BASE AL PADRON
$stmt_sig = mysqli_prepare($conexion, "SELECT voto FROM `padronelectoral` WHERE cedula = ?");
$stmt_sig->bind_param('s', $elector);
$stmt_sig->execute();
$result = $stmt_sig->get_result();
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $voto = $row['voto'];
}
$stmt_sig->close();
$conexion->close();


$stmt_o = $conexion_app->prepare("INSERT INTO flujo_electoral (cedula, nombre, centro, responsable, voto, unodiez, mcp, pq, verificado, juventudC comunidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt_o->bind_param("sssssssssss", $elector, $nombre, $centro, $responsable, $voto, $idUnoX10, $mcp, $pq, $verificado, $operador_juventud, $comunidad);
$stmt_o->execute();


if ($voto == 'OP') {
  echo "OP";
} else {
  echo $stmt_o ? 'OK' : 'ERROR';
}

$stmt_o->close();
