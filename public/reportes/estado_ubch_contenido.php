<?php

/** Error reporting */
include('../../configurar/configuracion.php');
require '../../elecciones/configuracion/conexion.php';
//require_once __DIR__ . '/lib/vendor/autoload.php';

header('Content-Type: application/json');
    /*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL)*/;

$input = json_decode(file_get_contents("php://input"), true);
$centro = $input['c'];
$pass = $input['pass'];



if ($pass != 'Sigven2025mcp') {

    echo json_encode([
        'status' => 'error',
        'html' => '<div style="text-align: center; margin-top: 50px;">
            <h1>Acceso Denegado</h1>
            <p>La contraseña ingresada es incorrecta.</p>'
    ]);

    exit();
}



$primeraHoja = 1;


$query = "SELECT centro FROM tablamesa WHERE CODIGO='$centro' LIMIT 1";
$result = $conexion_app->query($query);
$centro_nombre = ($result && $row = $result->fetch_assoc()) ? $row['centro'] : null;


$data = array();

// SACAR A LOS JEFES DE COMUNIDAD
$stmt = $conexion_app->prepare("SELECT * FROM `operadores` 
 WHERE juventud = 2");
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
        .btn-success {
  display: inline-block;
  font-weight: 400;
  color: #fff;
  background-color: #198754;
  border: 1px solid #198754;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.375rem;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.btn-success:hover {
  background-color: #157347;
  border-color: #146c43;
}

.btn-success:focus {
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.5);
}

.btn-danger {
  display: inline-block;
  font-weight: 400;
  color: #fff;
  background-color: #dc3545;
  border: 1px solid #dc3545;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.375rem;
  text-align: center;
  text-decoration: none;
  cursor: pointer;
  transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
}

.btn-danger:hover {
  background-color: #bb2d3b;
  border-color: #b02a37;
}

.btn-danger:focus {
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.5);
}

@media print {
  .no-print {
    display: none !important;
  }
}

</style>";



$html .= '<h1 style="text-align: center; margin-bottom: 0">' . $centro_nombre . '</h1>
<h3  style="text-align: center; margin-top: 0; margin-bottom: 0">Faltan por votar: ' . $faltantes . '</h3>
<p style="text-align: center;  margin-top: 5px;">' . date('Y-m-d H:i:s') . '</p>';

// agrega un boton para descargar con la clase mensaje y btn-danger
$html .= '<div style="text-align: center; margin-top: 10px;">
<button class="btn-danger no-print" onclick="imprimirContenido()">DESCARGAR</button>
</div>';



function formatearNombre($nombre)
{
    $nombre = mb_strtolower($nombre, 'UTF-8');     // Pone todo en minúsculas
    $nombre = mb_convert_case($nombre, MB_CASE_TITLE, 'UTF-8'); // Primera letra de cada palabra en mayúscula
    return $nombre;
}

// Imprimir cada comunidad
foreach ($data as $comunidad => $datos) {
    if (isset($datos['electores']) && count($datos['electores']) > 0) {
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
                <th style="width: 10%">Teléfono</th>
                <th class="no-print" style="width: 10%">Mensaje</th>
            </tr>';





        $i = 1;
        foreach ($datos['electores'] as $elector) {

            $nombre_formateado = formatearNombre(strtoupper($elector['nombre']));



            if (strlen($elector['telefono']) > 5) {
                $mensaje = '<a href="https://wa.me/58' . $elector['telefono'] . '?text=Saludos ' . $nombre_formateado . ', *aun no has votado*,  FALTAN 30 MINUTOS para el cierre de la jornada. Tu participación es muy importante. Recuerda pasar por el punto del Gran Polo Patriótico y registrarte." target="_blank" class="btn-success">
                MENSAJE
                </a>';
            } else {
                $mensaje = '';
            }



            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . $elector['cedula'] . '</td>';
            $html .= '<td>' . strtoupper($elector['nombre']) . '</td>';
            $html .= '<td>' . (!empty($elector['telefono']) && $elector['telefono'] != '0' ? $elector['telefono'] : '') . '</td>';
            $html .= '<td class="no-print">' . $mensaje . '</td>';
            $html .= '</tr>';
        }
    }

    $html .= '</table>';
    $html .= '</div>';
}

echo json_encode(array(
    'status' => 'success',
    'html' => $html
));
