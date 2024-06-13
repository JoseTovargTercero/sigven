<?php
require_once 'config.php';


$conexion = new mysqli(constant('HOST'), constant('USER'), constant('PASSWORD'), constant('DB'));
$conexion->set_charset(constant('CHARSET'));

if ($conexion->connect_error) {
	die('Error de conexion: ' . $conexion->connect_error);
}

date_default_timezone_set('America/Manaus');


/* LIMPIAR DATOS */
function clear($campo){
	$campo = strip_tags($campo);
	$campo = filter_var($campo, FILTER_UNSAFE_RAW);
	$campo = htmlspecialchars($campo);
	return $campo;
}


// Obtener operador
function getOperador($cedula){
	global $conexion;

	return array('Punto', 0);

	$stmt = mysqli_prepare($conexion, "SELECT * FROM `operadores_institucional` WHERE cedula = ?");
	$stmt->bind_param('s', $cedula);
	$stmt->execute();
	$result = $stmt->get_result();
	  if ($result->num_rows > 0) {
		return array('Inst', 0);
	  }
	$stmt->close();

	$stmt = mysqli_prepare($conexion, "SELECT * FROM `tablamesa` WHERE OPERADOR = ? OR OPERADOR_PUNTO = ?");
	$stmt->bind_param('ss', $cedula, $cedula);
	$stmt->execute();
	$result = $stmt->get_result();
	  if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
		  if ($row['OPERADOR'] == $cedula) {
			return array('Operador', $row['id']);
		  }else {
			return array('Punto', $row['id']);
		  }
		}
	  }
	$stmt->close();
	return false;
  }

?>