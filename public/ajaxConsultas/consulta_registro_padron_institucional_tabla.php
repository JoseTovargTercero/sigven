<script>
    $(document).ready(function() {
      $(".ron").click(function() {

        $("html,body").animate({
          scrollTop: $('.hhh').offset.top
        }, '0.5');
      });
    });
  </script>

<?php
include('../../configurar/configuracion.php');

if (isset($_POST['entidad'])) {

  $q = $conexion->real_escape_string($_POST['entidad']);

  $varCount = 0;

  $query55 = "SELECT * FROM `padronelectoral` WHERE id_int='$q' ORDER BY id DESC";
  $buscarAlumnos5555 = $conexion->query($query55);
  if ($buscarAlumnos5555->num_rows > 0) {
    while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

      echo '
<div  id="taf' . $fila9855['id'] . '" style="display: none;" class="sss hhh container-fluid py-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Editando datos</h6>
          </div>
          <div class="card-body p-3 ">
            <form action="../configurar/update/padron_Institucional.php" method="post" class="row"> 
            

          <input style="margin-bottom: 16px;" readonly name="id" id="id" class="form-control" required hidden value="' . $fila9855['id'] . '">
            <div class="col-lg-3">
                <label>Cedula</label>
                <input style="margin-bottom: 16px;"  type="tel" pattern="[0-9]{11}"  readonly name="cedula" id="cedula" class="form-control" required  value="' . $fila9855['cedula'] . '">
                </div>
                <div class="col-lg-3">
                  <label>Nombre</label>
                  <input style="margin-bottom: 16px;" readonly name="nombre" id="nombre" class="form-control" required  value="' . $fila9855['nombre'] . '">
                </div>
                <div class="col-lg-3">
                  <label>Telefono</label>
                  <input style="margin-bottom: 16px;"  type="tel" pattern="[0-9]{11}"  name="telefono" id="telefono" class="form-control" required placeholder="Telefono"  value="' . $fila9855['telefono'] . '">
                </div>
                <div class="col-lg-3">
                <label> &nbsp; </label>
                <input style="margin-bottom: 16px;"  type="submit" class="btn bg-gradient-primary w-100" value="ACTUALIZAR" />
                </div>
                </form>
          </div>
        </div>
      </div>
    </div>
    </div>

    <script>
    $(document).ready(function(){
      $("#edit' . $fila9855['id'] . '").click(function(){
        $(".sss").hide();
        $("#taf' . $fila9855['id'] . '").toggle(700, "swing");
      })
    })
  </script>';




      if (strlen($fila9855['cv']) > 38) {
        $cv = substr($fila9855['cv'], 0, 38) . '...';
      } else {
        $cv = $fila9855['cv'];
      }

      ++$varCount;
      if ($fila9855['firma'] == "SI") {
        $resultadofirma = '<td class=tablat violet>' . $varCount . '</td>';
      } else {
        $resultadofirma = '<td class=tablat>' . $varCount . '</td>';
      }

      $datos .= '
<tr class="odd gradeX">
' . $resultadofirma . '
<td class=tablat>' . ucwords(mb_strtolower($fila9855['nombre'])) . '</td>
<td class=tablat>' . $fila9855['cedula'] . '</td>
<td class=tablat>' . $fila9855['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($cv)) . '</td>
<td class="icono ron"><a  href="#taf' . $fila9855['id'] . '" id="edit' . $fila9855['id'] . '"><i class="line icon-pencil"></i></a></td>
<td class="icono">
<a class="contenidoLock" id="unlock_' . $fila9855['id'] . '"><i class="line icon-lock"></i></a>
<a style="display: none" id="lock_' . $fila9855['id'] . '" href="../configurar/delete/padron_Institucional.php?id=' . $fila9855['id'] . '&nombre=' . $fila9855['nombre'] . '&cedula=' . $fila9855['cedula'] . '"><i class="line icon-user-unfollow"></i></a>
</td>
</tr>

<script>
$(document).ready(function(){
$("#unlock_' . $fila98['id'] . '").click(function(){
$("#unlock_' . $fila9855['id'] . '").hide();
$("#lock_' . $fila9855['id'] . '").show();
})
})
</script>  ';
    }
  }

  ?>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Patrulla Territorial de la <?php echo $q ?></h6>
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

                  </tr>
                </thead>
                <tbody>

                  <?php
                  echo  $datos;
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php

  } else {
    ?>




<?php


if ($_SESSION['entidad_inst'] != "") {
  
  
  $entidad_inst = $_SESSION['entidad_inst'];
     
      $query55 = "SELECT * FROM `padronelectoral` WHERE id_int='$entidad_inst' ORDER BY id DESC";
      $buscarAlumnos5555 = $conexion->query($query55);
      if ($buscarAlumnos5555->num_rows > 0) {
        while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

          echo '
<div  id="taf' . $fila9855['id'] . '" style="display: none;" class="sss hhh container-fluid py-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Editando datos</h6>
          </div>
          <div class="card-body p-3 ">
            <form action="../configurar/update/padron_Institucional.php" method="post" class="row"> 
            

          <input style="margin-bottom: 16px;" readonly name="id" id="id" class="form-control" required hidden value="' . $fila9855['id'] . '">
            <div class="col-lg-3">
                <label>Cedula</label>
                <input style="margin-bottom: 16px;"  type="tel" pattern="[0-9]{11}"  name="cedula" id="cedula" class="form-control" required  value="' . $fila9855['cedula'] . '">
                </div>
                <div class="col-lg-3">
                  <label>Nombre</label>
                  <input style="margin-bottom: 16px;" readonly name="nombre" id="nombre" class="form-control" required  value="' . $fila9855['nombre'] . '">
                </div>
                <div class="col-lg-3">
                  <label>Telefono</label>
                  <input style="margin-bottom: 16px;"  type="tel" pattern="[0-9]{11}"  name="telefono" id="telefono" class="form-control" required placeholder="Telefono"  value="' . $fila9855['telefono'] . '">
                </div>
                <div class="col-lg-3">
                <label> &nbsp; </label>
                <input style="margin-bottom: 16px;"  type="submit" class="btn bg-gradient-primary w-100" value="ACTUALIZAR" />
                </div>
                </form>
          </div>
        </div>
      </div>
    </div>
    </div>

    <script>
    $(document).ready(function(){
      $("#edit' . $fila9855['id'] . '").click(function(){
        $(".sss").hide();
        $("#taf' . $fila9855['id'] . '").toggle(700, "swing");
      })
    })
  </script>
  
';

++$varCount;
if ($fila9855['firma'] == "SI") {
  $resultadofirma = '<td class=tablat violet>' . $varCount . '></td>';
} else {
  $resultadofirma = '<td class=tablat>' . $varCount . '</td>';
}

if (strlen($fila9855['cv']) > 38) {
  $cv = substr($fila9855['cv'], 0, 38) . '...';
} else {
  $cv = $fila9855['cv'];
}




$datos .= '
<tr class="odd gradeX ">
' . $resultadofirma . '
<td class=tablat>' . ucwords(mb_strtolower($fila9855['nombre'])) . '</td>
<td class=tablat>' . $fila9855['cedula'] . '</td>
<td class=tablat>' . $fila9855['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($cv)) . '</td>
<td class="icono ron"><a  href="#taf' . $fila9855['id'] . '" id="edit' . $fila9855['id'] . '"><i class="line icon-pencil"></i></a></td>
<td class="icono">
<a class="contenidoLock" id="unlock_' . $fila9855['id'] . '"><i class="line icon-lock"></i></a>
<a style="display: none" id="lock_' . $fila9855['id'] . '" href="../configurar/delete/padron_Institucional.php?id=' . $fila9855['id'] . '&nombre=' . $fila9855['nombre'] . '&cedula=' . $fila9855['cedula'] . '"><i class="line icon-user-unfollow"></i></a>
</td>
</tr>

<script>
$(document).ready(function(){
$("#unlock_' . $fila9855['id'] . '").click(function(){
$("#unlock_' . $fila9855['id'] . '").hide();
$("#lock_' . $fila9855['id'] . '").show();
})
})
</script>  

';
        }
      }

      ?>
      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-lg-12">
            <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Padron Electoral</h6>
              </div>
              <div class="card-body p-3 row">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 ">
                    <thead>
                      <tr>

                        <th style="padding: 10px !important">#</th>
                        <th style="padding: 10px !important">Nombre</th>
                        <th style="padding: 10px !important">Cedula</th>
                        <th style="padding: 10px !important">Telefono</th>
                        <th style="padding: 10px !important">Centro de Votación</th>

                      </tr>
                    </thead>
                    <tbody>
                        <?php echo $datos; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>


    <?php
    }
  }

    ?>