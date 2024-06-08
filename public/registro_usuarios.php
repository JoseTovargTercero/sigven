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
  <title>Registros: Usuarios</title>
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
      h6.text('Usuarios');

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
    <div class="container-fluid py-4">
      <form class="row" role="form" action="../configurar/create/usuarios.php" method="POST">
        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Usuario</h6>
            </div>
            <div class="card-body p-3">
            

              <p style="color: #c5c5c5; margin-top: -15px; opacity: 0.5 !important;">Información del Usuario</p>

              <label>Cedula</label>
                <div class="mb-3">
                  <input style="margin-bottom: 16px;"  type="number"  name="cedula" id="cedula" class="form-control" required placeholder="Cedula de Identidad">
                </div>
             
                <label>Nombre</label>
                <div class="mb-3">
                  <input style="margin-bottom: 16px;"  type="text"  name="nombre" id="nombre" class="form-control" required placeholder="Cedula de Identidad">
                </div>
          
                <label>Nivel de acceso</label>
                <div class="mb-3">
                  <select class='form-control' id="nivel" name="nivel" required>
                    <option></option>
                    <option value="2">ESTANDAR</option>
                    <option value="1">ADMISTRADOR</option>
                  </select>
                </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Contraseña</h6>
            </div>
            <div class="card-body p-3">
            



              <p style="color: #c5c5c5; margin-top: -15px;  opacity: 0.5 !important;">Contraseña del usuario</p>


              <label>Contraseña</label>
                <div class="mb-3">
                  <input style="margin-bottom: 16px;" type="password"  name="pass" id="pass" class="form-control" required placeholder="Contraseña">
                </div>

              <label>Repetir Contraseña</label>
                <div class="mb-3">
                  <input style="margin-bottom: 16px;" type="password"  name="pass2" id="pass2" class="form-control" required placeholder="Repetir Contraseña">
                </div>
                
                <div style="margin-top: 22px;" class="text-center">
                  <input type="submit" disabled id="boton" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="registrar" />
                </div>
              </div>
            </div>
          </div>
      </form>
    </div>

    <?php
$varCount = 0;
$query5555 = "SELECT * FROM `usuarios` WHERE activado='0' AND id!='500' ORDER BY nombre";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {


++$varCount;


if($fila9855['nivel'] == "1"){
  $nivel = "Administrador";
}else{
  $nivel = "Estandar";
}



if($fila9855['nivel'] == "2"){

  $delete = '
  <a class="contenidoLock" id="unlock_' . $fila9855['id'] . '"><i class="line icon-lock"></i></a>
 <a style="display: none" id="lock_' . $fila9855['id'] . '" href="../configurar/delete/usuarios.php?
 id=' . $fila9855['id'] . '&nombre=' . $fila9855['nombre'] . '&cedula=' . $fila9855['cedula'] . '"><i class="line icon-trash"></i></a>

';

}else{

 $delete = '';

}


$datos .= '
   <tr class="odd gradeX ">
   <td class=tablat>' . $varCount. '</td>
   <td class=tablat>' . $nivel . '</td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['nombre'])) . '</td>
   <td class=tablat>' .$fila9855['cedula']. '</td>

   
   <td class="icono"> <a href="perfil.php?id=' . $fila9855['id'] . '"><i class="line icon-user"></i></a></td>
   <td class="icono">'. $delete.' </td>

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

   <div class="container-fluid py-4" >
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%); height: 700px !important; overflow-y: scroll;">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Usuarios registrados</h6>
          </div>
          <div class="card-body p-3 row">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0 ">
                <thead>
                  <tr>
                    <th style="padding: 10px !important">#</th>
                    <th style="padding: 10px !important">Nivel</th>
                    <th style="padding: 10px !important">Nombre</th>
                    <th style="padding: 10px !important">Cedula</th>
                    <th style="padding: 10px !important"></th>
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
  <?php require_once('includes/settings.php');  ?>

<script type="text/javascript">
    $(document).ready(function() {
      /* Funcion para cargar las parroquias en base al municipio */
      function alertaNoticia2(title, text, tipo) {
        Swal.fire({
          type: tipo,
          title: title,
          text: text,
          timer: 4000, //el tiempo que dura el mensaje en ms
        
        });
      };

      function comparar() {
        var pass = $("#pass").val();
        var passLongitud = $("#pass").val().length;
        var pass2 = $("#pass2").val();

      if(passLongitud > 6){
        if (pass != "" && pass2 != "") {
          if (pass != pass2) {
            alertaNoticia2("No coinciden", "Las contraseñas indicadas no coinciden.", "info");
            $("#boton").prop('disabled', true);

          }else{
            $("#boton").prop('disabled', false);
          }
        }
          
        }else{
          alertaNoticia2("Longitud no valida", "La contraseña debe tener al menos 7 caracteres.", "info");
            $("#boton").prop('disabled', true);
        }
    }
     
      $("#pass").blur(comparar);
      $("#pass2").blur(comparar);

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