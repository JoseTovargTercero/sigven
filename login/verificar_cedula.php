<?php

include('../elecciones/configuracion/conexion.php');

$doc = trim($_GET['c']);
$doc = addslashes($doc);
$doc = strip_tags($doc);





$stmt = mysqli_prepare($conexion_app, "SELECT *
	FROM jefes_instituciones WHERE cedula = ?");
$stmt->bind_param('s', $doc);
$stmt->execute();
if ($stmt->execute()) {
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			echo json_encode(array('status' => 'ok', 'institucion' => $row['Institucion']));
		}
	}else {
		echo json_encode(array('status' => 'rechazado', 'c' => $doc));
	}
}else {
	echo json_encode(array('status' => 'error', 'msg' => $conexion_app->error));
}
$stmt->close();

