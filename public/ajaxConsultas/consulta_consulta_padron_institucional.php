<?php
include('../../configurar/configuracion.php');

if (isset($_POST['entidad'])) {
  $q=$conexion->real_escape_string($_POST['entidad']);

  $query2 = "SELECT * FROM instituciones WHERE id='$q'";
  $buscarComunidades = $conexion->query($query2);
  if ($buscarComunidades->num_rows > 0) {
    while ($row = $buscarComunidades->fetch_assoc()) {
      
        $direccion = strtoupper($row['direccion']);
        $telefono = $row['telefono'];	
        $correo = strtoupper($row['correo']);	
        $director =  strtoupper($row['nombreDirector']);
        $CedulaDirector = $row['CedulaDirector'];	
	  }
  }

?>

    

<div class="col-md-12">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Información</h6>
            </div>
            <div class="card-body p-3">
            <ul class="list">
              <li> <strong>Direcci&oacute;n:</strong> <?php echo $direccion ?></li>
              <li> <strong>Correo El&eacute;ctronico:</strong> <?php echo $correo ?></li>
              <li> <strong>Telefono:</strong> <?php echo $telefono ?></li>
              <li> <strong>Director:</strong> <?php echo $director ?></li>
              <li> <strong>Cedula:</strong> <?php echo $CedulaDirector ?></li>
            </ul>
           <br>
           <br>
          </div>
          </div>
        </div>
        

  <?php } else {
  ?>

        <div class="col-md-12">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Información</h6>
            </div>
            <div class="card-body p-3">

            <br>
           <br>      <br>
           <br>      <br>
           <br>      <br>
           <br>
          </div>
          </div>
        </div>
        
      

  <?php } ?>