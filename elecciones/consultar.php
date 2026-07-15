<?php

require_once __DIR__ . '/configuracion/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: apikey, Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (!isset($_SERVER['HTTP_APIKEY']) || $_SERVER['HTTP_APIKEY'] !== APIKEY) {
    http_response_code(401);
    echo json_encode(['error' => 'API key invalida']);
    exit;
}

if (!isset($_POST['cedula']) || empty(trim($_POST['cedula']))) {
    http_response_code(400);
    echo json_encode(['error' => 'cedula requerida']);
    exit;
}

$cedula = trim($_POST['cedula']);

try {
    $mysqli = new mysqli(HOST, USER, PASSWORD, DB);
    $mysqli->set_charset(CHARSET);

    $stmt = $mysqli->prepare("SELECT * FROM rep_24 WHERE cedula = ?");
    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        echo json_encode($row);
    } else {
        echo json_encode(['mensaje' => 'No se encontro la cedula']);
    }

    $stmt->close();
    $mysqli->close();
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la consulta']);
}
