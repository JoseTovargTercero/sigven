<?php
include('../configuracion.php');

$cedula = strip_tags( addslashes( $_POST['cedula'] ) );
$nombre  = strip_tags( addslashes( $_POST['nombre'] ) );
$nivel = strip_tags( addslashes( $_POST['nivel'] ) );
$pass = strip_tags( addslashes( $_POST['pass'] ) );
$pass2 = strip_tags( addslashes( $_POST['pass2'] ) );
$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';
$tipo = "ELECTOR";

$query="SELECT * FROM `usuarios` WHERE cedula='$cedula'"; 
$buscarCedula = $conexion->query( $query );
if ( $buscarCedula->num_rows > 0 ) {
    $_SESSION['noticia'] = "¡No se completó!/Intente con un número de cédula diferente/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_usuarios.php' );
    header( 'Location: '.PAGINA_RETORNO );
    exit();
}

if($pass == $pass2){
  
	$contrasena = md5($_POST['pass']);

    $insertar = "INSERT INTO usuarios (nombre, cedula, contrasena, nivel) VALUES ('$nombre','$cedula','$contrasena','$nivel')";
    $result = mysqli_query( $conexion, $insertar );

    if ($result){
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
    define( 'PAGINA_RETORNO', '../../public/registro_usuarios.php' );
    header( 'Location: '.PAGINA_RETORNO );
    }
}else{
    $_SESSION['noticia'] = "¡No se completó!/Las contraseñas no coinciden/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_usuarios.php' );
    header( 'Location: '.PAGINA_RETORNO );
    exit();  
}
?>