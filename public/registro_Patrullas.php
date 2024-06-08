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

if ($_SESSION['noticia'] != "") {
  $alerta = explode("/", $_SESSION['noticia']);
  unset($_SESSION['noticia']);
} else {
  $alerta[3] = "NO ASIGNADO";
}


if ($_SESSION['comunidad_patr'] != "") {

  $municipio = $_SESSION['municipio_patr'];
  $parroquia = $_SESSION['parroquia_patr'];
  $centrodev = $_SESSION['ubch_patr'];
  $comunidad = $_SESSION['comunidad_patr'];
  $calle = $_SESSION['calle_patr'];

  $query = "SELECT ciudad.name, pais.name1, continente.name2, mcp.mcp FROM `ciudad`
   LEFT JOIN pais ON pais.id=ciudad.pais_id 
   LEFT JOIN continente ON continente.id=pais.continente_id
   LEFT JOIN mcp ON mcp.id=continente.mcp_id 
   WHERE ciudad.id='$comunidad'";

  $buscarAlumnos = $conexion->query($query);
  if ($buscarAlumnos->num_rows > 0) {
    while ($filaAlumnos = $buscarAlumnos->fetch_assoc()) {
      $name3 = $filaAlumnos['mcp'];
      $name2 = $filaAlumnos['name2'];
      $name1 = $filaAlumnos['name1'];
      $name = $filaAlumnos['name'];
    }
  }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Registros: Patrullas Territoriales
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
  <?php  include('darkMode.php');  ?>
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

    function obtener_registros2(callee, comunidadd) {
      $.ajax({
          url: 'ajaxConsultas/consulta_registro_patrullas_tabla.php',
          type: 'POST',
          dataType: 'html',
          data: {
            callee: callee,
            comunidadd: comunidadd
          },
        })

        .done(function(resultado2) {
          $("#tabla_resultado2").html(resultado2);
        })
    }

    $(document).on('change', '#calle', function() {
      var comunidad = $("#ciudad_id").val();
      var calle = $(this).val();

      if (calle != "" && comunidad != "") {
        obtener_registros2(calle, comunidad);
      } else {
        obtener_registros2();
      }

    });




    /*Funcion que carga el listado de jefes de calle*/
    $(obtener_registros3());

    function obtener_registros3(rep3) {
      $.ajax({
          url: 'ajaxConsultas/consulta_registro_patrullas_jefesCalle.php',
          type: 'POST',
          dataType: 'html',
          data: {
            rep3: rep3
          },
        })

        .done(function(resultado3) {
          $("#tabla_resultado3").html(resultado3);
        })
    }
    $(document).on('dblclick', '#calle', function() {
      var valorBusqueda3 = $("#ciudad_id").val();
      if (valorBusqueda3 != "") {
        obtener_registros3(valorBusqueda3);
      } else {
        obtener_registros3();
      }
    });


    $(document).on('change', '#ciudad_id', function() {
      var valorBusqueda3 = $(this).val();
      if (valorBusqueda3 != "") {
        obtener_registros3(valorBusqueda3);
      } else {
        obtener_registros3();
      }
    });

 


    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Registros');
      h6.text('Estructura de las Patrullas');

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
      <form class="row" role="form" action="../configurar/create/patrullas.php" method="POST">
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Comunidad</h6>
            </div>
            <div class="card-body p-3">
   



              <p style="color: #c5c5c5; margin-top: -15px;     opacity: 0.5 !important;">Seleccion de comunidad</p>

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


              <label>Comuniad</label>
              <div class="mb-3">
                <select class='form-control' id="ciudad_id" name="ciudad_id" required>

                  <?php
                  if ($name != "") {
                    echo '<option value="' . $comunidad . '">' . $name . '</option>';
                  } else {
                    echo '<option value="">SELECCIONE</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
          </div>
        </div>



        <div class="col-lg-8">
          <div class="card h-100  cblur shadow-blu ">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Información de la Persona</h6>
            </div>
            <div class="card-body p-3 row">

              <p style="color: #c5c5c5; margin-top: -15px;  opacity: 0.5 !important;">Integrante de la Patrulla Territorial</p>



              <div class="col-lg-6">

                <label>Cedula</label>
                <div class="col-lg-12">
                  <input style="margin-bottom: 16px;"  type="number" name="busqueda" id="busqueda" class="form-control" required placeholder="Cedula de Identidad">
                </div>

                <section id="tabla_resultado"> </section>
              </div>
              
              
              <div class="col-lg-6">
                <section id="tabla_resultado3"> </section>

                <div style="margin-top: 16px;"  class="col-lg-12">
                  <label>Voto</label>
                  <select class='form-control' id="voto" name="voto" required='required'>
                    <option></option>
                    <option value="VD">DURO</option>
                    <option value="VB">BLANDO</option>
                    <option value="OP">OPOSITOR</option>
                  </select>
                </div>

                

                
                
                

                
                <div style="margin-top: 16px;" class="col-lg-12">
                  <label>Telefono</label>
                  <input name="telefono" id="telefono"  type="number" pattern="[0-9]{11}"   class="form-control" required='required' placeholder="telefono">
                </div>

                <div style="margin-top: 16px;" class="col-lg-12">
                  <label>Responsabilidad</label>
                  <select class='form-control' id="cargo" name="cargo" required='required'>
                    <option></option>
															<option value="RESP.  DE ALIMENTACION Y PRODUCCION">RESP.  DE ALIMENTACION Y PRODUCCION</option>
															<option value="RESP. DE SALUD">RESP. DE SALUD</option>
															<option value="RESP. DE MISIONES Y GRANDES MISIONES SOCIALISTAS">RESP. DE MISIONES Y GRANDES MISIONES SOCIALISTAS</option>
															<option value="RESP. DE LA JUVENTUD">RESP. DE LA JUVENTUD</option>
															<option value="RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION">RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION</option>
															<option value="RESP. DE MUJERES">RESP. DE MUJERES</option>
															<option value="RESP. DE DEFENSA INTEGRAL">RESP. DE DEFENSA INTEGRAL</option>
                  </select>

                </div>
                
                
                
                <div style="margin-top: 22px;" class="text-center">

                  <input type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="registrar" />
                </div>

                
              </div>
              
            </div>
          </div>


          
      </form>
      

      
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



      /* Funcion para cargar las ubch en base a la parroquia */

      function cargarComunidad() {
        $.get("dependencias/comunidades.php", "pais_id=" + $("#pais_id").val(), function(data) {
          $("#ciudad_id").html(data);
          console.log(data);
   
        });
      }
      $("#pais_id").change(cargarComunidad);
      $("#ciudad_id").dblclick(cargarComunidad);
  



      function resetTable() {
        $("#tabla_resultado2").html("");
      }
      $("#ciudad_id").change(resetTable);

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