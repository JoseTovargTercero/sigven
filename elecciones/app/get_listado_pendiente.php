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
              SELECT cdula AS cedula, nombre, jefe, telefono
              FROM unox10
              WHERE cv = ?
          ) AS combined
          WHERE cedula NOT IN (
              SELECT cedula
              FROM flujo_electoral
          )";

  $stmt = $conexion_app->prepare($sql);
  $stmt->bind_param("s", $centro);
  $stmt->execute();

  $result = $stmt->get_result();

  $data = array();
  $cedulas_a_consultar = array(); // Guardará las cédulas para la API externa

  if ($result->num_rows > 0) {
    // Primera pasada: Obtener los datos base y guardar las cédulas
    while ($row = $result->fetch_assoc()) {
      $cedula_limpia = trim((string)$row['cedula']);

      $data[] = array(
        'cedula' => $row['cedula'],
        'nombre' => $row['nombre'],
        'jefe' => $row['jefe'],
        'telefono' => $row['telefono'],
        'latitud' => '', // Se llenará más abajo
        'longitud' => '' // Se llenará más abajo
      );

      if ($cedula_limpia !== '') {
        $cedulas_a_consultar[] = $cedula_limpia;
      }
    }

    // ==========================================
    // PETICIÓN A LA API DE CÉDULAS (CURL)
    // ==========================================
    if (!empty($cedulas_a_consultar)) {
      $url_api = 'https://gitcom-ve.com/back/api_consulta_cedulas.php';

      $payload = json_encode([
        'token' => '123456789-9',
        'cedulas' => $cedulas_a_consultar
      ]);

      $ch = curl_init($url_api);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
      // Descomenta la siguiente línea si tienes problemas locales con certificados SSL de tu dominio
      // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

      $response_api = curl_exec($ch);
      curl_close($ch);

      if ($response_api) {
        $resultado_coor = json_decode($response_api, true);

        // Si la API devolvió datos exitosamente, creamos un mapa indexado por cédula
        if (isset($resultado_coor['success']) && $resultado_coor['success'] && isset($resultado_coor['datos'])) {
          $coordenadas_map = array();
          foreach ($resultado_coor['datos'] as $hab) {
            $coordenadas_map[$hab['cedula']] = [
              'lat' => $hab['coordenada_norte'],
              'lon' => $hab['coordenada_este']
            ];
          }

          // Segunda pasada: Cruzar los datos y asignar latitud y longitud sin romper el formato
          foreach ($data as $key => $item) {
            $c_actual = trim((string)$item['cedula']);
            if (isset($coordenadas_map[$c_actual])) {
              $data[$key]['latitud'] = $coordenadas_map[$c_actual]['lat'];
              $data[$key]['longitud'] = $coordenadas_map[$c_actual]['lon'];
            }
          }
        }
      }
    }
    // ==========================================

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
} else {
  $data = array(
    'cedula' => 'No Disponible',
    'nombre' => 'No Disponible',
    'jefe' => 'No Disponible',
    'telefono' => 'No Disponible'
  );
}

// Mantiene el formato original intacto
$response = ['pendientes' => $data];

// Imprime el JSON final
echo json_encode($response);
