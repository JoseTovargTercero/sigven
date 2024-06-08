<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
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
  <?php include('darkMode.php') ?>
  <script>
    /*Funcion que hace la cunsulta al rep*/
    $(obtener_registros());

    function obtener_registros(rep) {
      $.ajax({
          url: 'ajaxConsultas/consulta_registro_raas.php',
          type: 'POST',
          dataType: 'html',
          data: {
            rep: rep
          },
        })

        .done(function(resultado) {
          $("#tabla_resultado").html(resultado);
        })
    }

    $(document).on('blur', '#busqueda', function() {
      var valorBusqueda = $(this).val();
      if (valorBusqueda != "") {
        obtener_registros(valorBusqueda);
      } else {
        obtener_registros();
      }
    });




    /*Funcion que carga la tabla del fondo de la pagina*/
    $(obtener_registros2());

    function obtener_registros2(rep2) {
      $.ajax({
          url: 'ajaxConsultas/consulta_registro_raas_tabla.php',
          type: 'POST',
          dataType: 'html',
          data: {
            rep2: rep2
          },
        })

        .done(function(resultado2) {
          $("#tabla_resultado2").html(resultado2);
        })
    }

    $(document).on('change', '#ciudad_id', function() {
      var valorBusqueda2 = $(this).val();
      if (valorBusqueda2 != "") {
        obtener_registros2(valorBusqueda2);
      } else {
        obtener_registros2();
      }
    });






    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Registros');
      h6.text('Estructura RAAS');

      var active = document.getElementById('registros');
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
      <form class="row" role="form" action="../configurar/create/raas.php" method="POST">
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Comunidad</h6>
            </div>
            <div class="card-body p-3">
   



            

Lorem ipsum dolor sit, amet consectetur adipisicing elit. Rem impedit, optio soluta ullam non pariatur modi autem voluptate? Error corrupti sunt asperiores, nulla reprehenderit deleniti accusamus aliquid? Consectetur, voluptas voluptatibus!
            </div>
          </div>
        </div>

        <div class="col-lg-8">
          <div class="card h-100  cblur shadow-blu ">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Uno por Diez</h6>
            </div>
            <div class="card-body p-3 row">

              <p style="color: #c5c5c5; margin-top: -15px;  opacity: 0.5 !important;">Archivo csv.</p>



  <div class="col-lg-12">
    



    <div class="custom-input-file col-md-6 col-sm-6 col-xs-6">
<input type="file" id="fichero-tarifas" class="input-file" value="">
<i class="line icon-paper-clip"></i> &nbsp;Subir fichero...</div>
  </div>

<style>
  .custom-input-file {
    background-color: #f7f7f7;
    overflow: hidden;
    padding: 6px;
    position: relative;
    text-align: center;
    width: 100%;
    border-radius: 7px;
}

.custom-input-file .input-file {
 border: 10000px solid transparent;
 cursor: pointer;
 font-size: 10000px;
 margin: 0;
 opacity: 0;
 outline: 0 none;
 padding: 0;
 position: absolute;
 right: -1000px;
 top: -1000px;
}
</style>
                

             
                
                
                <div style="margin-top: 22px;" class="text-center">

                  <input type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="Subir" />
                </div>

                
              </div>
              
            </div>
          </div>


          
      </form>
      

      
    </div>
    
  </div>


  <!-- aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral 
       aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral -->
  <section id="tabla_resultado2"> </section>
  <!-- aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral 
       aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral -->
  
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