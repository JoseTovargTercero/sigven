<?php
include('../configuracion.php');

$continente_id  = strip_tags( addslashes( $_POST['continente_id'] ) );
$ubchName = strip_tags( addslashes( $_POST['ubchName'] ) );
$ubchName = strtoupper( $ubchName );
$codigo = strip_tags( addslashes( $_POST['codigo'] ) );


$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';


$query = "SELECT * FROM pais WHERE name1='$ubchName'";
$buscarCedula = $conexion->query( $query );
if ( $buscarCedula->num_rows > 0 ) {
    
    $_SESSION['noticia'] = "¡No se completó!/Ya existe otra UBCH con el mismo nombre/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Entidades_UBCH.php' );
    header( 'Location: '.PAGINA_RETORNO );
    

}else{

    $insertar = "INSERT INTO pais (name1, continente_id, codigo) VALUES ('$ubchName','$continente_id','$codigo')";
    
    $result = mysqli_query( $conexion, $insertar );
    
    if ($result){
    $insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipoInt','$nombre','')";
    $resultado = mysqli_query( $conexion, $insertaActividad );
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Entidades_UBCH.php' );
    header( 'Location: '.PAGINA_RETORNO );
    }
}