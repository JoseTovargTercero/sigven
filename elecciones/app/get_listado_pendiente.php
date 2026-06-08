<?php
// DATOS DE GITCOM
$usuario = 'ricardo';
$contrasena = '^ZwYa^.?hT7&';


$baseDeDatos = 'gitcom';
$conexion_gitcom = new mysqli('localhost', $usuario, $contrasena, $baseDeDatos);
$conexion_gitcom->set_charset('utf8');

if ($conexion_gitcom->connect_error) {
  die('Error de conexión: ' . $conexion_gitcom->connect_error);
}

date_default_timezone_set('America/Manaus');


$qry = $conexion_gitcom->prepare("SELECT cedula, coordenada_este, coordenada_norte FROM inf_habitantes WHERE cedula != 'N_C'");
$qry->execute();
$res = $qry->get_result();
$gitcom = [];

if ($res->num_rows > 0) {
  while ($row = $res->fetch_assoc()) {
    $gitcom[$row['cedula']] = [
      'coordenada_este' => $row['coordenada_este'],
      'coordenada_norte' => $row['coordenada_norte']
    ];
  }
}
$qry->close();


$conexion_gitcom->close();


// DATOS DE GITCOM


require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

header('Content-Type: application/json');

$cedula = $_POST["responsable"];

$result = getOperador($cedula);


if ($result) {
  $centro = $result[2];

  // Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
  $sql = "SELECT DISTINCT u.cdula AS cedula, u.nombre, u.jefe, u.telefono
FROM unox10 u
LEFT JOIN flujo_electoral f ON u.cdula = f.cedula
WHERE u.cv = ? 
  AND f.cedula IS NULL;";

  $stmt = $conexion_app->prepare($sql);
  $stmt->bind_param("s", $centro);
  $stmt->execute();

  $result = $stmt->get_result();

  $data = array();
  if ($result->num_rows > 0) {
    // Obtener los datos y convertirlos en un arreglo asociativo
    while ($row = $result->fetch_assoc()) {
      $data[] = array(
        'cedula' => $row['cedula'],
        'nombre' => $row['nombre'],
        'jefe' => $row['jefe'],
        'telefono' => $row['telefono'],
        'latitud' => $gitcom[$row['cedula']]['coordenada_norte'] ?? 'NG',
        'longitud' => $gitcom[$row['cedula']]['coordenada_este'] ?? 'NG'
      );
    }
  } else {
    $data = array(
      'cedula' => 'Participación alcanzada',
      'nombre' => '',
      'jefe' => '',
      'telefono' => ''
    );
  }

  // Cerrar la conexión a la base de datos
  $conexion_app->close();

  // Imprimir los resultados en formato JSON

} else {
  $data = array(
    'cedula' => 'No Disponible',
    'nombre' => 'No Disponible',
    'jefe' => 'No Disponible',
    'telefono' => 'No Disponible',
    'latitud' => 'No Disponible',
    'longitud' => 'No Disponible'
  );
}

// Suponiendo que tienes los datos almacenados en un array


// Empaqueta los datos en un JSON
$response = ['pendientes' => $data];

// Imprime el JSON
echo json_encode($response);
