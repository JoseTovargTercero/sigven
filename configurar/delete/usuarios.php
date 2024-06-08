<?php
date_default_timezone_set( 'America/Manaus' );
include( '../configuracion.php' );

$ap = $_SESSION['id'];

$id = $_GET['id'];
$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Eliminado';
$tipo = "USUARIO";
$nombre = $_GET['nombre'];
$ci = $_GET['cedula'];

  $update = "UPDATE usuarios SET activado='1' WHERE id='$id'";
  $result = mysqli_query( $conexion, $update );
  
  
	  if ( $result ) {
		
		$insertar1 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipo ','$nombre','$ci')";
		$resultado3 = mysqli_query( $conexion, $insertar1 );
		
		$_SESSION['noticia'] = "¡Perfecto!/Fue eliminado correctamente/success/3000";
		define( 'PAGINA_RETORNO', '../../public/registro_usuarios.php' );
		header( 'Location: '.PAGINA_RETORNO );

	  } 


?>