<?php
include('../configuracion.php');

$id = $_POST['id'];
$nombre = $_POST['nombre'];

$ap = $_SESSION['id'];  
$fecha = time();
$accion = 'Dato Modificado';
$tipo = 'Nombre de UBCH';

$update = "UPDATE pais SET name1='$nombre' WHERE id='$id'";
$result = mysqli_query( $conexion, $update );

	if ($result ) {
		$insertar4 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipo','$nombre','')";
        $resultado3 = mysqli_query( $conexion, $insertar4 );
       if($resultado3){

		   $_SESSION['noticia'] = "¡Perfecto!/Fue editado correctamente/success/3000";
		   define( 'PAGINA_RETORNO', '../../public/registro_Entidades_UBCH.php' );
		   header( 'Location: '.PAGINA_RETORNO );

		}
	} 

?>