<?php
// Puedes validar el origen, IP, etc. por seguridad
require('../configuracion/conexion.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? null;

    if ($token) {
        // Conexión a tu base de datos

        if ($conexion_app->connect_error) {
            die("Error de conexión: " . $conexion_app->connect_error);
        }

        // Puedes usar INSERT o REPLACE/UPDATE según el diseño
        $stmt = $conexion_app->prepare("INSERT INTO tokens_fcm (token) VALUES (?)");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        echo "Token guardado";
        $stmt->close();
        $conexion_app->close();
    } else {
        echo "Token no recibido";
    }
} else {
    echo "Método no permitido";
}
