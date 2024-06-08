<?php

include('../configurar/configuracion.php');
/*
$reultadoArray = array();
$reultadoArray2 = array();

$query = "SELECT * FROM `elec_pro`";
  $buscarAlumnos = $conexion->query($query);
  if ($buscarAlumnos->num_rows > 0) {
    while ($filaAlumnos = $buscarAlumnos->fetch_assoc()) {

      $id_parroquia = $filaAlumnos['PARROQUIA'];
      $id_vivienda = $filaAlumnos['COMUNIDAD'];

        if (@$reultadoArray[$id_vivienda]) {
          if ($reultadoArray[$id_vivienda][1] == $id_parroquia) {
            $reultadoArray[$id_vivienda][2] = $reultadoArray[$id_vivienda][2] + 1;
          }else {
            $reultadoArray[$id_vivienda] = array($id_vivienda , $id_parroquia, 1);
          }
         }else {
             $reultadoArray[$id_vivienda] = array($id_vivienda , $id_parroquia, 1);
         }

    }
  }

  $query2 = "SELECT * FROM `elec_jefes`";
  $buscarAlumnos2 = $conexion->query($query2);
  if ($buscarAlumnos2->num_rows > 0) {
    while ($filaAlumnos2 = $buscarAlumnos2->fetch_assoc()) {

      $id_parroquia2 = $filaAlumnos2['pq'];
      $id_vivienda2 = $filaAlumnos2['comunidad'];

        if (@$reultadoArray2[$id_vivienda2]) {
          if ($reultadoArray2[$id_vivienda2][1] == $id_parroquia2) {
            $reultadoArray2[$id_vivienda2][2] = $reultadoArray2[$id_vivienda2][2] + 1;
          }else {
            $reultadoArray2[$id_vivienda2] = array($id_vivienda2 , $id_parroquia2, 1);
          }
         }else {
             $reultadoArray2[$id_vivienda2] = array($id_vivienda2 , $id_parroquia2, 1);
         }

    }
  }

  <table>
  <thead>
    <th>#</th>
    <th>Pq</th>
    <th>Comunidad</th>
    <th>Cantidad</th>
  </thead>
  <tbody>
    <?php
    $count = 1;
      foreach ($reultadoArray as $value) {
    ?>
    <tr>
      <td><?php echo $count++ ?></td>
      <td><?php echo $value[1] ?></td>
      <td><?php echo $value[0] ?></td>
      <td><?php echo $value[2] ?></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>

*/
?>



<table>
  <thead>
    <th>#</th>
    <th>Pq</th>
    <th>Comunidad</th>
    <th>Cantidad JC</th>
    <th>Cantidad PR</th>
    <th>Filtro XLS</th>
  </thead>
  <tbody>
 
 
   


<?php


$query2 = "SELECT * FROM `ele_final_jefes`";
$buscarAlumnos2 = $conexion->query($query2);
if ($buscarAlumnos2->num_rows > 0) {
  while ($filaAlumnos2 = $buscarAlumnos2->fetch_assoc()) {

    $id_parroquia2 = $filaAlumnos2['Pq'];
    $id_vivienda2 = $filaAlumnos2['Comunidad'];
    $cantidad2 = $filaAlumnos2['Cantidad'];


    $query = "SELECT * FROM `ele_final_pro` WHERE Pq='$id_parroquia2' AND Comunidad='$id_vivienda2'";
    $buscarAlumnos = $conexion->query($query);
    if ($buscarAlumnos->num_rows > 0) {
      while ($filaAlumnos = $buscarAlumnos->fetch_assoc()) {
        $pq = $filaAlumnos['Pq'];
        $comunidad = $filaAlumnos['Comunidad'];
        $cantidad = $filaAlumnos['Cantidad'];
      }
    }else {
      $cantidad = 'NE';
    }

    if ($cantidad == 'NE') {
      $color = 'gray';
      $filtroXl = 'NE';
    }elseif($cantidad == $cantidad2) {
      $color = 'red';
      $filtroXl = 'OK';
    }elseif ($cantidad != $cantidad2) {
      $color = 'blue';
      $filtroXl = 'NC';
    }

    echo '   <tr style="color: '.$color.'">
    <td>'.$count++.'</td>
    <td>'.$filaAlumnos2['Pq'].'</td>
    <td>'.$id_vivienda2.'</td>
    <td>'.$cantidad2.'</td>
    <td>'.$cantidad.'</td>
    <td>'.$filtroXl.'</td>
  </tr>';

  }
}


/*
ele_final_jefes
ele_final_pro
*/


?>


</tbody>
</table>