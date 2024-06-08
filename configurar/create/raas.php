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
$comunidad = strip_tags( addslashes( $_POST['ciudad_id'] ) );

$ap = $_SESSION['id'];
date_default_timezone_set( 'America/Manaus' );
$fecha = time();
$accion = 'Dato Agregado';

$_SESSION['municipio'] = $municipio;
$_SESSION['parroquia'] = $parroquia;
$_SESSION['ubch'] = $ubch;
$_SESSION['comunidad'] = $comunidad;

$idResponabilidad = array( 'JEFE DE COMUNIDAD' => '1', 'RESP. PRINCIPAL DEL CONSEJO COMUNAL' => '2', 'RESP.  DE LA COMISION DE MISIONES Y GRANDES MISIONES SOCIALISTAS' => '3', 'RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION' => '4', 'RESP. DE LA COMISION DE LA JUVENTUD' => '5', 'RESP. DE LA COMISION DE MUJERES' => '6', 'RESP. ECONOMIA PRODUCTIVA' => '7', 'DEFENSA INTEGRAL' => '8', 'VOCERO DEL CONSEJO COMUNAL' => '9', 'VOCERO DE UNAMUJER' => '10', 'VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD' => '11', 'VOCERO DE UBCH' => '12', 'COMANDANTE DE LA UPDI' => '13', 'CALLE 1' => '14', 'CALLE 2' => '15', 'CALLE 3' => '16', 'CALLE 4' => '17', 'CALLE 5' => '18', 'CALLE 6' => '19', 'CALLE 7' => '20', 'CALLE 8' => '21', 'CALLE 9' => '22', 'CALLE 10' => '23', 'CALLE 11' => '24', 'CALLE 12' => '25', 'CALLE 13' => '26', 'CALLE 14' => '27', 'CALLE 15' => '28', 'CALLE 16' => '29', 'CALLE 17' => '30', 'CALLE 18' => '31', 'CALLE 19' => '32', 'CALLE 20' => '33', 'CALLE 21' => '34', 'CALLE 22' => '35', 'CALLE 23' => '36', 'CALLE 24' => '37', 'CALLE 25' => '38', 'CALLE 26' => '39', 'CALLE 27' => '40', 'CALLE 28' => '41', 'CALLE 29' => '42', 'CALLE 30' => '43', 'CALLE 31' => '44', 'CALLE 32' => '45', 'CALLE 33' => '46', 'CALLE 34' => '47', 'CALLE 35' => '48');

$id_propio = $idResponabilidad["$cargo"];
if($id_propio >= 14){
    $tipocargo = 1;
}

$query2223="SELECT cargo FROM `estructuraraas` WHERE cargo='$cargo' AND comunidad='$comunidad'";  //CONSULTO SI LA CEDULA EXISTE
$buscarAlumnos2223 = $conexion->query( $query2223 );
if ( $buscarAlumnos2223->num_rows > 0 ) {
  
    $_SESSION['noticia'] = "¡No se completó!/La responsabilidad ya se encuentra ocupada/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Raas.php' );
    header( 'Location: '.PAGINA_RETORNO );  
exit();
}



/* verifico si la persona se encuentra en la bd*/
$query="SELECT estructuraraas.cargo, ciudad.name FROM `estructuraraas` LEFT JOIN ciudad ON estructuraraas.comunidad=ciudad.id WHERE estructuraraas.cedula='$ci'";  //CONSULTO SI LA CEDULA EXISTE
$buscarAlumnos = $conexion->query( $query );
if ( $buscarAlumnos->num_rows > 0 ) {
    while( $filaAlumnos = $buscarAlumnos->fetch_assoc() ) {
    $cargoRegistrado = $filaAlumnos['cargo'];
    $name = $filaAlumnos['name'];
    }
  goto precargados;
}

/* Regisro normal cuando la persona no se encuentra en la bd*/

    $insertar = "INSERT INTO estructuraraas (cedula, nombre, correo, telefono, cv, cargo,  municipio, parroquia, ubch, comunidad, firma, registradopor, tipocargo, fecha_nac, sexo, id_propio) VALUES ('$ci','$nombre','$correo','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$comunidad','$firmo','$ap','$tipocargo','$nac','$sexo','$id_propio')";

    $insertar1 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo','$nombre','$ci')";

    $query69 = "SELECT * FROM padronelectoral WHERE cedula=$ci";
    $buscarAlumnos69 = $conexion->query( $query69 );
    if (!$buscarAlumnos69->num_rows > 0 && $tipocargo == '1') {

        $voto = $_POST['voto'];

        $insetar3 = "INSERT INTO padronelectoral (cedula, nombre, telefono, cv, voto, municipio, parroquia, ubch, comunidad, calle, registradopor, fecha_nac, sexo) VALUES ('$ci','$nombre','$telefono','$cv','$voto','$municipio','$parroquia','$ubch','$comunidad','$cargo','$ap','$nac','$sexo')";

        $insertar4 = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','Dato Agregado','ELECTOR','$nombre','$ci')";

        $resultado3 = mysqli_query( $conexion, $insertar4 );
        $resultado4 = mysqli_query( $conexion, $insetar3 );
       
    }
    
    $resultado = mysqli_query( $conexion, $insertar1 );
    
    if ($resultado){
        
        $resultado2 = mysqli_query( $conexion, $insertar );
        $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
        define( 'PAGINA_RETORNO', '../../public/registro_Raas.php' );
        header( 'Location: '.PAGINA_RETORNO );
    exit();
    }


precargados:

$cargosRepetir = array('DEFENSA INTEGRAL', 'COMANDANTE DE LA UPDI', 'RESP. PRINCIPAL DEL CONSEJO COMUNAL', 'VOCERO DEL CONSEJO COMUNAL', 'VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD', 'RESP. DE LA COMISION DE LA JUVENTUD', 'RESP. DE LA COMISION DE MUJERES', 'VOCERO DE UNAMUJER');

$cargoComprbar = 0;

foreach ($cargosRepetir as $value) {
  if($value == $cargo){
    $cargoComprobar += 1;
  }
}

foreach ($cargosRepetir as $value) {
  if($value == $cargoRegistrado){
    $cargoComprobar2 += 1;
  }
}

function coincidir($var){
    switch($var){
        case('DEFENSA INTEGRAL'):
            $valor = 1;
            break;
        case('COMANDANTE DE LA UPDI'):
            $valor = 1;
            break;
        case('RESP. PRINCIPAL DEL CONSEJO COMUNAL'):
            $valor = 2;
            break;
        case('VOCERO DEL CONSEJO COMUNAL'):
            $valor = 2;
            break;
        case('VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD'):
            $valor = 3;
            break;
        case('RESP. DE LA COMISION DE LA JUVENTUD'):
            $valor = 3;
            break;
        case('RESP. DE LA COMISION DE MUJERES' ):
            $valor = 4;
            break;
        case('VOCERO DE UNAMUJER'):
            $valor = 4;
            break;
    }
    return $valor;
}

/* verificar si el contador de cargos con regla de permitido (para cargos dobles) sumo valor, de lo contrario no se permite el registro*/
if($cargoComprobar == 0 || $cargoComprobar2 == 0 || coincidir($cargo) != coincidir($cargoRegistrado)){

    if ($name != "" ) {
        $_SESSION['comunidadPertenece'] = $name;
    }else{
        $_SESSION['comunidadPertenece'] = "COMUNIDAD ELIMINADA";
    }
    
    $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuetra registrada en ".$name."/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Raas.php' );
    header( 'Location: '.PAGINA_RETORNO );
    exit();
}



/* En caso de que el cargo registrado no coincida con el cargo a registrar y este cumpla con la regla de cargos repetidos.De lo contrario esta registrando dos veces en el mismo cargo*/
if($cargo != $cargoRegistrado){
    $insertar = "INSERT INTO estructuraraas (cedula, nombre, correo, telefono, cv, cargo,  municipio, parroquia, ubch, comunidad, firma, registradopor, tipocargo, fecha_nac, sexo, id_propio) VALUES ('$ci','$nombre','$correo','$telefono','$cv','$cargo','$municipio','$parroquia','$ubch','$comunidad','$firmo','$ap','$tipocargo','$nac','$sexo','$id_propio')";
    
    $insertaronada = "INSERT INTO `actividad_usuarios` (id_usuario, fecha, accion, tipo, nombre, cedula) VALUES ('$ap','$fecha','$accion','$cargo','$nombre','$ci')";   
   
    $resultado2 = mysqli_query($conexion , $insertar );         //INSERTO PERSONA
    
  if ($resultado2){
      

        $resultado = mysqli_query( $conexion, $insertaronada );     //INSERTO ACTIVIDAD
        $_SESSION['noticia'] = "¡Perfecto!/Fue agregado correctamente/success/3000";
        define( 'PAGINA_RETORNO', '../../public/registro_Raas.php' );
        header( 'Location: '.PAGINA_RETORNO );
    }

}else{
    if ($name == "" ) {
       $name = "COMUNIDAD ELIMINADA";
    }

    $_SESSION['noticia'] = "¡No se completó!/La persona ya se encuetra registrada en ".$name."/error/4000";
    define( 'PAGINA_RETORNO', '../../public/registro_Raas.php' );
    header( 'Location: '.PAGINA_RETORNO );  
}
