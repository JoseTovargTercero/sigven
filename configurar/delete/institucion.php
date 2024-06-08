<?php
date_default_timezone_set( 'America/Manaus' );
include( '../configuracion.php' );

$ap = $_SESSION['id'];

$id = $_GET['id'];
$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Eliminado';
$tipo = $_GET['tipo'];
$nombre = $_GET['nombre'];
$ci = "";

switch($tipo){
	case(1):
	$tipoInt = "INSTITUCION";
	  break;
	case(2):
	$tipoInt = "PARTIDO POLITICO";
	  break;
	case(3):
	$tipoInt = "MOVIMIENTO SOCIAL";
	  break;
  }

      
$delete = $conexion->query("DELETE FROM instituciones WHERE id='$id'"); 


$insertar1 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipoInt ','$nombre','$ci')";
$resultado3 = mysqli_query( $conexion, $insertar1 );


if ($resultado3){
    $_SESSION['noticia'] = "¡Perfecto!/Fue eliminado correctamente/success/3000";
    define( 'PAGINA_RETORNO', '../../public/registro_Institucion.php' );
    header( 'Location: '.PAGINA_RETORNO );
}


?>