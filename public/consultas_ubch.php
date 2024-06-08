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
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Consultas: UBCH
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
          url: 'ajaxConsultas/consulta_consulta_ubch_tabla.php',
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

    $(document).on('change', '#pais_id', function() {
      var valorBusqueda2 = $(this).val();
      if (valorBusqueda2 != "") {
        obtener_registros2(valorBusqueda2);
      } else {
        obtener_registros2();
      }
    });


    /*Funcion que carga la parte dereche de la pantalla*/
    $(obtener_registros22());

    function obtener_registros22(ubch) {
      $.ajax({
          url: 'ajaxConsultas/consulta_consulta_ubch.php',
          type: 'POST',
          dataType: 'html',
          data: {
            ubch: ubch
          },
        })

        .done(function(resultado22) {
          $("#tabla_resultado1").html(resultado22);
        })
    }

    $(document).on('change', '#pais_id', function() {
      var valorBusqueda22 = $(this).val();
      if (valorBusqueda22 != "") {
        obtener_registros22(valorBusqueda22);
        obtener_registros2(valorBusqueda2);
      } 
    });






    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Consultas');
      h6.text('UBCH');

      var active = document.getElementById('consultas');
      active.classList.add('active');
    })





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


              <p style="color: #c5c5c5; margin-top: -15px;     opacity: 0.5 !important;">Seleccion de UBCH</p>

              <label>Municipio</label>
              <div class="mb-3">
                <select class='form-control' id="municipio_id" name="municipio_id" required>
                  <?php
                  if ($name3 != "") {
                    echo '<option value="' . $municipio . '">' . $name3 . '</option>';
                  } else {
                    echo '<option value="">SELECCIONE</option>';
                  }
                  ?>


                  <?php foreach ($countries as $c) : ?>
                    <option value="<?php echo $c->id; ?>"><?php echo $c->mcp; ?></option>
                  <?php endforeach; ?>
                </select>

              </div>

              <label>Parroquia</label>
              <div class="mb-3">
                <select class='form-control' id="continente_id" name="continente_id" required>
                  <?php
                  if ($name2 != "") {
                    echo '<option value="' . $parroquia . '">' . $name2 . '</option>';
                  } else {
                    echo '<option value="">SELECCIONE</option>';
                  }
                  ?>

                </select>
              </div>


              <label>Ubch</label>
              <div class="mb-3">
                <select class='form-control' id="pais_id" name="pais_id" required>
                  <?php
                  if ($name1 != "") {
                    echo '<option value="' . $centrodev . '">' . $name1 . '</option>';
                  } else {
                    echo '<option value="">SELECCIONE</option>';
                  }
                  ?>
                </select>
              </div>

            </div>
          </div>
        </div>



     
          <section id="tabla_resultado1" class="col-lg-8"> </section>
          
                </div>


    </div>

    <!-- aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral -->
    <section id="tabla_resultado2"> </section>
    <!-- aqui se desplega la tabla con la info de los integrantes de la estructura o padron electoral -->

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