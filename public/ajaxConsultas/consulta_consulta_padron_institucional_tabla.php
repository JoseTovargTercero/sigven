

<?php
include('../../configurar/configuracion.php');


if(isset($_POST['rep2'])){
$q=$conexion->real_escape_string($_POST['rep2']);




$varCount = 0;
$query5555 = "SELECT * FROM `padronelectoral` WHERE id_int=$q ORDER BY voto";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

if( strlen($fila9855['cv']) > 38){
  $cv = substr($fila9855['cv'],0, 38).'...';
}else{
  $cv = $fila9855['cv'];
}



switch($fila9855['voto']){
  case('OP'):
    ++$op;
    break;
  case('VD'):
    ++$vd;
    break;
  case('VB'):
    ++$vb;
    break;
}



  ++$varCount;

$datos .= '
<tr class="odd gradeX">
<td class=tablat>' . $varCount . '</td>
<td class=tablat>' . ucwords(mb_strtolower($fila9855['nombre'])) . '</td>
<td class=tablat>' . $fila9855['cedula'] . '</td>
<td class=tablat>' . $fila9855['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($cv)) . '</td>
<td class=tablat>' .$fila9855['voto'] . '</td>

</tr>
';
  }
}

?>


<div class="container-fluid py-4">
 <div class="row">
   <div class="col-lg-12">
     <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
      <div class="card-header pb-0 p-3">
        <h6 class="mb-0">Estructura de la UBCH

        <span style="float: right;">
        Votos duros: <?php echo $vd ?>. Votos Blandos: <?php echo $vb ?>. Votos Opositores: <?php echo $op ?>. Total: <?php echo $varCount ?>
        </span>
        </h6>
        


      </div>
      <div class="card-body p-3 row">
        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0">
            <thead>
              <tr>
                
                <th style="padding: 10px !important">#</th>
                <th style="padding: 10px !important">Nombre</th>
                 <th style="padding: 10px !important">Cedula</th>
                 <th style="padding: 10px !important">Telefono</th>
                 <th style="padding: 10px !important">Centro de Votación</th>
                 <th style="padding: 10px !important">Voto</th>

                </tr>
             </thead>
             <tbody>
               
               <?php

echo $datos;

               ?>
             </tbody>
           </table>
         </div>
       </div>
     </div>
   </div>
 </div>
<?php



}
?>

