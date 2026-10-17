<?php
require('../configuracion/conexion.php');

if (isset($_POST["cedula"]) && isset($_POST["cantidad"])) {
  $c = $_POST['cedula'];
  $cantidad = $_POST["cantidad"];
  $time = date('H-i a');

  $result = getOperador($c);


  if ($result) {
    $id = $result[1];
    $tipo_user = $result[0];
    $cod = $result[2];
    if ($tipo_user != 'inst') {
      // verificar si se ya tiene apertura el centro
      $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `tablamesa` WHERE CODIGO = ? AND HORA_APERTURA='0'");
      $stmt->bind_param('s', $cod);
      $stmt->execute();
      $result = $stmt->get_result();
      if ($result->num_rows > 0) {
        $stmt2 = $conexion_app->prepare("UPDATE `tablamesa` SET `HORA_APERTURA` = ?, `ELECTORES_APERTURA` = ?, `USER_REPORTE` = ? WHERE CODIGO = ?");
        $stmt2->bind_param('sssi', $time, $cantidad, $tipo_user, $cod);
        if ($stmt2->execute()) {
          echo "Reporte de apertura enviado correctamente.";
        } else {
          echo "No se pudo registrar la apertura.";
        }
        $stmt2->close();
      } else {
        echo "Reporte de apertura enviado correctamente.";
      }
      $stmt->close();
    } else {
      echo "Reporte de apertura enviado correctamente.";
    }
  } else {
    echo "Reporte de apertura enviado correctamente.";
  }
} else {
  echo 'Rechazado : No ha iniciado sesion';
  exit();
}
