<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
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
      if (largo > 10) {
        $('.' + clase).prop('size', ancho);
      }

    });
  </script>



</head>




<body class="g-sidenav-show bg-gray-100">

  <?php require_once('includes/menu.php');  ?>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php echo $navbar; ?>
    <!-- End Navbar -->


    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, inventore error facilis consequatur animi harum minima porro doloremque cumque libero aperiam fuga nam in est ducimus accusantium dolor rem sit.


    <table class="table">
      <thead>
        <tr>
          <th>Mcp</th>
          <th>Carnetizados</th>
        </tr>
      </thead>

      <?php


      function contarUbchs($municipios)
      {

        global $conexion;
        $cantidad = 0;

        $query5555 = "SELECT * FROM `continente` WHERE mcp_id='$municipios'";
        $buscarAlumnos5555 = $conexion->query($query5555);
        if ($buscarAlumnos5555->num_rows > 0) {
          while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {
            $idParroquia = $fila9855['id'];
            $duros = mysqli_query($conexion, "SELECT * FROM pais WHERE continente_id='$idParroquia'");
            $cantidadDu = mysqli_num_rows($duros);
            $cantidad += $cantidadDu;
          }
        }

        return $cantidad;
      }













      function contarVotos($municipios, $var)
      {

        global $conexion;
        $cantidad = 0;

        $query5555 = "SELECT * FROM `continente` WHERE mcp_id='$municipios'";
        $buscarAlumnos5555 = $conexion->query($query5555);
        if ($buscarAlumnos5555->num_rows > 0) {
          while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {
            $idParroquia = $fila9855['id'];

            $query55556 = "SELECT * FROM `pais` WHERE continente_id='$idParroquia'";
            $buscarAlumnos55556 = $conexion->query($query55556);
            if ($buscarAlumnos55556->num_rows > 0) {
              while ($fila98556 = $buscarAlumnos55556->fetch_assoc()) {
                $idcv = $fila98556['id'];

         
           


                $query555567 = "SELECT * FROM `padronelectoral` WHERE ubch='$idcv'";
                $buscarAlumnos555567 = $conexion->query($query555567);
                if ($buscarAlumnos555567->num_rows > 0) {
                  while ($fila985567 = $buscarAlumnos555567->fetch_assoc()) {
                    $cedula = $fila985567['cedula'];
               
                    $duros = mysqli_query($conexion, "SELECT * FROM psuv WHERE cedulaCarnet='$cedula'");
                    $cantidadDu = mysqli_num_rows($duros);
                    if ($cantidadDu > 0) {
                      $cantidad += 1;
                    }

                  }
                  }
              }
            }
          }
        }

        return $cantidad;
      }





      $query5555 = "SELECT * FROM `mcp`";
      $buscarAlumnos5555 = $conexion->query($query5555);
      if ($buscarAlumnos5555->num_rows > 0) {
        while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

          $id = $fila9855['id'];

          echo '<tr>';
          echo '<td>' . $fila9855['mcp'] . '</td>';
          echo '<td>' . contarVotos($id, 'null') . '</td>';
          echo '</tr>';
        }
      }






      ?>


      <tbody>








      </tbody>
    </table>
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