<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

function getCentro($value, $accion)
{
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

if (isset($_POST["elector"]) && isset($_POST["responsable"])) {

  $elector = $_POST["elector"];
  $responsable = $_POST["responsable"];
  $resp_verificado = getOperador($responsable);
    if ($resp_verificado) {
        $tipo_user = $resp_verificado[0];
        $id = $resp_verificado[1];
        $datosCne = getDatosCne($elector);
        $centroRep = getCentro($elector, 1);
        $voto = 'NC';
        
        if ($datosCne) {
          $nombre = trim($datosCne[0]);
        }
        $centro = trim(($centroRep ? $centroRep : getCentro($datosCne[1], 0)));
        $centro = ($centro ? $centro : 'NDP');

       if ($nombre == '') {
          echo 'NE';
          exit();
        }


        $conexion_sigven=mysqli_connect("localhost","user_sigven","+ij*tK&[JH$,","sigven");
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


      if ($tipo_user == 'Punto' && $voto == 'OP') {
        echo "OP";
      } else {
          echo $stmt_o ? 'OK' : 'ERROR';
      }

      $stmt_o->close();

      
    } else {

      if ($tipo_user == 'PUNTO' && $voto == 'OP') {
        echo "OP";
      }else {
        echo 'OK';
      }
      // Ya existia el reporte
    }
    $stmt->close();
       
       
       
       
       
       
       
       
    }
}



