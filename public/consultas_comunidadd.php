<?php
include('../configurar/configuracion.php');

?>

<table class="table align-items-center mb-0">
<thead>
  <tr>
    <th>Calle</th>
    <th>Nombre</th>
    <th>Cedula</th>
    <th>Telefono</th>
    <th>Mcp</th>
    <th>Pq</th>
    <th>Ubch</th>
    <th>Comunidad</th>
  </tr>
</thead>
<tbody>
  <?php
  //////

  $query555 = "SELECT continente.name2, pais.name1, mcp.mcp, pais.id, ciudad.name, padronelectoral.calle, padronelectoral.nombre, padronelectoral.cedula, padronelectoral.telefono, padronelectoral.cv FROM `padronelectoral`
  LEFT JOIN `ciudad` ON padronelectoral.comunidad=ciudad.id 
  LEFT JOIN `pais` ON pais.id=padronelectoral.ubch 
  LEFT JOIN `continente` ON continente.id=padronelectoral.parroquia 
  LEFT JOIN `mcp` ON mcp.id=padronelectoral.municipio ";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {



      ++$varCount;


      echo '
          <tr class="odd gradeX">
          ' . $resultadofirma . '


          <td>' . ucwords(mb_strtolower($fila98555['calle'])) . '</td>
          <td>' . ucwords(mb_strtolower($fila98555['nombre'])) . '</td>
          <td>' . $fila98555['cedula'] . '</td>
          <td>' . $fila98555['telefono'] . '</td>
          <td>' . $fila98555['mcp'] . '</td>
          <td>' . $fila98555['name2'] . '</td>
          <td>' . $fila98555['name1'] . '</td>
          <td>' . $fila98555['name'] . '</td>

          </tr>
          ';
    }
  }



