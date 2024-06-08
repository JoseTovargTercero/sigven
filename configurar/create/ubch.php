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
$correo = "";
$municipio = strip_tags( addslashes( $_POST['municipio_id'] ) );
$parroquia = strip_tags( addslashes( $_POST['continente_id'] ) );
$ubch = strip_tags( addslashes( $_POST['pais_id'] ) );

$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';

$_SESSION['municipio_ubch'] = $municipio;
$_SESSION['parroquia_ubch'] = $parroquia;
$_SESSION['ubch_ubch'] = $ubch;

$idResponabilidad = array( 
        "JEFE DE UBCH" => "1",
        "FORMACION IDEOLOGICA" => "2",
        "AGITACION PROPAGANDA Y COMUNICACION" => "3",
        "TECNICA ELECTORAL" => "4",
        "ECONOMIA PRODUCTIVA" => "5",
        "MUJERES" => "6",
        "JUVENTUD" => "7",
        "DEFENSA INTEGRAL" => "8",
        "COMUNAS Y MOVIMIENTOS SOCIALES" => "9",
        "CLASE OBRERA" => "10",
        "MISIONES Y GRANDES MISIONES" => "11",
        "ORGANIZACION Y MOVILIZACION" => "12"
);

$id_propio = $idResponabilidad["$cargo"];

$query="SELECT estructuraubch.cargo, pais.name1 FROM `estructuraubch` LEFT JOIN pais ON estructuraubch.ubch=pais.id WHERE estructuraubch.cedula='$ci'";  //CONSULTO SI LA CEDULA EXISTE
$buscarAlumnos = $conexion->query( $query );
if ( $buscarAlumnos->num_rows > 0 ) {
    while( $filaAlumnos = $buscarAlumnos->fetch_assoc() ) {
    
       $name = $filaAlumnos['name1'];
       $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuetra registrada en ".$name."/error/4000";
       define( 'PAGINA_RETORNO', '../../public/registro_Ubch.php' );
       header( 'Location: '.PAGINA_RETORNO );
    
       exit();

    }
}

$query2223="SELECT cargo FROM `estructuraubch` WHERE id_propio='$id_propio' AND ubch='$ubch'";  //CONSULTO SI LA CEDULA EXISTE
$buscarAlumnos2223 = $conexion->query( $query2223 );
if ( $buscarAlumnos2223->num_rows > 0 ) {
  
   $_SESSION['noticia'] = "¡No se completó!/La responsabilidad ya se encuentra ocupada/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Ubch.php' );
    header( 'Location: '.PAGINA_RETORNO );  
    exit();
}



$insertar = "INSERT INTO estructuraubch (id_propio, cedula, nombre, correo, telefono, cv, cargo,  municipio, parroquia, ubch, firma, registradopor, fecha_nac, sexo) VALUES ('$id_propio','$ci','$nombre','$correo','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$firmo','$ap','$nac','$sexo')";

$insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo','$nombre','$ci')";
$resultado = mysqli_query( $conexion, $insertar );

 
if ($resultado){
    $resultado2 = mysqli_query( $conexion, $insertaActividad );
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
    define( 'PAGINA_RETORNO', '../../public/registro_Ubch.php' );
    header( 'Location: '.PAGINA_RETORNO );
exit();
}



?>