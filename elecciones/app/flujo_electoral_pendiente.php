<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

function getCentro($value, $accion){
  global $conexion_app;
  $value = trim($value);
  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion_app, "SELECT centro, nombre FROM `rep_24` WHERE cedula = ?");
  } else {
    $stmt = mysqli_prepare($conexion_app, "SELECT CODIGO FROM `tablamesa` WHERE centro = ?");
  }
  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1) ? array($row['centro'], $row['nombre']) : $row['CODIGO'];
  }else {
    return ($accion == 1) ? array(false, false) : false;

  }
  return false;
}


function verificarElector($elector, $responsable, $tipo_user) {
    global $conexion_app;

    $datosCne = getCentro($elector, 1);
    $centro = trim($datosCne[0]);
    $nombre = trim($datosCne[1]);
    $voto = 'NC';

    if ($nombre == '' || $centro == false) {
        return array("elector" => $elector, "status" => "NE");
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



    $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `flujo_electoral` WHERE cedula = ?");
    $stmt->bind_param('s', $elector);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt_o = $conexion_app->prepare("INSERT INTO flujo_electoral (cedula, nombre, centro, responsable, voto, unodiez) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt_o->bind_param("ssssss", $elector, $nombre, $centro, $responsable, $voto, $idUnoX10);
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
    $electores = json_decode($_POST["electores"], true);
    $responsable = $_POST["responsable"];
    
    $resp_verificado = getOperador($responsable);

    $response = array("results" => array());

    if ($resp_verificado) {
        $tipo_user = $resp_verificado[0];

        foreach ($electores as $elector) {
            $response["results"][] = verificarElector($elector, $responsable, $tipo_user);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
