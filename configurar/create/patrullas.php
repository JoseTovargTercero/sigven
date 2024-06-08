<?php
include('../configuracion.php');

$ci = strip_tags( addslashes( $_POST['busqueda'] ) );
$nombre  = strip_tags( addslashes( $_POST['nombre'] ) );
$telefono = strip_tags( addslashes( $_POST['telefono'] ) );
$cv = strip_tags( addslashes( $_POST['cv'] ) );
$firmo = strip_tags( addslashes( $_POST['firma'] ) );
$nac = strip_tags( addslashes( $_POST['nac'] ) );
$sexo = strip_tags( addslashes( $_POST['sexo'] ) );
$cargo = strip_tags( addslashes( $_POST['cargo'] ) );
$cargo2 = strip_tags( addslashes( $_POST['cargo'] ) );
$cargo2 = "(P) ".$cargo2;


$municipio = strip_tags( addslashes( $_POST['municipio_id'] ) );
$parroquia = strip_tags( addslashes( $_POST['continente_id'] ) );
$ubch = strip_tags( addslashes( $_POST['pais_id'] ) );
$comunidad = strip_tags( addslashes( $_POST['ciudad_id'] ) );
$calle = strip_tags( addslashes( $_POST['calle'] ) );

$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';

$_SESSION['municipio_patr'] = $municipio;
$_SESSION['parroquia_patr'] = $parroquia;
$_SESSION['ubch_patr'] = $ubch;
$_SESSION['comunidad_patr'] = $comunidad;
$_SESSION['calle_patr'] = $calle;


$query="SELECT estructurapatrulla.cargo, ciudad.name FROM `estructurapatrulla` LEFT JOIN ciudad ON estructurapatrulla.comunidad=ciudad.id WHERE estructurapatrulla.cedula='$ci'"; 
$buscarAlumnos = $conexion->query( $query );
if ( $buscarAlumnos->num_rows > 0 ) {
    while( $filaAlumnos = $buscarAlumnos->fetch_assoc() ) {
    
       $name = $filaAlumnos['name'];
       $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuetra registrada en ".$name."/error/4000";
       define( 'PAGINA_RETORNO', '../../public/registro_Patrullas.php' );
       header( 'Location: '.PAGINA_RETORNO );
    
       exit();

    }
}

$query2223="SELECT cargo FROM `estructurapatrulla` WHERE cargo='$cargo' AND comunidad='$comunidad' AND calle='$calle'"; 
$buscarAlumnos2223 = $conexion->query( $query2223 );
if ( $buscarAlumnos2223->num_rows > 0 ) {
  
   $_SESSION['noticia'] = "¡No se completó!/La responsabilidad ya se encuentra ocupada/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Patrullas.php' );
    header( 'Location: '.PAGINA_RETORNO );  
    exit();
}



$insertar = "INSERT INTO estructurapatrulla (cedula, nombre, telefono, cv, cargo,  municipio, parroquia, ubch, comunidad, calle, firma, registradopor, fecha_nac, sexo) 
                                     VALUES ('$ci','$nombre','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$comunidad','$calle','$firmo','$ap','$nac','$sexo')";

$insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo2','$nombre','$ci')";
$resultado = mysqli_query( $conexion, $insertar );


$query69 = "SELECT * FROM padronelectoral WHERE cedula=$ci";
$buscarAlumnos69 = $conexion->query( $query69 );
if (!$buscarAlumnos69->num_rows > 0) {

    $voto = $_POST['voto'];

    $insetar3 = "INSERT INTO padronelectoral (cedula, nombre, telefono, cv, voto, municipio, parroquia, ubch, comunidad, calle, registradopor, fecha_nac, sexo) VALUES ('$ci','$nombre','$telefono','$cv','$voto','$municipio','$parroquia','$ubch','$comunidad','$calle','$ap','$nac','$sexo')";

    $insertar4 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','Dato Agregado','ELECTOR','$nombre','$ci')";

    $resultado3 = mysqli_query( $conexion, $insertar4 );
    $resultado4 = mysqli_query( $conexion, $insetar3 );
   
}

 
if ($resultado){
    $resultado2 = mysqli_query( $conexion, $insertaActividad );
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
    define( 'PAGINA_RETORNO', '../../public/registro_Patrullas.php' );
    header( 'Location: '.PAGINA_RETORNO );
exit();
}



?>