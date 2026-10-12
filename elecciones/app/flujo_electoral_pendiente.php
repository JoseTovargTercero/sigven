<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');
require('helpers.php');


function verificarElector($elector, $responsable, $tipo_user, $verificado = 0, $juventud = 0)
{
  global $conexion_app;
  require('../../configurar/configuracion.php');


  /* $datosCne = getCentro($elector, 1);
  $centro = trim($datosCne[0]);
  $nombre = trim($datosCne[1]);
  $mcp = trim($datosCne[2]);
  $pq = trim($datosCne[3]);*/
  // Intentamos obtener el centro principal; si falla o está vacío, usamos el elegible
  $datosCne = getCentro($elector, 1) ?: getCentroElegible($elector);

  // Inicializamos las variables en null por seguridad
  $centro = $nombre = $mcp = $pq = null;

  // Verificamos de forma segura que sea un array válido con los 4 elementos
  if (is_array($datosCne) && count($datosCne) >= 4) {
    [$centro, $nombre, $mcp, $pq] = $datosCne;
  } 

  $voto = 'NC';


  if ($nombre == null || $centro == null) {
    return array("elector" => $elector, "status" => "NE");
  }




  $idUnoX10 = '0';
  $stmt_unox10 = mysqli_prepare($conexion_app, "SELECT id, jfcedula, comunidad FROM `unox10` WHERE cdula = ?");
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




  $query = "SELECT voto FROM `padronelectoral` WHERE cedula = ?";
  $stmt_sig = $conexion->prepare($query);
  $stmt_sig->bind_param("s", $elector);
  $stmt_sig->execute();
  $result = $stmt_sig->get_result();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $voto = $row['voto'];
  }


  $stmt_sig->close();
  $conexion->close();




  $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `flujo_electoral` WHERE cedula = ?");
  $stmt->bind_param('s', $elector);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows == 0) {
    $stmt_o = $conexion_app->prepare("INSERT INTO flujo_electoral (cedula, nombre, centro, responsable, voto, unodiez, mcp, pq, verificado, juventud, comunidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_o->bind_param("sssssssssss", $elector, $nombre, $centro, $responsable, $voto, $idUnoX10, $mcp, $pq, $verificado, $juventud, $comunidad);
    $stmt_o->execute();
    $stmt_o->close();

    if ($tipo_user == 'Punto' && $voto == 'OP') {
      return array("elector" => $elector, "status" => "OP");
    } else {
      return array("elector" => $elector, "status" => $stmt_o ? "OK" : "ERROR");
    }
  } else {
    if ($tipo_user == 'PUNTO' && $voto == 'OP') {
      return array("elector" => $elector, "status" => "OP");
    } else {
      return array("elector" => $elector, "status" => "OK");
    }
  }
}

if (isset($_POST["electores"]) && isset($_POST["responsable"])) {
  //if (true) {
  $electores = json_decode($_POST["electores"], true);
  // $electores = array('14565280', '27640176');
  $responsable = $_POST["responsable"];
  // $responsable = '27640176';

  $resp_verificado = getOperador($responsable);

  $response = array("results" => array());

  $tipo_user = 0;
  $verificado = 0;
  $juventud = 0;
  if ($resp_verificado) {
    $tipo_user = $resp_verificado[0];
    $verificado = $resp_verificado[1] ? 1 : 0;
    $juventud = $resp_verificado[4] ?? 0;
  }

  foreach ($electores as $elector) {
    $elector = trim($elector);
    $elector = str_replace("-", "", $elector);
    $elector = str_replace(".", "", $elector);
    $elector = str_replace(" ", "", $elector);
    $elector = str_replace("V", "", $elector);
    $elector = str_replace("E", "", $elector);
    $response["results"][] = verificarElector($elector, $responsable, $tipo_user, $verificado, $juventud);
  }

  //header('Content-Type: application/json');
  echo json_encode($response);
}
