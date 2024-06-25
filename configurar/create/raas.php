<?php
include('../configuracion.php');

// Sanitize input
function sanitize_input($data) {
    return strip_tags(addslashes($data));
}

$ci = sanitize_input($_POST['busqueda']);
$nombre = sanitize_input($_POST['nombre']);
$telefono = sanitize_input($_POST['telefono']);
$cv = sanitize_input($_POST['cv']);
$firmo = sanitize_input($_POST['firma']);
$nac = sanitize_input($_POST['nac']);
$sexo = sanitize_input($_POST['sexo']);
$cargo = sanitize_input($_POST['cargo']);
$municipio = sanitize_input($_POST['municipio_id']);
$parroquia = sanitize_input($_POST['continente_id']);
$ubch = sanitize_input($_POST['pais_id']);
$comunidad = sanitize_input($_POST['ciudad_id']);

$ap = $_SESSION['id'];
date_default_timezone_set('America/Manaus');
$fecha = time();
$accion = 'Dato Agregado';

$_SESSION['municipio'] = $municipio;
$_SESSION['parroquia'] = $parroquia;
$_SESSION['ubch'] = $ubch;
$_SESSION['comunidad'] = $comunidad;

$idResponsabilidad = [
    'JEFE DE COMUNIDAD' => '1',
    'RESP. PRINCIPAL DEL CONSEJO COMUNAL' => '2',
    'RESP.  DE LA COMISION DE MISIONES Y GRANDES MISIONES SOCIALISTAS' => '3',
    'RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION' => '4',
    'RESP. DE LA COMISION DE LA JUVENTUD' => '5',
    'RESP. DE LA COMISION DE MUJERES' => '6',
    'RESP. ECONOMIA PRODUCTIVA' => '7',
    'DEFENSA INTEGRAL' => '8',
    'VOCERO DEL CONSEJO COMUNAL' => '9',
    'VOCERO DE UNAMUJER' => '10',
    'VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD' => '11',
    'VOCERO DE UBCH' => '12',
    'COMANDANTE DE LA UPDI' => '13',
    'CALLE 1' => '14',
    'CALLE 2' => '15',
    'CALLE 3' => '16',
    'CALLE 4' => '17',
    'CALLE 5' => '18',
    'CALLE 6' => '19',
    'CALLE 7' => '20',
    'CALLE 8' => '21',
    'CALLE 9' => '22',
    'CALLE 10' => '23',
    'CALLE 11' => '24',
    'CALLE 12' => '25',
    'CALLE 13' => '26',
    'CALLE 14' => '27',
    'CALLE 15' => '28',
    'CALLE 16' => '29',
    'CALLE 17' => '30',
    'CALLE 18' => '31',
    'CALLE 19' => '32',
    'CALLE 20' => '33',
    'CALLE 21' => '34',
    'CALLE 22' => '35',
    'CALLE 23' => '36',
    'CALLE 24' => '37',
    'CALLE 25' => '38',
    'CALLE 26' => '39',
    'CALLE 27' => '40',
    'CALLE 28' => '41',
    'CALLE 29' => '42',
    'CALLE 30' => '43',
    'CALLE 31' => '44',
    'CALLE 32' => '45',
    'CALLE 33' => '46',
    'CALLE 34' => '47',
    'CALLE 35' => '48'
];

$id_propio = $idResponsabilidad[$cargo];
$tipocargo = ($id_propio >= 14) ? 1 : 0;

$query = "SELECT cargo FROM estructuraraas WHERE cargo='$cargo' AND comunidad='$comunidad'";
$buscarAlumnos = $conexion->query($query);

if ($buscarAlumnos->num_rows > 0) {
    $_SESSION['noticia'] = "¡No se completó!/La responsabilidad ya se encuentra ocupada/error/4000";
    header('Location: ../../public/registro_Raas.php');
    exit();
}

$query = "SELECT estructuraraas.cargo, ciudad.name FROM estructuraraas LEFT JOIN ciudad ON estructuraraas.comunidad=ciudad.id WHERE estructuraraas.cedula='$ci'";
$buscarAlumnos = $conexion->query($query);

if ($buscarAlumnos->num_rows > 0) {
    $filaAlumnos = $buscarAlumnos->fetch_assoc();
    $cargoRegistrado = $filaAlumnos['cargo'];
    $name = $filaAlumnos['name'];
    goto precargados;
}

$insertar = "INSERT INTO estructuraraas (cedula, nombre, correo, telefono, cv, cargo, municipio, parroquia, ubch, comunidad, firma, registradopor, tipocargo, fecha_nac, sexo, id_propio) VALUES ('$ci','$nombre','','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$comunidad','$firmo','$ap','$tipocargo','$nac','$sexo','$id_propio')";
$insertar1 = "INSERT INTO actividad_usuarios (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo','$nombre','$ci')";

$query = "SELECT * FROM padronelectoral WHERE cedula='$ci'";
$buscarAlumnos = $conexion->query($query);

if (!$buscarAlumnos->num_rows && $tipocargo == 1) {
    $voto = sanitize_input($_POST['voto']);
    $insertar2 = "INSERT INTO padronelectoral (cedula, nombre, telefono, cv, voto, municipio, parroquia, ubch, comunidad, calle, registradopor, fecha_nac, sexo) VALUES ('$ci','$nombre','$telefono','$cv','$voto','$municipio','$parroquia','$ubch','$comunidad','$cargo','$ap','$nac','$sexo')";
    $insertar3 = "INSERT INTO actividad_usuarios (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','Dato Agregado','ELECTOR','$nombre','$ci')";
    $conexion->query($insertar3);
    $conexion->query($insertar2);
}

if ($conexion->query($insertar1)) {
    if ($conexion->query($insertar)) {
        $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
        header('Location: ../../public/registro_Raas.php');
        exit();
    }
}

precargados:

$cargosRepetir = [
    'DEFENSA INTEGRAL',
    'COMANDANTE DE LA UPDI',
    'RESP. PRINCIPAL DEL CONSEJO COMUNAL',
    'VOCERO DEL CONSEJO COMUNAL',
    'VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD',
    'RESP. DE LA COMISION DE LA JUVENTUD',
    'RESP. DE LA COMISION DE MUJERES',
    'VOCERO DE UNAMUJER'
];

$cargoComprobar = 0;
$cargoComprobar2 = 0;

foreach ($cargosRepetir as $value) {
    if ($value == $cargo) {
        $cargoComprobar++;
    }
    if ($value == $cargoRegistrado) {
        $cargoComprobar2++;
    }
}

function coincidir($var) {
    $map = [
        'DEFENSA INTEGRAL' => 1,
        'COMANDANTE DE LA UPDI' => 1,
        'RESP. PRINCIPAL DEL CONSEJO COMUNAL' => 2,
        'VOCERO DEL CONSEJO COMUNAL' => 2,
        'VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD' => 3,
        'RESP. DE LA COMISION DE LA JUVENTUD' => 3,
        'RESP. DE LA COMISION DE MUJERES' => 4,
        'VOCERO DE UNAMUJER' => 4
    ];
    return $map[$var] ? $map[$var] : 0;
}

if ($cargoComprobar == 0 || $cargoComprobar2 == 0 || coincidir($cargo) != coincidir($cargoRegistrado)) {
    $_SESSION['comunidadPertenece'] = $name ?: "COMUNIDAD ELIMINADA";
    $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuentra registrada en $name/error/4000";
    header('Location: ../../public/registro_Raas.php');
    exit();
}

if ($cargo != $cargoRegistrado) {
    $insertar = "INSERT INTO estructuraraas (cedula, nombre, correo, telefono, cv, cargo, municipio, parroquia, ubch, comunidad, firma, registradopor, tipocargo, fecha_nac, sexo, id_propio) VALUES ('$ci','$nombre','','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$comunidad','$firmo','$ap','$tipocargo','$nac','$sexo','$id_propio')";
    $insertar1 = "INSERT INTO actividad_usuarios (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo','$nombre','$ci')";
    
    if ($conexion->query($insertar)) {
        $conexion->query($insertar1);
        $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
        header('Location: ../../public/registro_Raas.php');
        exit();
    }
} else {
    $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuentra registrada en $name/error/4000";
    header('Location: ../../public/registro_Raas.php');
}
?>
