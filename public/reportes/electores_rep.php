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



$query = "SELECT pais.name1, pais.codigo, continente.name2 FROM pais
LEFT JOIN continente ON pais.continente_id=continente.id
 WHERE pais.id='$pais_id'";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $nombreUbch = $row['name1'];
            $nombrePq = $row['name2'];
            $codigo = $row['codigo'];
        }
}
header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename=Electores (REP) - '.$nombreUbch.' - SIGVEN - '.date('Y_m_d h-i a').' .xls' );
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
<p class='titulo'>Electores en el REP de: <?php echo ucwords(mb_strtolower($nombreUbch)); ?><br><br></p>
<p class='titulo'>Parroquia: <?php echo ucwords(mb_strtolower($nombrePq)); ?><br><br></p>


        <table border="1">
        <tr>
            <th class="header">#</th=>     
            <th class="header">Nombres</th>
            <th class="header">cedula</th>
            <th class="header">C</th>
            <th class="header">S</th>
        </tr>

<?php


$sql = "SELECT cedulaCarnet FROM psuv WHERE cedulaCarnet = ?";
$carnetizado = $base->prepare($sql);

$sql2 = "SELECT id FROM padronelectoral WHERE cedula = ?";
$padron = $base->prepare($sql2);



        $contador = 1;


    $query22="SELECT nombre, cedula FROM rep28 WHERE cod='$codigo'";  
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


      $padron->execute(array($cedula));
        if ($padron->fetchColumn() != '') {
        $p = 's';
        }else {
            $p = '';
        }

    	echo'
						<tr class="odd gradeX">
						<td>'.$contador++.'</td>
						<td class="tablat">'.$fila['nombre'].'</td>
						<td class="left">'.$fila['cedula'].'</td>
                        <td class="tablat">'.$c.'</td>
                        <td class="tablat">'.$p.'</td>
						
						</tr>
						';
  
}    
}    

?>

        </table>