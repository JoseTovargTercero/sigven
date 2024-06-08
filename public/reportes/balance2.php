

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
<?php
error_reporting(0);
include('../../configurar/configuracion.php');

  
  
$sql = "SELECT COUNT(*) FROM padronelectoral WHERE comunidad = ?";
$padron = $base->prepare($sql);
$sql3 = "SELECT COUNT(*) FROM estructuraubch WHERE ubch= ?";
$countUbch = $base->prepare($sql3);
$sql2 = "SELECT COUNT(*) FROM estructuraraas WHERE comunidad= ? AND tipocargo= ?";
$raas = $base->prepare($sql2);
$sql4 = "SELECT COUNT(*) FROM estructurapatrulla WHERE comunidad= ?";
$patrullas = $base->prepare($sql4);
$ubchCantdades = array();


$query = "SELECT id FROM pais ORDER BY id";
$search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($row = $search->fetch_assoc()) {
            $id = $row['id'];
            $countUbch->execute(array($id));
            $ubchCantdades[$id] = $countUbch->fetchColumn();
        }
}


header( 'Content-type:application/xls' );
header( 'Content-Disposition: attachment; filename=Reporte_Electricidad.xlsx' );
?>


<p class='titulo'><br><strong>Vicepresidencia de Organizaci&oacute;n PSUV - SIGVEN</strong><br></p>
<p class='titulo'>Balance general de informaci&oacute;n cargada al sistema<br><br></p>

<table border="1">

         <tr>
        <th class="header" >PARROQUIA</th>
        <th class="header">UBCH</th>
        <th class="header">COMUNIDAD</th>
        <th class="header">UBCH</th>
        <th class="header">RAAS</th>
        <th class="header">JC</th>
        <th class="header">PATRULLA</th>
        <th class="header">PADRON</th>

<?php
       
              $query22="SELECT ciudad.name, ciudad.id, ciudad.pais_id, pais.name1, continente.name2
                FROM ciudad
                LEFT JOIN pais ON ciudad.pais_id=pais.id
                LEFT JOIN continente ON pais.continente_id=continente.id
                ORDER BY `pais_id` LIMIT 10";  
             $buscarAlumnos22=$conexion->query($query22);
                if ($buscarAlumnos22->num_rows > 0){
                    while($filaAlumnos22= $buscarAlumnos22->fetch_assoc()){
            
                $idComunity = $filaAlumnos22['id'];
                $idUbch = $filaAlumnos22['pais_id'];

                $padron->execute(array($idComunity));
                $raas->execute(array($idComunity, '0'));

                $raas2 = $raas->fetchColumn();
                $raas->execute(array($idComunity, '1'));
                $jefeCalle = $raas->fetchColumn();
                $patrullas->execute(array($idComunity));


 
    	          $tabla .= '<tr class="odd gradeX">
                        <td class="tablat">'.$filaAlumnos22['name2'].'</td>
                        <td class="tablat">'.$filaAlumnos22['name1'].'</td>
						<td class="tablat">'.$filaAlumnos22['name'].'</td>
						<td class="tablat">'.$ubchCantdades[$idUbch].'</td>
                        <td class="tablat">'.$raas2.'</td>
                        <td class="tablat">'.$jefeCalle.'</td>
                        <td class="tablat">'.$patrullas->fetchColumn().'</td>
						<td class="tablat">'.$padron->fetchColumn().'</td>
						</tr>'; 
                 
            } 
        }
         echo $tabla;
         
         

    $raas->closeCursor();
    $patrullas->closeCursor();
    $countUbch->closeCursor();
    $padron->closeCursor();



     
?>

