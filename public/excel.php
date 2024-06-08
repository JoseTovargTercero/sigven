<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}


$query = $conexion->query("select * from mcp");
$countries = array();
while ($r = $query->fetch_object()) {
  $countries[] = $r;
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
  
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
  Registros: RAAS
  </title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">


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
  


  <script>

$(document).on('keyup', 'input[type="text"]', function() {
  
  var largo = $(this).prop('value').length;
  var clase = $(this).attr("class");
  var ancho = largo / 1.2;
  if(largo > 10){
    $('.' + clase).prop('size', ancho);
 }

});

</script>



</head>



<style>
  .fila{
   height: 86vh;
    overflow: auto;
    padding: 20px 20px 60px 20px;
    width: 95%;
  }
  .contentField{
    display: flex;
  }
  input[type="text"]{
    border-top: 1px solid  #d2d6da;
    border-bottom: 1px solid  #d2d6da;
    border-right: 1px solid  #d2d6da;
    border-left: none;
    padding: 5px;
    line-height: 1.4rem;
    color: #6e6e6e;
    appearance: none;
    transition: box-shadow 1s ease, border-color 1s ease;
  }
  input[type="text"]:focus {
    outline: 0;
  }
  .izqBarra{
    width: 45px;
    border-left: 1px solid lightgray !important;
    text-align: center;
  }
  .topBarra{
    text-align: center;
  }






</style>



<body class="g-sidenav-show bg-gray-100">

  <?php require_once('includes/menu.php');  ?>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php echo $navbar; ?>
    <!-- End Navbar -->


    <div class="fila">

    <?php
        $a = 0;
        $b = "A";
        while ($a <= 50) { // imprime los campos hacia abajo

          for ($i=1; $i <= 50; $i++) {  // imprime los campos hacia la derecha
              
            if($i == 1){  echo " <div class='contentField'>";  }


            if($a == 0){
             
              if($i == 1){
                echo '<input type="text" readonly class="izqBarra" placeholder="+">';
              }else{
                echo '<input type="text" readonly class="f-'.$i.' topBarra" placeholder="'.$b++.'">';
              }
            }else{

              if($i == 1){
                echo '<input type="text" readonly class="izqBarra" placeholder="'.$a.'">';
              }else{
                echo '<input type="text" class="f-'.$i.'">';
              }
            
            }
            if($i == 50){  echo " </div>";  }
          }

          $a++;
        }
        
      ?>

      
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

      function cargarComunidad() {
        $.get("dependencias/comunidades.php", "pais_id=" + $("#pais_id").val(), function(data) {
          $("#ciudad_id").html(data);
          console.log(data);
        });
      }
      $("#pais_id").change(cargarComunidad);
      $("#ciudad_id").dblclick(cargarComunidad);

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