<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');







if ($_SESSION['nivel'] != 1 && $_SESSION['entidad'] != '1') {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}


$query = $conexion->query("select * from mcp");
$countries = array();
while ($r = $query->fetch_object()) {
  $countries[] = $r;
}

$query = $conexion->query("select * from tablamesa ORDER BY CODIGO");
$centros = array();
while ($rv = $query->fetch_object()) {
  $centros[] = $rv;
}



if ($_SESSION['noticia'] != "") {
  $alerta = explode("/", $_SESSION['noticia']);
  unset($_SESSION['noticia']);
} else {
  $alerta[3] = "NO ASIGNADO";
  unset($_SESSION['noticia']);
}


if ($_SESSION['ubch_ubch'] != "") {

  $municipio = $_SESSION['municipio_ubch'];
  $parroquia = $_SESSION['parroquia_ubch'];
  $centrodev = $_SESSION['ubch_ubch'];

  $query = "SELECT pais.name1, continente.name2, mcp.mcp FROM `pais`
   LEFT JOIN continente ON continente.id=pais.continente_id
   LEFT JOIN mcp ON mcp.id=continente.mcp_id 
   WHERE pais.id='$centrodev'";

  $buscarAlumnos = $conexion->query($query);
  if ($buscarAlumnos->num_rows > 0) {
    while ($filaAlumnos = $buscarAlumnos->fetch_assoc()) {
      $name3 = $filaAlumnos['mcp'];
      $name2 = $filaAlumnos['name2'];
      $name1 = $filaAlumnos['name1'];
    }
  }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Registros: UBCH
  </title>
  <link rel="stylesheet" href="../vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="../vendors/animatedLines/css/simple-line-icons.css">
  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link rel="stylesheet" href="../vendors/validator/fv.css" type="text/css" />
  <link href="../assets/css/sidebar.css" rel="stylesheet" />
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script type="text/javascript" src="jquery.min.js"></script>
  <?php  include('darkMode.php');  ?>
  <script>
    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Registros');
      h6.text('Registro de UBCH');

      var active = document.getElementById('entidad');
      active.classList.add('active');
    })






    /*Funcion que activa las notificaciones de sweetAlert2 para los tipos: success, error*/
    var title = "<?php echo $alerta[0]; ?>";
    var text = "<?php echo $alerta[1]; ?>";
    var tipoAlert = "<?php echo $alerta[2]; ?>";
    var timer = "<?php echo $alerta[3]; ?>";

    if (timer != "NO ASIGNADO") {
      $(document).ready(function() {

        function alertaNoticia(title, text, tipo) {
          Swal.fire({
            type: tipo,
            title: title,
            text: text,
            timer: timer, //el tiempo que dura el mensaje en ms

          });
        };

        alertaNoticia(title, text, tipoAlert);

      });

    }
  </script>



</head>

<body class="g-sidenav-show bg-gray-100">

  <?php require_once('includes/menu.php');  ?>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php echo $navbar; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <form class="row" role="form" action="../configurar/create/nueva_ubch.php" method="POST">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">UBCH</h6>
            </div>
            <div class="card-body p-3">


              <p style="color: #c5c5c5; margin-top: -15px;     opacity: 0.5 !important;">Información de la UBCH</p>

              <label>Municipio</label>
              <div class="mb-3">
                <select class='form-control' id="municipio_id" name="municipio_id" required>

                  <option value="">SELECCIONE</option>

                  <?php foreach ($countries as $c) : ?>
                    <option value="<?php echo $c->id; ?>"><?php echo $c->mcp; ?></option>
                  <?php endforeach; ?>
                </select>

              </div>

              <label>Parroquia</label>
              <div class="mb-3">
                <select class='form-control' id="continente_id" name="continente_id" required>
                  <option value="">SELECCIONE</option>
                </select>
              </div>

              <div class="mb-3">
                <label>Nombre</label>
                <input required type="text" name="ubchName" class="form-control" placeholder="Nombre de la UBCH">
              </div>



            </div>
          </div>
        </div>



        <div class="col-lg-6">
          <div class="card h-100  cblur shadow-blu ">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Información del Centro de Votación</h6>
            </div>
            <div class=" p-3 row">

              <p style="color: #c5c5c5; margin-top: -15px;  opacity: 0.5 !important;">Centro de Votación Asociado</p>

              <div class="mb-3">
                <label>Centro de Votación Asociado</label>
                <select class='form-control' id="centro" name="centro" required>
                  <option value="">SELECCIONE</option>

                  <?php foreach ($centros as $cv) : ?>
                    <option value="<?php echo $cv->CODIGO; ?>"><?php echo $cv->NOMBRE; ?></option>
                  <?php endforeach; ?>
                </select>

              </div>
              <div class="mb-3">
                <label>Código</label>
                <input readonly type="text" id="codigo" name="codigo" class="form-control" placeholder="Código Asociado">
              </div>


              <div  class="text-center">
                <input type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="registrar" />
              </div>



            </div>
          </div>



      </form>



    </div>

  </div>


  <?php
$varCount = 0;
$query5555 = "SELECT continente.name2, pais.name1, mcp.mcp, pais.id FROM `pais`
LEFT JOIN `continente` ON continente.id=pais.continente_id 
LEFT JOIN `mcp` ON mcp.id=continente.mcp_id
 ORDER BY pais.codigo";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

    echo '
<div  id="taf'.$fila9855['id'].'" style="display: none;" class="sss hhh container-fluid py-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Editando datos</h6>
          </div>
          <div class="card-body p-3 ">
            <form action="../configurar/update/nueva_ubch.php" method="post" class="row"> 
            

          <input style="margin-bottom: 16px;" readonly name="id" id="id" class="form-control" required hidden value="'.$fila9855['id'].'">
                <div class="col-lg-3">
                  <label>Municipio</label>
                  <input style="margin-bottom: 16px;" class="form-control" readonly  value="'.$fila9855['mcp'].'">
                </div>
     
                <div class="col-lg-3">
                  <label>Parroquia</label>
                  <input style="margin-bottom: 16px;"  class="form-control" readonly  value="'.$fila9855['name2'].'">
                </div>
     
     
     
                <div class="col-lg-3">
                  <label>UBCH</label>
                  <input style="margin-bottom: 16px;" name="nombre" id="nombre" class="form-control" required  value="'.$fila9855['name1'].'">
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
      $("#edit'.$fila9855['id'].'").click(function(){
        $(".sss").hide();
        $("#taf'.$fila9855['id'].'").toggle(700, "swing");
      })
    })
  </script>
  
';





$datos .= '
   <tr class="odd gradeX ">
   <td class=tablat>' . ++$varCount . '</td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['mcp'])) . '</td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['name2'])) . '</td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['name1'])) . '</td>

   <td class="icono ron"><a  href="#taf'.$fila9855['id'].'" id="edit'.$fila9855['id'].'"><i class="line icon-pencil"></i></a></td>


 </tr>';
  }
}

?>

   <div class="container-fluid py-4" >
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%); height: 700px !important; overflow-y: scroll;">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Instituciones y Movimientos Sociales</h6>
          </div>
          <div class="card-body p-3 row">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0 ">
                <thead>
                  <tr>
                    <th style="padding: 10px !important">#</th>
                    <th style="padding: 10px !important">Atures</th>
                    <th style="padding: 10px !important">Parroquia</th>
                    <th style="padding: 10px !important">Ubch</th>
                    <th style="padding: 10px !important"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php  echo $datos;  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
   

  <footer class="footer pt-3  ">
    <div class="container-fluid">
      <div class="row align-items-center justify-content-lg-between">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="copyright text-center text-sm text-muted text-lg-start">

            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Gloster III.
          </div>
        </div>

      </div>
    </div>
  </footer>
  </div>
  </div>
  <!-- Settings options -->
  <?php
  require_once('includes/settings.php');
  ?>



  <script type="text/javascript">
    $(document).ready(function() {

      /* Funcion para cargar las parroquias en base al municipio */
      function cargarParroquia() {
        $.get("dependencias/parroquias.php", "municipio_id=" + $("#municipio_id").val(), function(data) {
          $("#continente_id").html(data);
          console.log(data);
        });
      }

      $("#municipio_id").change(cargarParroquia);
      $("#continente_id").dblclick(cargarParroquia);



      /* Funcion para cargar las ubch en base a la parroquia */
      function cargarUbch() {
        $.get("dependencias/ubch.php", "continente_id=" + $("#continente_id").val(), function(data) {
          $("#pais_id").html(data);
          console.log(data);
        });
      }

      $("#continente_id").change(cargarUbch);
      $("#pais_id").dblclick(cargarUbch);




      /* Funcion para cargar las ubch en base a la parroquia */
      function cargarCode() {
          var date = $('#centro').val();
          document.getElementById('codigo').value = date
      }

      $("#centro").change(cargarCode);

    });
  </script>


  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../vendors/sweetAlert2/sweetalert2.all.min.js"></script>


  <!--   Core JS Files   -->
  <script src="../assets/js/jquery.nanoscroller.min.js"></script>
  <script src="../assets/js/menubar/sidebar.js"></script>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
</body>

</html>