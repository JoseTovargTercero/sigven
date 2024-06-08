<?php
include('../configuracion.php');

$id = $_POST['id'];
$telefono = $_POST['telefono'];
$cedula = $_POST['cedula'];
$nombre = $_POST['nombre'];
$tipo = "ELECTOR";

$voto = $_POST['voto'];
$calle = $_POST['nuevaCalle'];

$ap = $_SESSION['id'];  
$fecha = time();
$accion = 'Dato Modificado';

$update = "UPDATE padronelectoral SET telefono='$telefono', voto='$voto', calle='$calle' WHERE id='$id'";
$result = mysqli_query( $conexion, $update );

	if ($result ) {
		$insertar4 = "INSERT INTO `actividad_usuarios` 
		(id_usuario, fecha, accion, tipo, nombre, cedula) VALUES 
		('$ap','$fecha','$accion','$tipo','$nombre','$cedula')";
        $resultado3 = mysqli_query( $conexion, $insertar4 );
       if($resultado3){

		   $_SESSION['noticia'] = "¡Perfecto!/Fue editado correctamente/success/3000";
		   define( 'PAGINA_RETORNO', '../../public/registro_Padron.php' );
		   header( 'Location: '.PAGINA_RETORNO );

		}
	} 

?>