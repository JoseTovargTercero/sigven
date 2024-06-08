<?php
include('../configuracion.php');

$ci = strip_tags( addslashes( $_POST['busqueda'] ) );
$nombre  = strip_tags( addslashes( $_POST['nombre'] ) );
$telefono = strip_tags( addslashes( $_POST['telefono'] ) );
$cv = strip_tags( addslashes( $_POST['cv'] ) );
$nac = strip_tags( addslashes( $_POST['nac'] ) );
$sexo = strip_tags( addslashes( $_POST['sexo'] ) );
$voto = strip_tags( addslashes( $_POST['voto'] ) );

$municipio = strip_tags( addslashes( $_POST['municipio_id'] ) );
$parroquia = strip_tags( addslashes( $_POST['continente_id'] ) );
$ubch = strip_tags( addslashes( $_POST['pais_id'] ) );
$comunidad = strip_tags( addslashes( $_POST['ciudad_id'] ) );

$responsabilidad = strip_tags( addslashes( $_POST['responsabilidad'] ) );
$tipoId = strip_tags( addslashes( $_POST['tipo'] ) );
$entidad = strip_tags( addslashes( $_POST['entidad'] ) );

$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';
$tipo = "ELECTOR";

$_SESSION['tipo_inst'] = $tipoId;
$_SESSION['entidad_inst'] = $entidad;


function nameInstitucion($id){
    global $conexion;
    $query = "SELECT nombre FROM `instituciones` WHERE id='$id'";
    $search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $name = $row['nombre'];
        }
    }
    return $name;
}
    
$institucion = nameInstitucion($entidad);



$origenDatos = 2;
$query="SELECT cedula FROM `padronelectoral` WHERE cedula='$ci'"; 
$buscarCedula = $conexion->query( $query );
if ( $buscarCedula->num_rows > 0 ) {
    $update = "UPDATE padronelectoral SET tipo_int='$tipoId', id_int='$entidad', nombre_int='$institucion', responsabilidad='$responsabilidad' WHERE cedula='$ci'";
    $result = mysqli_query( $conexion, $update );
        if ( $result ) {
            $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
            define( 'PAGINA_RETORNO', '../../public/registro_Padron_Institucional.php' );
            header( 'Location: '.PAGINA_RETORNO );
        }
}else{

    $insertar = "INSERT INTO padronelectoral (cedula, nombre, telefono, cv, voto, municipio, parroquia, ubch, comunidad, registradopor, fecha_nac, sexo, tipo_int, id_int, nombre_int, origenDatos, responsabilidad) 
    VALUES ('$ci','$nombre','$telefono','$cv','$voto','$municipio','$parroquia','$ubch','$comunidad','$ap','$nac','$sexo', '$tipoId', '$entidad', '$institucion', '1', '$responsabilidad')";

    $result = mysqli_query( $conexion, $insertar );

    if ($result){
    $insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipo','$nombre','$ci')";
    $resultado = mysqli_query( $conexion, $insertaActividad );
    $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
    define( 'PAGINA_RETORNO', '../../public/registro_Padron_Institucional.php' );
    header( 'Location: '.PAGINA_RETORNO );
    }
}



?>