<?php
include('../configurar/configuracion.php');
include('../elecciones/configuracion/conexion.php'); // CONEXION A LA BASE DE DATOS DE LAS ELECCIONES

header('Content-Type: application/json');

// Obtener datos como array asociativo
$input = json_decode(file_get_contents('php://input'), true);
$response = [];

if (is_array($input)) {
    foreach ($input as $key => $value) {
        $nombre = 'No rep';

        $stmt = $conexion->prepare("SELECT nombre FROM rep28 WHERE cedula = ?");
        if ($stmt) {
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $stmt->bind_result($nombreDB);
            if ($stmt->fetch()) {
                $nombre = $nombreDB;
            }
            $stmt->close();
        }

        $response[] = [
            'nombre' => $nombre,
            'key' => $key,
            'value' => $value
        ];
    }
}

echo json_encode($response);
