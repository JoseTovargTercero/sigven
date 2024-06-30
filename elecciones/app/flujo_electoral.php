<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

function getCentro($value, $accion){
  global $conexion_app;
  $value = trim($value);
  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion_app, "SELECT tablamesa.MUN, rep_24.centro, rep_24.nombre FROM `rep_24`
    LEFT JOIN tablamesa ON tablamesa.CODIGO = rep_24.centro
     WHERE cedula = ?");

  } else {
    $stmt = mysqli_prepare($conexion_app, "SELECT CODIGO FROM `tablamesa` WHERE centro = ?");
  }
  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1) ? array($row['centro'], $row['nombre'], $row['MUN']) : $row['CODIGO'];
  }
  return false;
}

if (isset($_POST["elector"]) && isset($_POST["responsable"])) {
  $elector = $_POST["elector"];
  $responsable = $_POST["responsable"];
  $resp_verificado = getOperador($responsable);


  if ($resp_verificado) {

    $tipo_user = $resp_verificado[0];
    
    $datosCne = getCentro($elector, 1);
    $centro = trim($datosCne[0]);
    $nombre = trim($datosCne[1]);
    $mcp = trim($datosCne[2]);
    $voto = 'NC';


    if ($nombre == '' || $centro == 'NDP' || $centro == false) {
      echo 'NE';
      exit();
    }


    $conexion_sigven = mysqli_connect("localhost", "user_sigven", "+ij*tK&[JH$,", "sigven");
    $conexion_sigven->set_charset('utf8');

    $stmt_sig = mysqli_prepare($conexion_sigven, "SELECT voto FROM `padronelectoral` WHERE cedula = ?");
    $stmt_sig->bind_param('s', $elector);
    $stmt_sig->execute();
    $result = $stmt_sig->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $voto = $row['voto'];
    }
    $stmt_sig->close();
    $conexion_sigven->close();

    $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `flujo_electoral` WHERE cedula = ?");
    $stmt->bind_param('s', $elector);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {



    
    $idUnoX10 = '0';
    $stmt_unox10 = mysqli_prepare($conexion_app, "SELECT id FROM `unox10` WHERE cdula = ?");
    $stmt_unox10->bind_param('s', $elector);
    $stmt_unox10->execute();
    $result = $stmt_unox10->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $voto = 'VD';
      $idUnoX10 = $row['id'];

    }
    $stmt_unox10->close();



      $stmt_o = $conexion_app->prepare("INSERT INTO flujo_electoral (cedula, nombre, centro, responsable, voto, unodiez, mcp) VALUES (?, ?, ?, ?, ?, ?, ?)");
      $stmt_o->bind_param("sssssss", $elector, $nombre, $centro, $responsable, $voto, $idUnoX10, $mcp);
      $stmt_o->execute();


      if ($tipo_user == 'Punto' && $voto == 'OP') {
        echo "OP";
      } else {
        echo $stmt_o ? 'OK' : 'ERROR';
      }

      $stmt_o->close();
    } else {

      if ($tipo_user == 'PUNTO' && $voto == 'OP') {
        echo "OP";
      } else {
        echo 'OK';
      }
      // Ya existia el reporte
    }
    $stmt->close();
  }
}
