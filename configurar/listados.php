<?php
include('../elecciones/configuracion/conexion.php');
session_start();

if ($_SESSION['nivel'] == 1) {
	// Leer JSON
	$datos = json_decode(file_get_contents('php://input'), true);
	$cedula = $datos['cedula'];



	$centros = [];
	$stmt = mysqli_prepare($conexion_app, "SELECT * FROM tablamesa");
	$stmt->execute();
	$result = $stmt->get_result();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			$centros[trim($row['CODIGO'])] = $row['centro'];
		}
	}
	$stmt->close();



	$stmt = mysqli_prepare($conexion_app, "SELECT * FROM `flujo_electoral` WHERE responsable = ?");
	$stmt->bind_param('s', $cedula);

	$stmt->execute();

	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		header("Content-Type: application/vnd.ms-excel; charset=utf-8");
		header("Content-Disposition: attachment; filename=listado_" . $cedula . ".xls");
		header("Pragma: no-cache");
		header("Expires: 0");

		echo "<table border='1'>";
		echo "<tr><th>#</th><th>Cedula</th><th>Nombre</th><th>Centro</th><th>Voto</th></tr>";
		$counto = 0;
		while ($row = $result->fetch_assoc()) {
			$centro = $centros[trim($row['centro'])] ?? 'No disponible';
			echo "<tr>";
			echo "<td>" . $counto++ . "</td>";
			echo "<td>" . htmlspecialchars($row['cedula']) . "</td>";
			echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
			echo "<td>" . htmlspecialchars($centro) . "</td>";
			echo "<td>" . htmlspecialchars($row['voto']) . "</td>";
			echo "</tr>";
		}

		echo "</table>";
	} else {
		//	http_response_code(204); // No Content
		echo 'error';
	}

	$stmt->close();
}
