<?php 
error_reporting(0);
include('../../configurar/configuracion.php');
$municipio_id = $_POST['municipio_id'];
$comision = $_POST['comisionesUbchInput'];
$arrayMunicipios = array('ATURES','ALTO ORINOCO','AUTANA','MANAPIARE','MAROA','RIO NEGRO','ATABAPO');
$nombreMunicipio = $arrayMunicipios[$municipio_id-1];

header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename='.$comision.' - SIGVEN - '.$nombreMunicipio.' - '.date('Y_m_d h-i a').' .xls' );



  
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
<p class='titulo'><?php echo ucwords(mb_strtolower($comision)); ?> - Estructura de UBCH de <?php echo ucwords(mb_strtolower($nombreMunicipio)); ?><br><br></p>


        <table border="1">
        <tr>
            <th class="header">#</th=>     
            <th class="header">Parroquia</th>
            <th class="header">UBCH</th>
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




    $query22="SELECT pais.name1, continente.name2, estructuraubch.nombre, estructuraubch.cedula, estructuraubch.telefono, estructuraubch.cv
    FROM estructuraubch
    LEFT JOIN pais ON estructuraubch.UBCH=pais.id
    LEFT JOIN continente ON pais.continente_id=continente.id
    WHERE estructuraubch.cargo='$comision' AND estructuraubch.municipio='$municipio_id' ORDER BY `parroquia`,`ubch`";  

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
                        <td class="tablat">'.$fila['name2'].'</td>
                        <td class="tablat">'.$fila['name1'].'</td>
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