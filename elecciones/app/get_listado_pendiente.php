<?php
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');

header('Content-Type: application/json');

$cedula = $_POST["responsable"];

$result = getOperador($cedula);


if ($result) {
  $centro = $result[2];


// Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
$sql = "SELECT DISTINCT cedula, nombre, jefe, telefono
FROM (
    SELECT cedula, nombre, jefe, telefono
    FROM unox107
    WHERE centro = ?
    UNION
    SELECT cdula AS cedula, nombre, jefe, telefono
    FROM unox10
    WHERE cv = ?
) AS combined
WHERE cedula NOT IN (
    SELECT cedula
    FROM flujo_electoral
)";

$stmt = $conexion_app->prepare($sql);
$stmt->bind_param("ss", $centro, $centro);
$stmt->execute();

$result = $stmt->get_result();

$data = array();
if ($result->num_rows > 0) {
// Obtener los datos y convertirlos en un arreglo asociativo
while($row = $result->fetch_assoc()) {
    $data[] = array(
        'cedula' => $row['cedula'],
        'nombre' => $row['nombre'],
        'jefe' => $row['jefe'],
        'telefono' => $row['telefono']
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

}else {
  $data = array(
    'cedula' => 'No Disponible',
    'nombre' => 'No Disponible',
    'jefe' => 'No Disponible',
    'telefono' => 'No Disponible'
  );
}

// Suponiendo que tienes los datos almacenados en un array


// Empaqueta los datos en un JSON
$response = ['pendientes' => $data];

// Imprime el JSON
echo json_encode($response);
