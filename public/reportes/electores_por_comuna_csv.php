<?php
error_reporting(0);
include('../../configurar/configuracion.php');
include('../../elecciones/configuracion/conexion.php'); // CONEXION A LA BASE DE DATOS DE LAS ELECCIONES

if ($_SESSION['nivel'] != 1) {
    define('PAGINA_INICIO', '../../index.php');
    header('Location: ' . PAGINA_INICIO);
    exit;
}

/*
 * Reporte CSV: Total de electores por comuna (CODIGO_SITUR)
 *
 * Consulta optimizada en un solo paso:
 *   1. Agrupa flujo_electoral por centro y cuenta registros.
 *   2. JOIN con centrosxcomunas usando CODIGO_CENTRO para obtener CODIGO_SITUR.
 *   3. Agrupa nuevamente por CODIGO_SITUR y suma los totales.
 *
 * Formato de salida CSV:
 *   CÓDIGO COM,CORTE PREVIO
 */

$sql = "
    SELECT
        cxc.CODIGO_SITUR AS codigo_com,
        SUM(fe.total)    AS corte_previo
    FROM (
        SELECT centro, COUNT(*) AS total
        FROM flujo_electoral
        GROUP BY centro
    ) AS fe
    INNER JOIN centrosxcomunas AS cxc
        ON cxc.CODIGO_CENTRO = fe.centro
    GROUP BY cxc.CODIGO_SITUR
    ORDER BY cxc.CODIGO_SITUR
";

$result = $conexion_app->query($sql);

if (!$result) {
    http_response_code(500);
    exit('Error en la consulta: ' . $conexion_app->error);
}

// Enviar cabeceras para descarga de CSV
$filename = 'electores_por_comuna_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$output = fopen('php://output', 'w');

// BOM UTF-8 para compatibilidad con Excel
fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

// Cabecera del CSV
fputcsv($output, ['CÓDIGO COM', 'CORTE PREVIO']);

// Filas de datos
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['codigo_com'], $row['corte_previo']]);
}

fclose($output);
$result->free();
exit;
