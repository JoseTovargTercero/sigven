<?php


require_once 'php-jwt-main/src/JWT.php';
require_once '../configuracion.php';
require_once '../../elecciones/configuracion/conexion.php';

$notifications = [];

/* Selección de tokens */
$tokens = [];
$stmt = mysqli_prepare($conexion_app, "SELECT * FROM `tokens_fcm` WHERE user!='27640176' AND user!='27365357'");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tokens[$row['user']] = [$row['nombre'], $row['token']]; // JEFES DE COMUNIDAD

        // mensaje pre elecciones
        $notificacion = [
            'token' => $row['token'],
            'title' => 'ATENCIÓN - ' . $row['nombre'],
            'body' => "Si realizaste un reporte antes del inicio del evento, debes eliminarlo. Para hacerlo, ingresa al chat dentro de la app y toca el ícono de la papelera ubicado en la parte superior izquierda."
        ];
        array_push($notifications, $notificacion);
        // mensaje pre elecciones
    }
}
$stmt->close();
/* Selección de tokens */



/* Selección de jefes de comunidad */
$jefes_comunidad = [];
$stmt = mysqli_prepare($conexion, "SELECT cedula, jefe FROM `jefes_comunidad`");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jefes_comunidad[$row['cedula']] = $row['jefe']; // JEFES DE COMUNIDAD
    }
}
$stmt->close();
/* Selección de jefes de comunidad */



/* Meta de Electores por centro */
$meta_centro = [];
$stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(centro), COUNT(*) AS total FROM `electores_regionales` GROUP by centro;");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $meta_centro[$row['centro']] = $row['total']; // JEFES DE COMUNIDAD
    }
}
$stmt->close();
/* Meta de Electores por centro */



/* reportes realizados por centro */
$reportes_centro = [];
$stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(centro), COUNT(*) AS total FROM `flujo_electoral` GROUP by centro;");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reportes_centro[$row['centro']] = $row['total']; // JEFES DE COMUNIDAD
    }
}
$stmt->close();
/* reportes realizados por centro */
/*

// Lista de notificaciones personalizadas
$notifications = [
    ['token' => 'token1... (prueba de sintaxis)', 'title' => 'Saludo 1', 'body' => 'Mensaje para token 1'],
    ['token' => 'token2... (prueba de sintaxis)', 'title' => 'Alerta 2', 'body' => 'Mensaje para token 2'],
    ['token' => 'token3... (prueba de sintaxis)', 'title' => 'Notificación 3', 'body' => 'Mensaje para token 3'],
];*/


function abrv($centro)
{
    $centro_ab = str_replace('CENTRO DE EDUCACION INCIAL', 'CEI', $centro);
    $centro_ab = str_replace('CENTRO DE EDUCACION INICIAL', 'CEI', $centro_ab);
    $centro_ab = str_replace('CENTRO EDUCATIVO INTEGRAL BOLIVARIANO', 'CEIB', $centro_ab);
    $centro_ab = str_replace('UNIVERSIDAD PEDAGOGICA EXPERIMENTAL LIBERTADOR', 'UPEL', $centro_ab);
    $centro_ab = str_replace('UNIVERSIDAD PEDAGOGICA EXPERIMENTAL LIBERTADOR', 'UPEL', $centro_ab);
    $centro_ab = str_replace('ESCUELA BASICA', 'EB', $centro_ab);
    $centro_ab = str_replace('CENTRO MOVIL', 'CM', $centro_ab);
    $centro_ab = str_replace('UNIDAD EDUCATIVA BOLIVARIANA', 'UEB', $centro_ab);
    $centro_ab = str_replace('UNIDAD EDUCATIVA', 'UE', $centro_ab);
    $centro_ab = str_replace('CENTRO EDUCATIVO INTEGRAL BOLIVARIANO', 'UE', $centro_ab);

    return $centro_ab;
}


/* selección del flujo electoral */
$operadores = [];
$stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.responsable, flujo_electoral.centro, tablamesa.centro AS name_centro, COUNT(*) AS cantidad FROM flujo_electoral LEFT JOIN tablamesa ON tablamesa.CODIGO = flujo_electoral.centro GROUP BY responsable, centro HAVING (responsable, cantidad) IN ( SELECT responsable, MAX(cantidad) FROM ( SELECT responsable, centro, COUNT(*) AS cantidad FROM flujo_electoral GROUP BY responsable, centro ) AS sub GROUP BY responsable);");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $data_user = $tokens[$row['responsable']] ?? false;
        if ($data_user) {
            $token = $data_user[1];
            $user = $data_user[0];
            $total_reportes_centro = $reportes_centro[$row['centro']] ?? '0';
            $meta_electoral = $meta_centro[$row['centro']] ?? '100';
            $name_centro = abrv($row['name_centro']) ?? 'CENTRO DE VOTACIÓN';

            /*
            $notificacion = [
                'token' => $token,
                'title' => $name_centro,
                'body' => "Tienes $total_reportes_centro de $meta_electoral reportes en tu centro, ingresa a la app y verifica los avances."
            ];
*/
            //  array_push($notifications, $notificacion);

            /*  $operadores[$row['responsable']] = [
                'centro' => $row['centro'],
                'Reportes_del_usuario' => $row['cantidad'],
                'token' => $token,
                'user' => $user,
            ];*/
        }
    }
}
$stmt->close();
/* selección del flujo electoral */


use \Firebase\JWT\JWT;

// Leer credenciales del servicio
$serviceAccountPath = '../../../service-account.json';
$serviceAccount = json_decode(file_get_contents($serviceAccountPath), true);

// Crear JWT
$now = time();
$payload = [
    'iss' => $serviceAccount['client_email'],
    'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
    'aud' => 'https://oauth2.googleapis.com/token',
    'iat' => $now,
    'exp' => $now + 3600
];
$jwt = JWT::encode($payload, $serviceAccount['private_key'], 'RS256');

// Obtener token de acceso
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
    'assertion' => $jwt,
]));
$response = curl_exec($ch);
$data = json_decode($response, true);
curl_close($ch);


if (!isset($data['access_token'])) {
    die("Error al obtener token de acceso: " . json_encode($data));
}


$accessToken = $data['access_token'];
$projectId = 'participa-v';
$url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";


// Inicializar multi-cURL
$multiCurl = curl_multi_init();
$curlHandles = [];

foreach ($notifications as $index => $notif) {
    $body = [
        'message' => [
            'token' => $notif['token'],
            'notification' => [
                'title' => $notif['title'],
                'body' => $notif['body']
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $accessToken",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

    curl_multi_add_handle($multiCurl, $ch);
    $curlHandles[$index] = $ch;
}

// Ejecutar las peticiones en paralelo
$running = null;
do {
    curl_multi_exec($multiCurl, $running);
    curl_multi_select($multiCurl);
} while ($running > 0);

// Recoger respuestas y cerrar recursos
foreach ($curlHandles as $index => $ch) {
    $response = curl_multi_getcontent($ch);
    echo "Respuesta {$index}: $response\n";
    curl_multi_remove_handle($multiCurl, $ch);
    curl_close($ch);
}
curl_multi_close($multiCurl);
