<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');




if (@$_SESSION['noticia'] != "") {
  $alerta = explode("/", $_SESSION['noticia']);
  unset($_SESSION['noticia']);
} else {

  $alerta[0] = "";
  $alerta[1] = "";
  $alerta[2] = "";
  $alerta[3] = "NO ASIGNADO";
  unset($_SESSION['noticia']);
}




if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}
$id_user = $_SESSION['id'];


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Perfil
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


  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <?php  include('darkMode.php');  ?>

  <script>
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      h6.text('Perfil');
      li.text('Usuario');
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


<?php 
/*
$registrosP = current($conexion->query("SELECT COUNT(*) FROM padronelectoral WHERE registradopor='$id_user'")->fetch_assoc());
$registrosPAT = current($conexion->query("SELECT COUNT(*) FROM estructurapatrulla WHERE registradopor='$id_user'")->fetch_assoc());
$registrosR = current($conexion->query("SELECT COUNT(*) FROM estructuraraas WHERE registradopor='$id_user'")->fetch_assoc());
$registrosU = current($conexion->query("SELECT COUNT(*) FROM estructuraubch WHERE registradopor='$id_user'")->fetch_assoc());

$totalRegistros =  $registrosP + $registrosPAT + $registrosR + $registrosU;
*/
?>


  <?php require_once('includes/menu.php');  ?>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php echo $navbar; ?>
    <!-- End Navbar -->

    <div class="container-fluid">

      <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/3.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-4"></span>
      </div>
      <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">

        <div class="row gx-4">

          <div class="col-auto">

            <div class="avatar bg-gradient-primary me-3 my-auto">

              <?php $iniciales = explode(" ", $_SESSION['nombre']); ?>

              <h1 style="font-size: 2.5rem; color: white !important; font-family: -webkit-pictograph;">

                <?php echo substr($iniciales[0], 0, 1) . substr($iniciales[1], 0, 1); ?>

              </h1>

            </div>
          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $_SESSION['nombre'];  ?>
              </h5>
              <p class="mb-0 font-weight-bold text-sm">
                PSUV Amazonas
              </p>
            </div>
          </div>
          
          
       <!-- 

         <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
           <div class="nav-wrapper position-relative end-0">
             <div style="display: grid; place-content: flex-end;">
               <span class="ms-1"><strong><?php /* echo number_format($totalRegistros, '0', '.', '.') */ ?></strong> registro(s) realizado(s).</span>
              </div>
            </div>
          </div>
          
        -->



        </div>
      </div>
    </div>






    

    <div class="container-fluid py-4">

      <div class="row">
        <div class="col-12 col-xl-8">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Ultimos Cambios</h6>
            </div>
            <div class="card-body p-3">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Fecha</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipo</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Accion</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php


                    $query = "SELECT * FROM actividad_usuarios WHERE id_usuario='$id_user' ORDER BY id DESC LIMIT 7";
                    $search = $conexion->query($query);
                    if ($search->num_rows > 0) {
                      while ($fila = $search->fetch_assoc()) {



                        switch ($fila['accion']) {
                          case ('Dato Agregado'):
                            $hecho = '
                         <span class="badge badge-sm bg-gradient-primary">Agregado</span>';
                            break;
                          case ('Dato Eliminado'):
                            $hecho = ' 
                         <span class="badge badge-sm bg-gradient-info">Eliminado</span>';
                            break;
                          case ('Dato Modificado'):
                            $hecho = ' <span class="badge badge-sm bg-gradient-secondary">Modificado</span> ';
                            break;
                      
                        }


                        echo '
                    <tr>
                      <td>
                      <p class="text-xs text-secondary mb-0">' . $fila['fecha'] . '</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">' . $fila['tipo'] .'</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">' . $fila['accion'] .'</p>
                      </td>
                      <td class="align-middle text-center text-sm">
                       ' . $hecho . '
                      </td>
                    </tr>';
                      }
                    }
                    ?>





                  </tbody>
                </table>




              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-xl-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Seguridad  </h6>


            </div>
            <div class="card-body p-3">
              <ul class="list-group">

                  <form role="form" action="../configurar/contrasena.php" method="post">
                    <p style="color: #c5c5c5; margin-top: -15px;">Cambio de contraseña</p>

                    <label>Contraseña actual</label>
                    <div class="mb-3 field">
                      <input type="password" class="form-control" aria-label="Password" data-validate-length-rangeh="1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30" name="password" required='required'>
                    </div>

                    <label>Nueva contraseña</label>
                    <div class="mb-3 field">
                      <input type="password" name="password11" class="form-control" data-validate-length="6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30" required='required'>
                    </div>
                    <label>Repetir contraseña</label>
                    <div class="mb-3 field">
                      <input type="password" name="password211" class="form-control" data-validate-linked='password11' required='required'>
                    </div>

                    <div class="text-center">
                      <input type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="ACTUALIZAR" />
                    </div>
                  </form>

           
              </ul>



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

<script src="../vendors/validator/validator.js"></script>
<script src="../vendors/validator/multifield.js"></script>

<script src="../assets/js/core/bootstrap.min.js"></script>

<script src="../vendors/sweetAlert2/sweetalert2.all.min.js"></script>
  <!--   Core JS Files   -->
  <script src="../assets/js/jquery.nanoscroller.min.js"></script>
  <script src="../assets/js/menubar/sidebar.js"></script>

  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>


  <script>
    var validator = new FormValidator({
      "events": ['blur', 'input', 'change']
    }, document.forms[0]);

    // on form "submit" event
    document.forms[0].onsubmit = function(e) {
      var submit = true,
        validatorResult = validator.checkAll(this);

      console.log(validatorResult);
      return !!validatorResult.valid;
    };


    // on form "reset" event
    document.forms[0].onreset = function(e) {
      validator.reset();
    };
  </script>


  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>

  <?php
    
    unset($_SESSION['noticia']);
    
  ?>