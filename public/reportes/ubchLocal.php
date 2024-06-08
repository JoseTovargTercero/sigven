<?php 
error_reporting(0);
include('../../configurar/configuracion.php');
$municipio_id = $_POST['municipio_id'];
$continente_id = $_POST['continente_id'];
$pais_id = $_POST['pais_id'];


if($pais_id == '' || $continente_id == '' || $municipio_id == '' ) {
    define('PAGINA_INICIO', '../reportes.php');
    header('Location: ' . PAGINA_INICIO);
}



$query = "SELECT pais.name1, continente.name2 FROM pais
LEFT JOIN continente ON pais.continente_id=continente.id
 WHERE pais.id='$pais_id'";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $nombreUbch = $row['name1'];
            $nombrePq = $row['name2'];
        }
}

header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename=UBCH - '.$nombreUbch.' - SIGVEN - '.date('Y_m_d h-i a').' .xls' );
 
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
<p class='titulo'>Estructura de la UBCH: <?php echo ucwords(mb_strtolower($nombreUbch)); ?><br><br></p>


        <table border="1">
        <tr>
            <th class="header">#</th=>     
            <th class="header">Parroquia</th>
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


    $query22="SELECT nombre, cedula, telefono, cv, cargo FROM estructuraubch WHERE ubch='$pais_id' ORDER BY `parroquia`,`ubch`";  
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
                        <td class="tablat">'.$nombrePq.'</td>
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