<?php
include('../configuracion.php');

$id = $_GET['id'];
$tipo = $_GET['tipo'];
$value = $_GET['value'];
$tipo2 = "PERMISOS";
$ap = $_SESSION['id'];  
$fecha = time();
$accion = 'Dato Modificado';

$update = "UPDATE usuarios SET $tipo='$value' WHERE id='$id'";

$result = mysqli_query( $conexion, $update );

if ($result ) {
		$insertar4 = "INSERT INTO `actividad_usuarios` 
		(id_usuario, fecha, accion, tipo, nombre, cedula) VALUES 
		('$ap','$fecha','$accion','$tipo','$nombre','$cedula')";
        $resultado3 = mysqli_query( $conexion, $insertar4 );
       if($resultado3){
		   $_SESSION['noticia'] = "¡Perfecto!/Los permisos fueron cambiados/success/3000";
		   define( 'PAGINA_RETORNO', '../../public/permisos.php' );
		   header( 'Location: '.PAGINA_RETORNO );
		}
	} 

?>