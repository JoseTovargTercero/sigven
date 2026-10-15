<?php

require('../configuracion/conexion.php');
require('../../configurar/configuracion.php');
require('../configuracion/datos_cne.php');
require('helpers.php');

/*
// Reportar todos los errores de PHP
error_reporting(E_ALL);

// Mostrar los errores en la pantalla (útil para desarrollo)
ini_set('display_errors', '1');

// (Opcional) Mostrar también los errores de inicio de activación de PHP
ini_set('display_startup_errors', '1');

*/


if (!isset($_POST["elector"]) || !isset($_POST["responsable"])) {
  echo 'FALTAN DATOS';
  exit;
}



$elector =      $_POST["elector"];
$responsable =  $_POST["responsable"];

$elector = trim($elector);
$elector = str_replace("-", "", $elector);
$elector = str_replace(".", "", $elector);
$elector = str_replace(" ", "", $elector);
$elector = str_replace("V", "", $elector);
$elector = str_replace("E", "", $elector);

//$elector =      "10921393";
//$responsable =  "26013157";
$voto = 'NC'; // Valor por defecto para el voto



// Obtener datos del operador responsable
$resp_verificado = getOperador($responsable);

if (@$resp_verificado) {
  $tipo_user = $resp_verificado[0] ?? 0;
  $operador_juventud = $resp_verificado[4] ?? 0;
  $municipio_resp = $resp_verificado[5] ?? '';
  $parroquia_resp = $resp_verificado[6] ?? '';
  $verificado = $resp_verificado[1] ? 1 : 0;
} else {
  $tipo_user = 0;
  $operador_juventud = 0;
  $municipio_resp = '';
  $parroquia_resp = '';
  $verificado = 0;
}


// Verificar si el responsable existe

if (!$resp_verificado) {
  exit('RESPONSABLE NO VERIFICADO');
}




// Intentamos obtener el centro principal; si falla o está vacío, usamos el elegible
$datosCne = getCentro($elector, 1) ?: getCentroElegible($elector);

// Inicializamos las variables en null por seguridad
$centro = $nombre = $mcp = $pq = null;

// Verificamos de forma segura que sea un array válido con los 4 elementos
if (is_array($datosCne) && count($datosCne) >= 4) {
  [$centro, $nombre, $mcp, $pq] = $datosCne;
}

if ($nombre == null || $centro == null) {
  return array("elector" => $elector, "status" => "NE");
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
  $idUnoX10 = $row['jfcedula'];
  $comunidad = $row['comunidad'];
} else {
  $voto = 'NC';
  $idUnoX10 = '';
  $comunidad = 'ND';
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





// Query SQL preparada
$query = "INSERT INTO flujo_electoral 
            (cedula, nombre, centro, responsable, voto, unodiez, mcp, pq, verificado, juventud, comunidad) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_o = $conexion_app->prepare($query);

// Validamos que la consulta se haya preparado correctamente
if ($stmt_o) {
  // Si unodiez o verificado son enteros, puedes cambiar su 's' por 'i' (ejemplo: "sssssisssis")
  $stmt_o->bind_param(
    "sssssssssss",
    $elector,
    $nombre,
    $centro,
    $responsable,
    $voto,
    $idUnoX10,
    $mcp,
    $pq,
    $verificado,
    $operador_juventud,
    $comunidad
  );

  if ($stmt_o->execute()) {
    // Inserción exitosa
  } else {
    echo "Error al insertar: " . $stmt_o->error;
  }

  $stmt_o->close();
} else {
  echo "Error en la preparación SQL: " . $conexion_app->error;
}


if ($voto == 'OP') {
  echo "OP";
} else {
  echo $stmt_o ? 'OK' : 'ERROR';
}
