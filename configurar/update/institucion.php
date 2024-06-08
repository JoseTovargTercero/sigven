<?php
include('../configuracion.php');

$id = $_POST['id'];

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$cedula = $_POST['cedula'];
$tipo = $_POST['tipo'];
$correo = $_POST['correo'];
$direccion = $_POST['direccion'];
$nombreDirector = $_POST['nombreDirector'];

$ap = $_SESSION['id'];  

$fecha = time();
$accion = 'Dato Modificado';

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
  
$update = "UPDATE instituciones SET nombre='$nombre', telefono='$telefono', correo='$correo', direccion='$direccion', CedulaDirector='$cedula', nombreDirector='$nombreDirector' WHERE id='$id'";


$result = mysqli_query( $conexion, $update );

	if ($result ) {
		$insertar4 = "INSERT INTO `actividad_usuarios` 
		(id_usuario, fecha, accion, tipo, nombre, cedula) VALUES 
		('$ap','$fecha','$accion','$tipoInt','$nombre','')";
        $resultado3 = mysqli_query( $conexion, $insertar4 );
       if($resultado3){

		   $_SESSION['noticia'] = "¡Perfecto!/Fue editado correctamente/success/3000";
		   define( 'PAGINA_RETORNO', '../../public/registro_Institucion.php' );
		   header( 'Location: '.PAGINA_RETORNO );

		}
	} 

?>