<?php 
error_reporting(0);
include('../../configurar/configuracion.php');

header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename=Patrullas Territoriales del Estado - SIGVEN - '.date('Y_m_d h-i a').' .xls' );
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
<p class='titulo'>Patrullas Territoriales del Estado<br><br></p>
                       
  

<?php


////////////////////////////////////////////////////////

$sql2 = "SELECT nombre, cedula, telefono, cv, cargo FROM estructurapatrulla WHERE calle = ? AND comunidad= ? ";
$resultado2 = $base->prepare($sql2);

////////////////////////////////////////////////////////
$sql = "SELECT cedulaCarnet FROM psuv WHERE cedulaCarnet = ?";
$carnetizado = $base->prepare($sql);
$contador = 1;

$query22="SELECT estructuraraas.nombre, estructuraraas.comunidad, estructuraraas.cedula, estructuraraas.telefono, estructuraraas.cv, estructuraraas.cargo, continente.name2, pais.name1, ciudad.name 
FROM estructuraraas
LEFT JOIN ciudad ON estructuraraas.comunidad=ciudad.id
LEFT JOIN pais ON estructuraraas.ubch=pais.id
LEFT JOIN continente ON estructuraraas.parroquia=continente.id WHERE  estructuraraas.tipocargo='1' ORDER BY `parroquia`,`ubch`,`comunidad`,`id_propio`";  
 $buscarAlumnos22=$conexion->query($query22);
    if ($buscarAlumnos22->num_rows > 0){
      echo '<table border="1">
      <tr>
          <th class="header">#</th=>     
          <th class="header">Parroquia</th>
          <th class="header">UBCH</th>
          <th class="header">Comunidad</th>
          <th class="header">Calle</th>
          <th class="header">Responsabilidad</th>
          <th class="header">Nombres</th>
          <th class="header">cedula</th>
          <th class="header">Telefono</th>
          <th class="header">Centro de votaci&oacute;n</th>  
          <th class="header">C</th>
      </tr>';
        while($fila= $buscarAlumnos22->fetch_assoc()){


      $cargo = $fila['cargo'];
      $cedula = $fila['cedula'];
      
      $carnetizado->execute(array($cedula));
        if ($carnetizado->fetchColumn() != '') {
        $c = 's';
        }else {
            $c = '';
        }

    	$tabla .= '<tr class="odd gradeX">
						<td style="background-color: #cfcfcf">'.$contador++.'</td>
						
                        <td style="background-color: #cfcfcf">'.$fila['name2'].'</td>
                        <td style="background-color: #cfcfcf">'.$fila['name1'].'</td>
                        <td style="background-color: #cfcfcf">'.$fila['name'].'</td>

						<td style="background-color: #cfcfcf">'.$cargo.'</td>
						<td style="background-color: #cfcfcf">'.$fila['cargo'].'</td>
						<td style="background-color: #cfcfcf">'.$fila['nombre'].'</td>
						<td style="background-color: #cfcfcf">'.$fila['cedula'].'</td>
						<td style="background-color: #cfcfcf">'.$fila['telefono'].'</td>
						<td style="background-color: #cfcfcf">'.$fila['cv'].'</td>
                        <td style="background-color: #cfcfcf">'.$c.'</td>
						</tr>';

                        $comunidad = $fila['comunidad'];

        $resultado2->execute(array($cargo, $comunidad));
        while ($registros = $resultado2->fetch(PDO::FETCH_ASSOC)) {

            $cedula = $registros['cedula'];
            $carnetizado->execute(array($cedula));
            if ($carnetizado->fetchColumn() != '') {
            $c = 's';
            }else {
                $c = '';
            }
    


            $tabla .= '<tr class="odd gradeX">
            <td>'.$contador++.'</td>
            					
            <td>'.$fila['name2'].'</td>
            <td>'.$fila['name1'].'</td>
            <td>'.$fila['name'].'</td>
            <td class="tablat">'.$cargo.'</td>
            <td class="tablat">'.$registros['cargo'].'</td>
            <td class="tablat">'.$registros['nombre'].'</td>
            <td class="left">'.$registros['cedula'].'</td>
            <td class="left">'.$registros['telefono'].'</td>
            <td class="tablat">'.$registros['cv'].'</td>
            <td class="tablat">'.$c.'</td>
            </tr>';
        }
                    

  
}    
}    
$resultado2->closeCursor();

echo $tabla;
?>

        </table>