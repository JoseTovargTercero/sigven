<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

function getCentro($value, $accion){
  global $conexion;
  $value = trim($value);
  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion, "SELECT centro FROM `rep_24` WHERE cedula = ?");
  } else {
    $stmt = mysqli_prepare($conexion, "SELECT CODIGO FROM `tablamesa` WHERE centro = ?");
  }
  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1) ? $row['centro'] : $row['CODIGO'];
  }
  return false;
}

function verificarElector($elector, $responsable, $tipo_user) {
    global $conexion;

    $datosCne = getDatosCne($elector);
    $centroRep = getCentro($elector, 1);
    $voto = 'NC';

    if ($datosCne) {
        $nombre = trim($datosCne[0]);
    }
    $centro = trim(($centroRep ? $centroRep : getCentro($datosCne[1], 0)));
    $centro = ($centro ? $centro : 'NDP');

    if ($nombre == '') {
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

    $stmt = mysqli_prepare($conexion, "SELECT * FROM `flujo_electoral` WHERE cedula = ?");
    $stmt->bind_param('s', $elector);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt_o = $conexion->prepare("INSERT INTO flujo_electoral (cedula, nombre, centro, responsable, voto) VALUES (?, ?, ?, ?, ?)");
        $stmt_o->bind_param("sssss", $elector, $nombre, $centro, $responsable, $voto);
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
    
    // Aquí debes verificar al responsable, este es solo un ejemplo de verificación
    $resp_verificado = array('Operador', 0);

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
