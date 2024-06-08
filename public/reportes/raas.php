<?php 
error_reporting(0);
include('../../configurar/configuracion.php');
$municipio_id = $_POST['municipio_id'];
$continente_id = $_POST['continente_id'];
$pais_id = $_POST['pais_id'];
$ciudad_id = $_POST['ciudad_id'];


if($pais_id == '' || $continente_id == '' || $municipio_id == ''  || $ciudad_id == '' ) {
    define('PAGINA_INICIO', '../reportes.php');
    header('Location: ' . PAGINA_INICIO);
}



$query = "SELECT pais.name1, continente.name2, ciudad.name FROM ciudad

LEFT JOIN pais ON pais.id=ciudad.pais_id
LEFT JOIN continente ON pais.continente_id=continente.id


 WHERE ciudad.id='$ciudad_id'";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $nombreComunidad = $row['name'];
            $nombreUbch = $row['name1'];
            $nombrePq = $row['name2'];
        }
}


header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename=RAAS - '.$nombreComunidad.' - SIGVEN - '.date('Y_m_d h-i a').' .xls' );
 
?>
<head>
   <meta charset="utf-8">
</head>

<style>
    .titulo {
        font-size: 18px;
        text-align: center;
    }

    .header {
        font-size: 15px;
        width: 100%;
        text-align: left;
        height: 34px;
        background-color: #c82333;
        color: white;
    }

</style>
<p class='titulo'><br><strong>Vicepresidencia de Organizaci&oacute;n PSUV - SIGVEN</strong><br></p>
<p class='titulo'>Estructura RAAS de <?php echo ucwords(mb_strtolower($nombreComunidad)); ?><br><br></p>
<p class='titulo'><?php echo ucwords(mb_strtolower($nombrePq)); ?> - <?php echo ucwords(mb_strtolower($nombreUbch)); ?><br><br></p>
                       
        <table border="1">
        <tr>
            <th class="header">#</th=>     

            <th class="header">Responsabilidad</th>
            <th class="header">Nombres</th>
            <th class="header">cedula</th>
            <th class="header">Telefono</th>
            <th class="header">Centro de votaci&oacute;n</th>  
            <th class="header">C</th>
        </tr>

<?php

$sql = "SELECT cedulaCarnet FROM psuv WHERE cedulaCarnet = ?";
$carnetizado = $base->prepare($sql);



        $contador = 1;


    $query22="SELECT nombre, cedula, telefono, cv, cargo FROM estructuraraas WHERE comunidad='$ciudad_id' ORDER BY `id_propio`";  
 $buscarAlumnos22=$conexion->query($query22);
    if ($buscarAlumnos22->num_rows > 0){
        while($fila= $buscarAlumnos22->fetch_assoc()){

      $cedula = $fila['cedula'];
      $carnetizado->execute(array($cedula));
        if ($carnetizado->fetchColumn() != '') {
        $c = 's';
        }else {
            $c = '';
        }

    	echo'
						<tr class="odd gradeX">
						<td>'.$contador++.'</td>
       
						<td class="tablat">'.$fila['cargo'].'</td>
						<td class="tablat">'.$fila['nombre'].'</td>
						<td class="left">'.$fila['cedula'].'</td>
						<td class="left">'.$fila['telefono'].'</td>
						<td class="tablat">'.$fila['cv'].'</td>
                        <td class="tablat">'.$c.'</td>
						
						</tr>
						';
  
}    
}    

?>

        </table>