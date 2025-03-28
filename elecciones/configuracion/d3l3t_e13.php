<?php
require_once 'conexion.php';
session_start();

$id_user = $_SESSION['id'];
$users = ['500', '540'];

if ($_SESSION['nivel'] == 1 && in_array($id_user, $users)) { // Verifica permisos

	$zipFilename = "archivos_exportados.zip";
	$csvFiles = [];

	// Función para generar CSV a partir de una consulta
	function generateCSV($conexion, $query, $filename)
	{
		global $csvFiles;
		$stmt = mysqli_prepare($conexion, $query);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result->num_rows > 0) {
			$filePath = __DIR__ . "/$filename";
			$csvFile = fopen($filePath, 'w');

			// Escribir encabezados
			$fields = $result->fetch_fields();
			$headers = [];
			foreach ($fields as $field) {
				$headers[] = $field->name;
			}
			fputcsv($csvFile, $headers);

			// Escribir datos
			while ($row = $result->fetch_assoc()) {
				fputcsv($csvFile, $row);
			}

			fclose($csvFile);
			$csvFiles[] = $filePath;
			return true; // Se generó correctamente
		}

		$stmt->close();
		return false; // No se generó
	}

	// Generar los CSV
	$csv1 = generateCSV($conexion_app, "SELECT * FROM tablamesa", "tablamesa.csv");
	$csv2 = generateCSV($conexion_app, "SELECT * FROM flujo_electoral", "flujo_electoral.csv");
	$csv3 = generateCSV($conexion_app, "SELECT jfcedula, jefe, telefono, tipo, cv FROM `unificado` GROUP BY jfcedula", "jefes_1x10_1x10x7.csv");


	if ($csv1 && $csv2 && $csv3) {
		// Crear archivo ZIP
		$zip = new ZipArchive();
		$zipPath = __DIR__ . "/$zipFilename";

		if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
			foreach ($csvFiles as $file) {
				$zip->addFile($file, basename($file));
			}
			$zip->close();


			// Eliminar archivos CSV temporales
			foreach ($csvFiles as $file) {
				unlink($file);
			}

			// TRUNCATE de las tablas después de verificar el ZIP**
			$conexion_app->query("TRUNCATE TABLE flujo_electoral");

			$stmt2 = $conexion_app->prepare("UPDATE `tablamesa` SET `USER_REPORTE` = '0',
			`HORA_APERTURA` = '0',
			`ELECTORES_APERTURA` = '0'");
			$stmt2->execute();
			$stmt2->close();

			// Enviar ZIP al cliente
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zipFilename . '"');
			header('Content-Length: ' . filesize($zipPath));
			readfile($zipPath);

			// Eliminar el ZIP después de la descarga
			unlink($zipPath);
			exit();
		} else {
			echo "Error al crear el archivo ZIP.";
		}
	} else {
		echo "Error al generar los archivos CSV.";
	}
}
