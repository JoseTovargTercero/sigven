<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}

if ($_SESSION['noticia'] != "") {
  $alerta = explode("/", $_SESSION['noticia']);
  unset($_SESSION['noticia']);
} else {
  $alerta[3] = "NO ASIGNADO";
  unset($_SESSION['noticia']);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
  Permisos de Usuarios
  </title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="../vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="../vendors/animatedLines/css/simple-line-icons.css">
  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
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
      h6.text('Permisos');

      var active = document.getElementById('usuarios');
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

    <?php
$varCount = 0;
$query5555 = "SELECT * FROM `usuarios` WHERE nivel='2' AND activado='0' AND id!='500' ORDER BY nombre";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

    $id = $fila9855['id'];
++$varCount;

$entidad = $fila9855['entidad'];
$reportes = $fila9855['reportes'];
$consulta = $fila9855['consulta'];
$respaldos = $fila9855['respaldos'];

if($entidad == "0"){
  $entidad = 'close blue';
  $value1 = 1;
}else{
  $entidad = 'check red';
  $value1 = 0;
}

if($reportes == "0"){
$reportes = 'close blue';
$value2 = 1;
}else{
  $reportes = 'check red';
  $value2 = 0;
}

if($consulta == "0"){
$consulta = 'close blue';
$value3 = 1;
}else{
  $consulta = 'check red';
  $value3 = 0;
}


if($respaldos == "0"){
$respaldos = 'close blue';
$value4 = 1;
}else{
  $respaldos = 'check red';
  $value4 = 0;
}

$datos .= '
   <tr class="odd gradeX ">
   <td class=tablat>' . $varCount. '</td>
   <td class=tablat>' .$fila9855['cedula']. '</td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['nombre'])) . '</td>
   <td class=center><a href="../configurar/update/permisos.php?tipo=entidad&id='.$id.'&value='.$value1.'"><i class="line icon-'.$entidad.'"><i></a></td>
   <td class=center><a href="../configurar/update/permisos.php?tipo=reportes&id='.$id.'&value='.$value2.'"><i class="line icon-'.$reportes.'"><i></a></td>
   <td class=center><a href="../configurar/update/permisos.php?tipo=consulta&id='.$id.'&value='.$value3.'"><i class="line icon-'.$consulta.'"><i></a></td>
   <td class=center><a href="../configurar/update/permisos.php?tipo=respaldos&id='.$id.'&value='.$value4.'"><i class="line icon-'.$respaldos.'"><i></a></td>
 
   </tr>
 <script>
 $(document).ready(function(){
   $("#unlock_'.$fila9855['id'].'").click(function(){
     $("#unlock_'.$fila9855['id'].'").hide();
     $("#lock_'.$fila9855['id'].'").show();
   })
 })
</script>  
   
   ';
  }
}
?>

   <div class="container-fluid py-4" style="margin-top: -10px;">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Usuarios registrados</h6>
            <p style="color: lightgray;">Control de Permisos</p>
          </div>
          <div class="card-body p-3 row">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0 ">
                <thead>
                  <tr>
                    <th style="padding: 10px !important">#</th>
                    <th style="padding: 10px !important">Cedula</th>
                    <th style="padding: 10px !important">Nombre</th>
                    
                    <th class="center">Entidad</th>
                    <th class="center">Reportes</th>
                    <th class="center">Consultas</th>
                    <th class="center">Respaldos</th>

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