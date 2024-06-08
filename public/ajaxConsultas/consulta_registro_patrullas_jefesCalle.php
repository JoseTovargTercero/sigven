

<?php
include('../../configurar/configuracion.php');


if(isset($_POST['rep3'])){

$q=$conexion->real_escape_string($_POST['rep3']);
    
$query="SELECT * FROM `estructuraraas` WHERE comunidad='$q' AND tipocargo='1' ORDER BY cargo";
$buscarAlumnos=$conexion->query($query);
if ($buscarAlumnos->num_rows > 0){
  $tabla= ' <div class="col-lg-12">
  <label>Calle</label>
  <select class="form-control" id="calle" name="calle" required>
  <option></option>';
  
    while($filaAlumnos= $buscarAlumnos->fetch_assoc())	{
    $tabla.= '<option value="'.$filaAlumnos['cargo'].'">'.$filaAlumnos['cargo'].'&nbsp;-&nbsp;'.$filaAlumnos['nombre'].'&nbsp;-&nbsp;'.$filaAlumnos['cedula'].'</option>';
    }

  $tabla.= '
    </select>
  </div>';
}else{
    $tabla= ' <div class="col-lg-12">
    <label>Calle</label>
      <select class="form-control" id="calle" name="calle" required>
      <option></option>
     
        <option value=""></option>
    </select>
  </div>';
}



}else{
    
  
  $comunidad = $_SESSION['comunidad_patr'];
  $calle_patr = $_SESSION['calle_patr'];

  if($_SESSION['calle_patr'] == ""){
    $tabla= ' <div class="col-lg-12">
    <label>Calle</label>
      <select class="form-control" id="calle" name="calle" required>
     
        <option value="">SELECCIONE UNA COMUNIDAD</option>
    </select>
  </div>';

}else{


  $query="SELECT * FROM `estructuraraas` WHERE comunidad='$comunidad' AND cargo='$calle_patr'";
  $buscarAlumnos=$conexion->query($query);
  if ($buscarAlumnos->num_rows > 0){
      while($filaAlumnos= $buscarAlumnos->fetch_assoc())	{
      $nombre = $filaAlumnos['nombre']; 
      $cedula = $filaAlumnos['cedula'];
      }
  }

    $tabla= '<div class="col-lg-12">
    <label>Calle</label>
      <select class="form-control" id="calle" name="calle" required>
        <option value="'.$calle_patr.'">'.$calle_patr.' - '.$nombre.' - '.$cedula.'</option>
    </select>
  </div>';
  }


}

echo $tabla;
?>

