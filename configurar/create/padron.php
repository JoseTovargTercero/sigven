<?php
include('../configuracion.php');

$ci = strip_tags( addslashes( $_POST['busqueda'] ) );
$nombre  = strip_tags( addslashes( $_POST['nombre'] ) );
$telefono = strip_tags( addslashes( $_POST['telefono'] ) );
$cv = strip_tags( addslashes( $_POST['cv'] ) );
$nac = strip_tags( addslashes( $_POST['nac'] ) );
$sexo = strip_tags( addslashes( $_POST['sexo'] ) );
$municipio = strip_tags( addslashes( $_POST['municipio_id'] ) );
$parroquia = strip_tags( addslashes( $_POST['continente_id'] ) );
$ubch = strip_tags( addslashes( $_POST['pais_id'] ) );
$comunidad = strip_tags( addslashes( $_POST['ciudad_id'] ) );
$calle = strip_tags( addslashes( $_POST['calle'] ) );
$voto = strip_tags( addslashes( $_POST['voto'] ) );



$ap = $_SESSION['id'];
$fecha = time();
$accion = 'Dato Agregado';
$tipo = "ELECTOR";

$_SESSION['municipio_padron'] = $municipio;
$_SESSION['parroquia_padron'] = $parroquia;
$_SESSION['ubch_padron'] = $ubch;
$_SESSION['comunidad_padron'] = $comunidad;
$_SESSION['calle_padron'] = $calle;

$origenDatos = 2;
$query="SELECT ciudad.name, padronelectoral.calle, padronelectoral.origenDatos FROM `padronelectoral` LEFT JOIN ciudad ON padronelectoral.comunidad=ciudad.id WHERE padronelectoral.cedula='$ci'"; 
$buscarCedula = $conexion->query( $query );
if ( $buscarCedula->num_rows > 0 ) {
    while( $filaEncontrada = $buscarCedula->fetch_assoc() ) {
       $name = $filaEncontrada['name']." - ".$filaEncontrada['calle'];
       $origenDatos = $filaEncontrada['origenDatos'];
    }
} ///////////////////////// en caso de que la persona ya exista dentro del padron y que fuese agregado desde el padron comunal



switch($origenDatos){

    case(0):

       $update = "UPDATE padronelectoral SET municipio='$municipio', parroquia='$parroquia', ubch='$ubch', comunidad='$comunidad', voto='$voto', calle='$calle', registradopor='$ap', telefono='$telefono', cv='$cv' WHERE cedula='$ci'";
       $result = mysqli_query( $conexion, $update );
       if ( $result ) {
               $_SESSION['noticia'] = "¡Actualizado!/Fue actualizado correctamente/success/3000";
               define( 'PAGINA_RETORNO', '../../public/registro_Padron.php' );
               header( 'Location: '.PAGINA_RETORNO );
               exit(); // Detener la ejecución del script después de redirigir
           
            } else {
            // Manejo de error si la consulta falla
            echo "Error al actualizar: " . mysqli_error($conexion);
            exit(); 
            
        }

    break;

    case(1):
    $update = "UPDATE padronelectoral SET municipio='$municipio', parroquia='$parroquia', ubch='$ubch', comunidad='$comunidad', voto='$voto', calle='$calle', registradopor='$ap', origenDatos='0' WHERE cedula='$ci'";
    $result = mysqli_query( $conexion, $update );
        if ( $result ) {
            $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
            define( 'PAGINA_RETORNO', '../../public/registro_Padron.php' );
            header( 'Location: '.PAGINA_RETORNO );
        } else {
            // Manejo de error si la consulta falla
            echo "Error al actualizar: " . mysqli_error($conexion);
            exit(); 
        }

    break;

    case(2):
        $insertar = "INSERT INTO padronelectoral (cedula, nombre, telefono, cv, voto, municipio, parroquia, ubch, comunidad, calle, registradopor, fecha_nac, sexo) 
        VALUES ('$ci','$nombre','$telefono','$cv','$voto','$municipio','$parroquia','$ubch','$comunidad','$calle','$ap','$nac','$sexo')";

        $result = mysqli_query( $conexion, $insertar );

        if ($result){
        $insertaActividad = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$tipo','$nombre','$ci')";
        $resultado = mysqli_query( $conexion, $insertaActividad );
        $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
        define( 'PAGINA_RETORNO', '../../public/registro_Padron.php' );
        header( 'Location: '.PAGINA_RETORNO );
        } else {
            // Manejo de error si la consulta falla
            echo "Error al actualizar: " . mysqli_error($conexion);
            exit(); 
        }
    break;
}

?>