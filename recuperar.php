
<?php 
session_start();
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
unset($_SESSION);
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>Recuperar Acceso</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="vendors/animatedLines/css/simple-line-icons.css">

  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script src="vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <script>
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

<body class="g-sidenav-show  bg-gray-100">
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 w-100 shadow-none my-3  navbar-transparent mt-4">
    <div class="container">
      <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 text-white" href="pages/dashboards/default.html">
        Psuv Amazonas
      </a>
   
      <div class="collapse navbar-collapse w-100 pt-3 pb-2 py-lg-0" id="navigation">
        <ul class="navbar-nav navbar-nav-hover mx-auto">
          
          
          <li class="nav-item dropdown dropdown-hover mx-2">
            <a role="button" class="nav-link ps-2 d-flex justify-content-between cursor-pointer align-items-center " id="dropdownMenuDocs" data-bs-toggle="dropdown" aria-expanded="false">
              PSUV - AMAZONAS
            </a>
            
          </li>
        </ul>
        <ul class="navbar-nav d-lg-block d-none">
          <li class="nav-item">
            <a href="#" class="btn btn-sm  bg-gradient-primary  btn-round mb-0 me-1" onclick="smoothToPricing('pricing-soft-ui')">IR A GITCOM</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <section class=" mb-8">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('assets/img/3.jpg');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">Recuperar acceso!</h1>
            <p class="text-lead text-white">Use el siguiente formulario para recuperar el acceso a su cuenta.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h5>Contrase&ntilde;a vencida</h5>
            </div>
            <div class="row " style="display: flex; place-content: center;">
              
            <div class="col-3">
                <a class="btn btn-outline-light w-100" href="#">
                  <img src="assets/img/favicon.png" alt="" height="30px" style="margin-left: -5px;">
                </a>
              </div>
              
              <div class="col-3">
                <a class="btn btn-outline-light w-100" href="javascript:;">
                 <i style="font-size: 27px; color: #bfbfbf;" class="line icon-lock"></i>
                </a>
              </div>
             
            
            </div>
            <div class="card-body">
              <form action="configurar/recuperar.php" method="post">
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="C&eacute;dula" name="cedula">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Ultima Contrase&ntilde;a" name="oldPass">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Nueva Contrase&ntilde;a" name="newPass">
                </div>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Repetir Contrase&ntilde;a" name="repeatPass">
                </div>
             
                <div class="text-center">
                  <input type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2" value="Actualizar"></input>
                </div>
                <br>
                Recuerde actualizar su contrase&ntilde;a  <strong class="text-primary font-weight-bolder">periodicamente</strong>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        
     
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
        <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Gloster III. 
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="vendors/sweetAlert2/sweetalert2.all.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>