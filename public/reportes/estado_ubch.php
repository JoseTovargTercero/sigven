<?php

/** Error reporting */
include('../../configurar/configuracion.php');
require '../../elecciones/configuracion/conexion.php';
require_once __DIR__ . '/lib//vendor/autoload.php';



$centro = $_GET['c'];


if ($centro == '') {
    header("Location:" . $_SERVER['HTTP_REFERER']);
    exit();
}

$primeraHoja = 1;


$query = "SELECT centro FROM tablamesa WHERE CODIGO='$centro' LIMIT 1";
$result = $conexion_app->query($query);
$centro_nombre = ($result && $row = $result->fetch_assoc()) ? $row['centro'] : null;


$data = array();

// SACAR A LOS JEFES DE COMUNIDAD
$stmt = $conexion_app->prepare("SELECT * FROM `operadores` 
 WHERE CODIGO = '$centro' AND juventud = 2");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['comunidad']]['jefe_comnuidad'] = $row;
    }
}

$faltantes = 0;
// SACAR A LOS ELECTORES
$stmt = $conexion_app->prepare("SELECT unificado.*, cdula AS cedula, f.hora_reporte FROM `unificado`
LEFT JOIN flujo_electoral AS f ON f.cedula=unificado.cdula
 WHERE unificado.cv = '$centro' AND f.hora_reporte is null");
$stmt->execute();
$result = $stmt->get_result();
$electores = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[$row['comunidad']]['electores'][] = $row;
        $faltantes++;
    }
}
// Cerrar la conexión
$stmt->close();


// pr con pre para los electores
/*
echo "<pre>";
print_r($data);
echo "</pre>";
*/


$html = "<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
        color: #000;
    }

    .comunidad-container {
        margin-bottom: 25px;
        padding-bottom: 10px;
    }

    .comunidad-title {
        font-weight: bold;
        font-size: 13px;
        text-transform: uppercase;
    }

    .jefe-info {
        font-weight: bold;
        margin-top: 5px;
    }

    .telefono {
        font-weight: bold;
    }

    .electores-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    .electores-table th,
    .electores-table td {
        border: 1px solid #ccc;
        padding: 3px 5px;
        text-align: left;
    }

    .electores-table th {
        background: #eee;
    }

    .electores-table td:first-child {
        width: 30px;
        text-align: center;
    }

    .text-muted {
        color: #666;
        font-size: 10px;
    }
</style>";



$html .= '<h1 style="text-align: center; margin-bottom: 0">' . $centro_nombre . '</h1>
<h3  style="text-align: center; margin-top: 0; margin-bottom: 0">Faltan por votar: ' . $faltantes . '</h3>
<p style="text-align: center;  margin-top: 5px;">' . date('Y-m-d H:i:s') . '</p>
';



// Imprimir cada comunidad
foreach ($data as $comunidad => $datos) {
    $html .= '<div class="comunidad-container">';
    $html .= '<div class="comunidad-title">COMUNIDAD ' . strtoupper($comunidad) . '</div>';

    if (isset($datos['jefe_comnuidad'])) {
        $jefe = $datos['jefe_comnuidad'];
        $html .= '<div class="jefe-info">JEFE (A) COMUNIDAD: ' . strtoupper($jefe['nombre']) . '</div>';
        $html .= '<div class="telefono">TELÉFONO: <span>' . $jefe['telefono'] . '</span></div>';
    } else {
        $html .= '<div class="text-muted">Jefe no definido</div>';
    }

    $html .= '<table class="electores-table">';
    $html .= '<tr>
                <th style="width: 10%">#</th>
                <th style="width: 20%">Cédula</th>
                <th style="width: 50%">Apellidos y Nombres</th>
                <th style="width: 20%">Teléfono</th>
            </tr>';

    if (isset($datos['electores']) && count($datos['electores']) > 0) {
        $i = 1;
        foreach ($datos['electores'] as $elector) {
            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . $elector['cedula'] . '</td>';
            $html .= '<td>' . strtoupper($elector['nombre']) . '</td>';
            $html .= '<td>' . (!empty($elector['telefono']) && $elector['telefono'] != '0' ? $elector['telefono'] : '') . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="4" class="text-muted">No hay electores</td></tr>';
    }

    $html .= '</table>';
    $html .= '</div>';
}

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$mpdf->Output();
