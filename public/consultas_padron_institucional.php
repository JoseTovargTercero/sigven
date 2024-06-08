<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['consulta'] != '1') {
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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Consultas: Padron Institucional
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
  <?php include('darkMode.php') ?>
  <script>
    /*Funcion que carga la tabla del fondo de la pagina*/
    $(obtener_registros2());

    function obtener_registros2(rep2) {
      $.ajax({
          url: 'ajaxConsultas/consulta_consulta_padron_institucional_tabla.php',
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

    $(document).on('change', '#entidades', function() {
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
      li.text('Consultas');
      h6.text('Padron Institucional');

      var active = document.getElementById('consultas');
      active.classList.add('active');
    })

    
    
    
    
    /*Funcion que carga la parte dereche de la pantalla*/
    $(obtener_registros22());

    function obtener_registros22(entidad) {
      $.ajax({
          url: 'ajaxConsultas/consulta_consulta_padron_institucional.php',
          type: 'POST',
          dataType: 'html',
          data: {
            entidad: entidad
          },
        })

        .done(function(resultado22) {
          $("#tabla_resultado1").html(resultado22);
        })
    }

    $(document).on('change', '#entidades', function() {
      var valorBusqueda22 = $(this).val();
      if (valorBusqueda22 != "") {
        obtener_registros22(valorBusqueda22);
        obtener_registros2(valorBusqueda2);
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
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">UBCH</h6>
            </div>
            <div class="card-body p-3">


              <p style="color: #c5c5c5; margin-top: -15px;     opacity: 0.5 !important;">Seleccion de Entidad</p>

              <label>Tipo</label>
              <div class="mb-3">
                <select class='form-control' id="tipo" name="tipo" required>
                <option value="">SELECCIONE</option>
                  <option value="1">INSTITUCIÓN</option>
                  <option value="2">PARTIDO POLITICO</option>
                  <option value="3">MOVIMIENTO SOCIAL</option>
                </select>

              </div>

            


              <label>Entidad</label>
              <div class="mb-3">
                <select class='form-control' id="entidades" name="entidades" required>
                <option value="">SELECCIONE</option>
                </select>
              </div>



            </div>
          </div>
        </div>



     
          <section id="tabla_resultado1" class="col-lg-8"> </section>
          
                </div>


    </div>

    <section id="tabla_resultado2"> </section>
          
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
      function cargarInstituciones() {
        $.get("dependencias/entidades.php", "tipo=" + $("#tipo").val(), function(data) {
          $("#entidades").html(data);
          console.log(data);
        });
      }

      $("#tipo").change(cargarInstituciones);
      $("#entidades").dblclick(cargarInstituciones);


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