<?php 

function getCentro($value, $accion = 1)
{
  global $conexion_app;
  $value = trim($value);

  if ($accion == 1) {
    $stmt = mysqli_prepare($conexion_app, "SELECT centro, nombre, mcp, pq FROM rep_24 WHERE cedula = ?");
  } else {
    $stmt = mysqli_prepare($conexion_app, "SELECT CODIGO FROM tablamesa WHERE centro = ?");
  }

  if (!$stmt) return false;

  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return ($accion == 1)
      ? [$row['centro'], $row['nombre'], $row['mcp'], $row['pq']]
      : $row['CODIGO'];
  }

  return false;
}

function getCentroElegible($value)
{
  global $conexion_app;
  $value = trim($value);

  // Nota: Verifica si 'cdula' lleva 'e' ('cedula') en tu base de datos
  $sql = "SELECT T.CODIGO AS centro, T.centro AS nombre, T.MUN AS mcp, T.PAQ AS pq 
            FROM unox10 AS U 
            LEFT JOIN tablamesa AS T ON T.CODIGO = U.cv 
            WHERE U.cdula = ?";

  $stmt = mysqli_prepare($conexion_app, $sql);
  if (!$stmt) return false;

  $stmt->bind_param('s', $value);
  $stmt->execute();
  $result = $stmt->get_result();
  $stmt->close();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return [$row['centro'], $row['nombre'], $row['mcp'], $row['pq']];
  }

  return false;
}


?>
