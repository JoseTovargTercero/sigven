<?php
require('../configuracion/conexion.php');



if (isset($_POST["cedula"]) && isset($_POST["cantidad"])) {
    $c = $_POST['cedula'];
    $cantidad = $_POST["cantidad"];
    $time = date('H-i a');
    //$c = '27640176';
    //$cantidad = '125';


    $result = getOperador($c);


    if ($result) {
      $id = $result[1];
      $tipo_user = $result[0];
      if ($tipo_user != 'inst') {
          // verificar si se ya tiene apertura el centro
          $stmt = mysqli_prepare($conexion, "SELECT * FROM `tablamesa` WHERE id = ? AND HORA_APERTURA=''");
          $stmt->bind_param('s', $id);
          $stmt->execute();
          $result = $stmt->get_result();
            if (!$result->num_rows > 0) {
              echo "Reporte de apertura enviado correctamente.";
            }else {
              $stmt2 = $conexion->prepare("UPDATE `tablamesa` SET `HORA_APERTURA` = ?, `ELECTORES_APERTURA` = ?, `USER_REPORTE` = ? WHERE id = ?");
              $stmt2->bind_param('sssi', $time, $cantidad, $tipo_user, $id);
              $stmt2->execute();
              if ($stmt2) {
                echo "Reporte de apertura enviado correctamente.";
              }
              $stmt2 -> close();
            }
          $stmt->close();

      }else {
        echo "Reporte de apertura enviado correctamente.";
      }

    }else {
      echo 'Rechazado: No se encontraron sus credenciales';
    }

}else {
    echo 'Rechazado : No ha iniciado sesión';
    exit();
}


?>