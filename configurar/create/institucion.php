<?php
include('../configuracion.php');


$tipo  = strip_tags( addslashes( $_POST['tipo'] ) );
$nombre  = strip_tags( addslashes( $_POST['nombreInsti'] ) );
$nombre = strtoupper( $nombre );
$direccion = strip_tags( addslashes( $_POST['direccionReferencia'] ) );

$CedulaDirector = strip_tags( addslashes( $_POST['busqueda'] ) );
$nombreDirector = strip_tags( addslashes( $_POST['nombre'] ) );

$telefono  = strip_tags( addslashes( $_POST['telefono'] ) );
$correo = strip_tags( addslashes( $_POST['correo'] ) );

$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';


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


$query = "SELECT * FROM instituciones WHERE nombre='$nombre'";
$buscarCedula = $conexion->query( $query );
if ( $buscarCedula->num_rows > 0 ) {
    
    $_SESSION['noticia'] = "¡No se completó!/Ya existe otra institución con el mismo nombre/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Institucion.php' );
    header( 'Location: '.PAGINA_RETORNO );
    

}else{

    $insertar = "INSERT INTO instituciones (tipo, nombre, telefono, correo, direccion, CedulaDirector, nombreDirector) 
                                    VALUES ('$tipo','$nombre','$telefono','$correo','$direccion','$CedulaDirector','$nombreDirector')";
    
    $result = mysqli_query( $conexion, $insertar );
    
    if ($result){
    $insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipoInt','$nombre','')";
    $resultado = mysqli_query( $conexion, $insertaActividad );
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Institucion.php' );
    header( 'Location: '.PAGINA_RETORNO );
    }
}