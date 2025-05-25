<?php
require_once 'config.php';


$conexion_app = new mysqli(constant('HOST'), constant('USER'), constant('PASSWORD'), constant('DB'));
$conexion_app->set_charset(constant('CHARSET'));

if ($conexion_app->connect_error) {
    die('Error de conexion_app: ' . $conexion_app->connect_error);
}

date_default_timezone_set('America/Manaus');


/* LIMPIAR DATOS */
function clear($campo)
{
    $campo = strip_tags($campo);
    $campo = filter_var($campo, FILTER_UNSAFE_RAW);
    $campo = htmlspecialchars($campo);
    return $campo;
}



function getOperador($cedula)
{
    global $conexion_app;

    // Preparar la consulta
    $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `operadores` WHERE cedula = ?");

    // Verificar si la preparación de la consulta falló
    if (!$stmt) {
        die('Error en mysqli_prepare: ' . mysqli_error($conexion_app));
    }

    $stmt->bind_param('s', $cedula);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stmt->close();
        return array('Operador', $row['id'], $row['CODIGO'], $row['centro']);
    }
    $stmt->close();
    return false;
}



function contar($condicion)
{
    global $conexion_app;

    //$condicion = "SELECT count(*) FROM $table WHERE $condicion";
    $stmt = $conexion_app->prepare($condicion);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_row();
    $galTotal = $row[0];

    return $galTotal;
}
