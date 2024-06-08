<?php
include('../../configurar/configuracion.php');

if (isset($_POST['ubch'])) {

  $q = $conexion->real_escape_string($_POST['ubch']);

  $opositores = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE ubch='$q' AND voto='OP'");
  $cantidadOp = mysqli_num_rows($opositores);

  $blandos = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE ubch='$q' AND voto='VB'");
  $cantidadBd = mysqli_num_rows($blandos);

  $duros = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE ubch='$q' AND voto='VD'");
  $cantidadDu = mysqli_num_rows($duros);


  $totalVotos = $cantidadOp + $cantidadBd + $cantidadDu;


  $query2 = "SELECT * FROM ciudad WHERE pais_id='$q'";
  $buscarComunidades = $conexion->query($query2);
  if ($buscarComunidades->num_rows > 0) {
    while ($row = $buscarComunidades->fetch_assoc()) {
        $resultado .='<li><a href="consultas_Comunidad.php?id='.$row['id'].'">'.$row['name'].'</a></li>';	
	  }
  }





  /*
$contar5 = current($con->query("SELECT COUNT(*) FROM padronelectoral WHERE ubch='$q'")->fetch());     
$contar6 = current($con->query("SELECT COUNT(*) FROM estructuraubch WHERE ubch='$q'")->fetch()); 
*/

  $query5555 = "SELECT * FROM tablamesa WHERE ID_UBCH='$q'";
  $buscarAlumnos5555 = $conexion->query($query5555);
  if ($buscarAlumnos5555->num_rows > 0) {
    while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {
        $codigo = $fila9855['CODIGO'];      
        $direccion = $fila9855['DIRECCION'];
        $electores = $fila9855['ELECTORES'];
        $mesas = $fila9855['MESA'];
    }
  }

?>


  <div class="row">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header pb-0 p-3">
          <h6 class="mb-0">Consejos Comunales</h6>
        </div>
        <div class="card-body p-3">

          <ul style="list-style: none; padding-left: 1rem;">
            <?php echo $resultado ?>
          </ul>

        </div>
      </div>
    </div>
    <div class="col-xl-6">
      <div class="row">
        <div class="col-md-6">
          <div class="card h-100" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-user-following"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Duros</h6>
              <span class="text-xs">Votos Duros</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadDu ?></h5>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card h-100" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-user-follow"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Blandos</h6>
              <span class="text-xs">Votos Blandos</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadBd ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>
    

    <div class="col-xl-12" style="margin-top: 20px;">
      <div class="row">

        <div class="col-md-9">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <div class="row">
                <div class="col-6 d-flex align-items-center">
                  <h6 class="mb-0">Información de la UBCH</h6>
                </div>
              </div>
            </div>
            <div class="card-body p-3">
              
              
                <h6 class="mb-0" style="margin-top: -12px;">Dirección</h6>
                <p class="text-sm opacity-8 mb-0"><?php echo $direccion; ?></p>

                <h6 class="mb-0">Mesas: <?php echo $mesas ?></h6>
                <h6 class="mb-0">Electores: <?php echo $totalVotos." / ".$electores ?></h6>

              
              
           
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-warning shadow text-center border-radius-lg">
                <i class="line icon-user-unfollow"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Opositores</h6>
              <span class="text-xs">Votos Opositores</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadOp ?></h5>
            </div>
          </div>
        </div>

      </div>
    </div>

    </div>
    </div>


    


  <?php } else {
  ?>
<style>
    .background{
      background-image: linear-gradient(
        90deg,
        #FFFFFF 0%, #FFFFFF 40%,
        #f3f4f5 50%, #f3f4f5 55%,
        #FFFFFF 65%, #FFFFFF 100%
      );
      background-size: 400%;
      animation: shimer 1500ms infinite;
    }
    @keyframes shimer{
      from{ background-position: 100% 100%;}
      to{background-position: 0% 0%;}
    }
  </style>
<div class="row">
    <div class="col-md-6">
      <div class="card h-100 background" >
       
      </div>
    </div>
    
    <div class="col-xl-6">
      <div class="row">
        <div class="col-md-6">
          <div class="card background" style="place-content: center; display: grid;">
            <div class=" mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-white border-radius-lg">
              
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center ">
              <h6 class="text-center mb-0">&nbsp;</h6>
              <span class="text-xs">&nbsp;</span>
              <hr class="horizontal dark my-3"  style="background-image: none !important">
              <h5 class="mb-0">&nbsp;</h5>
            </div>
          </div>
        </div>

        <div class="col-md-6">
      <div class="card h-100 background" >
       
      </div>
    </div>
      
      
      </div>
    </div>


    <div class="col-xl-12" style="margin-top: 20px;">
      <div class="row">

      <div class="col-md-9">
      <div class="card h-100 background" >
       
      </div>
    </div>



        <div class="col-md-3">
          <div class="card background" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center" style="background-color: #fff0;">
              <div class="icon icon-shape icon-lg bg-gradient-white border-radius-lg" >
              
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">&nbsp;</h6>
              <span class="text-xs">&nbsp;</span>
              <hr class="horizontal dark my-3" style="background-image: none !important">
              <h5 class="mb-0">&nbsp;</h5>
            </div>
          </div>
        </div>

      </div>
    </div>
  <?php } ?>