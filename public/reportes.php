<?php

include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['reportes'] != '1') {
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
  unset($_SESSION['noticia']);
}
$tipoint = array(
  array('id' => '1', 'nombre' => 'INSTITUCION'),
  array('id' => '2', 'nombre' => 'PARTIDO POLITICO'),
  array('id' => '3', 'nombre' => 'MOVIMIENTO SOCIAL')
);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
   Generar Reportes
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
  <?php include('darkMode.php');  ?>
  <script>
    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Reportes');
      h6.text('Generar Reportes');

      var active = document.getElementById('reportes');
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
<div class="row">

        <div class="col-lg-4">
          <div class="card h-100" style=" background-color: transparent; box-shadow: none;">
            <div class="p-3 menuReporte">
              <ul style="list-style: none !important">

                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back" href="reportes/balance.php">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Balance</span>
                  </a>
                </li> <!-- Enlace directo sin JQUERY -->


                <hr class="horizontal dark mt-0" style="margin-top: 10px !important;margin-bottom: 5px;">

                <li style="padding: 5px 0 5px 0;" id="comisionesPatrullas" data-valor="comisionesPatrullas">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Comisiones Patrullas</span>
                  </a>
                </li>

                <li style="padding: 5px 0 5px 0;" id="comisionesRaas" data-valor="comisionesRaas">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Comisiones Raas</span>
                  </a>
                </li>

                <li style="padding: 5px 0 5px 0;" id="comisionesUbch" data-valor="comisionesUbch">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Comisiones UBCH</span>
                  </a>
                </li>

                <hr class="horizontal dark mt-0" style="margin-top: 10px !important;margin-bottom: 5px;">

                <li style="padding: 5px 0 5px 0;" id="ubchLocal" data-valor="ubchLocal">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes">Estructuras de UBCH</span>
                  </a>
                </li>
                <!-- -------------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------------- -->
                <!-- -------------------------------------------------------------------- -->
                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back" href="reportes/edo_ubch.php">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Estructuras de UBCH</span>
                  </a>
                </li><!-- Enlace directo sin JQUERY -->

                <li style="padding: 5px 0 5px 0;" id="raas" data-valor="raas">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Estructuras RAAS</span>
                  </a>
                </li>
                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back" href="reportes/edo_raas.php">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Estructuras RAAS</span>
                  </a>
                </li><!-- Enlace directo sin JQUERY -->

                <li style="padding: 5px 0 5px 0;" id="patrullas" data-valor="patrullas">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Estructuras Territoriales</span>
                  </a>
                </li>
                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back" href="reportes/edo_patrullas.php">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Estructuras Territoriales </span>
                  </a>
                </li><!-- Enlace directo sin JQUERY -->

                <hr class="horizontal dark mt-0" style="margin-top: 10px !important;margin-bottom: 5px;">

                <li style="padding: 5px 0 5px 0;" id="electores_rep" data-valor="electores_rep">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Electores en el REP</span>
                  </a>
                </li>
                <hr class="horizontal dark mt-0" style="margin-top: 10px !important;margin-bottom: 5px;">
                <li style="padding: 5px 0 5px 0;" id="padron_electoral" data-valor="padron_electoral" class="activo">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Padron Electoral</span>
                  </a>
                </li>

                <li style="padding: 5px 0 5px 0;" id="padron_x_ubch" data-valor="padron_x_ubch">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Padron Electoral (ubch)</span>
                  </a>
                </li>
              <!-- 
                
                <li style="padding: 5px 0 5px 0;" id="padron_x_pq" data-valor="padron_x_pq">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Padron Electoral (pq)</span>
                  </a>
                </li>
              -->

         
                <li style="padding: 5px 0 5px 0;" id="padron_Int" data-valor="padron_Int">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-pin"></i>
                    </div>
                    <span class="spanReportes"> Padron Institucional</span>
                  </a>
                </li>

                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Padron Institucional</span>
                  </a>
                </li><!-- Enlace directo sin JQUERY -->
                <li style="padding: 5px 0 5px 0;">
                  <a class="aReportesMenu back">
                    <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center backRombo">
                      <i class="line icon-globe-alt"></i>
                    </div>
                    <span class="spanReportes"> Padron Institucional Dir.</span>
                  </a>
                </li><!-- Enlace directo sin JQUERY -->
              </ul>
            </div>
          </div>
        </div>

        <!-- 

        comisionesUbch
        comisionesRaas
        comisionesUbch
        ubchLocal
        raas
        patrullas
        electores_rep
        padron_electoral
        padron_x_ubch
        padron_Int

        -->

        <script>
          $(document).ready(function() {
            function actualizarLink() {
              // Cambiar el titulo //
              var valor = $(this).text()
              var texto = $('#textoTitulo');
              texto.text(valor);
              // A;adir la clase activo //
              $('li').removeClass('activo');
              $(this).addClass('activo');
              //Recojo el valor del data-valor con el cual voy a mostra u ocultar ciertos campos // 
              var dataValor = $(this).data("valor")

              if (dataValor == 'ubchLocal' || dataValor == 'padron_x_ubch' || dataValor == 'electores_rep') {

                  $("#municipioCampo").show(500, "swing");
                  $("#parroquiaCampo").show(500, "swing");
                  $("#ubchCampo").show(500, "swing");
                  $("#comunidadCampo").hide(500, "swing");
                  $("#comisionesPatrullasCampo").hide(500, "swing");
                  $("#comisionesUbchCampo").hide(500, "swing");
                  $("#comisionesRaasCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").hide(500, "swing");
                  $("#EntidadCampo").hide(500, "swing");

              } else if (dataValor == 'padron_electoral' || dataValor == 'raas' || dataValor == 'patrullas') {

                  $("#municipioCampo").show(500, "swing");
                  $("#parroquiaCampo").show(500, "swing");
                  $("#ubchCampo").show(500, "swing");
                  $("#comunidadCampo").show(500, "swing");
                  $("#comisionesPatrullasCampo").hide(500, "swing");
                  $("#comisionesUbchCampo").hide(500, "swing");
                  $("#comisionesRaasCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").hide(500, "swing");
                  $("#EntidadCampo").hide(500, "swing");
                
              } else if (dataValor == 'padron_Int') {
                  $("#municipioCampo").hide(500, "swing");
                  $("#parroquiaCampo").hide(500, "swing");
                  $("#ubchCampo").hide(500, "swing");
                  $("#comunidadCampo").hide(500, "swing");
                  $("#comisionesPatrullasCampo").hide(500, "swing");
                  $("#comisionesUbchCampo").hide(500, "swing");
                  $("#comisionesRaasCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").show(500, "swing");
                  $("#EntidadCampo").show(500, "swing");

              } else if (dataValor == 'comisionesPatrullas') {

                  $("#municipioCampo").show(500, "swing");
                  $("#parroquiaCampo").hide(500, "swing");
                  $("#ubchCampo").hide(500, "swing");
                  $("#comunidadCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").hide(500, "swing");
                  $("#EntidadCampo").hide(500, "swing");
                  $("#comisionesPatrullasCampo").show(500, "swing");
                  $("#comisionesUbchCampo").hide(500, "swing");
                  $("#comisionesRaasCampo").hide(500, "swing");

              }else if (dataValor == 'comisionesUbch') {

                  $("#municipioCampo").show(500, "swing");
                  $("#parroquiaCampo").hide(500, "swing");
                  $("#ubchCampo").hide(500, "swing");
                  $("#comunidadCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").hide(500, "swing");
                  $("#EntidadCampo").hide(500, "swing");
                  $("#comisionesPatrullasCampo").hide(500, "swing");
                  $("#comisionesUbchCampo").show(500, "swing");
                  $("#comisionesRaasCampo").hide(500, "swing");

              }else if (dataValor == 'comisionesRaas') {

                  $("#municipioCampo").show(500, "swing");
                  $("#parroquiaCampo").hide(500, "swing");
                  $("#ubchCampo").hide(500, "swing");
                  $("#comunidadCampo").hide(500, "swing");
                  $("#TipoEntidadCampo").hide(500, "swing");
                  $("#EntidadCampo").hide(500, "swing");
                  $("#comisionesPatrullasCampo").hide(500, "swing");
                  $("#comisionesUbchCampo").hide(500, "swing");
                  $("#comisionesRaasCampo").show(500, "swing");

              }else if (dataValor == 'padron_x_pq') {

              $("#municipioCampo").show(500, "swing");
              $("#parroquiaCampo").show(500, "swing");
              $("#ubchCampo").hide(500, "swing");
              $("#comunidadCampo").hide(500, "swing");
              $("#TipoEntidadCampo").hide(500, "swing");
              $("#EntidadCampo").hide(500, "swing");
              $("#comisionesPatrullasCampo").hide(500, "swing");
              $("#comisionesUbchCampo").hide(500, "swing");
              $("#comisionesRaasCampo").hide(500, "swing");

              }

              $("#formulario").attr("action", 'reportes/' + dataValor + '.php');


            }
            $("#ubchLocal").click(actualizarLink); // hecho
            $("#raas").click(actualizarLink); // hecho
            $("#patrullas").click(actualizarLink); // hecho
            $("#electores_rep").click(actualizarLink); // hecho
            $("#padron_electoral").click(actualizarLink); // hecho
            $("#padron_x_ubch").click(actualizarLink); // hecho
            $("#padron_x_pq").click(actualizarLink); // hecho
            $("#padron_Int").click(actualizarLink); // hecho

            $("#comisionesUbch").click(actualizarLink);
            $("#comisionesRaas").click(actualizarLink);
            $("#comisionesPatrullas").click(actualizarLink);
          });
        </script>




        <div class="col-lg-8">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0" id="textoTitulo">Padron Electoral</h6>
            </div>
            <div class="card-body p-3">

            <form  role="form" action="reportes/padron_electoral.php" id="formulario" method="POST">

              <div class="mb-3" id="municipioCampo">
                <label>Municipio</label>
                <select class='form-control' id="municipio_id" name="municipio_id" >
                <option value="">SELECCIONE</option>
                  <?php foreach ($countries as $c) : ?>
                    <option value="<?php echo $c->id; ?>"><?php echo $c->mcp; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3" id="parroquiaCampo">
                <label>Parroquia</label>
                <select class='form-control' id="continente_id" name="continente_id" >
                  <option value="">SELECCIONE</option>
                </select>
              </div>

              <div class="mb-3" id="ubchCampo">
                <label>Ubch</label>
                <select class='form-control' id="pais_id" name="pais_id" >
                  <option value="">SELECCIONE</option>
                </select>
              </div>

              <div class="mb-3" id="comunidadCampo">
                <label>Comuniad</label>
                <select class='form-control' id="ciudad_id" name="ciudad_id">
                  <option value="">SELECCIONE</option>
                </select>
              </div>




              <!-- campo que se muestra al hacer click en comisiones patrullas -->
              <div class="mb-3" id="comisionesPatrullasCampo" style="display: none;">
                <label>Comision Patrulla Territorial</label>
                <select class='form-control' id="comisionPatrullasInput" name="comisionPatrullasInput">
                  <option value="">SELECCIONE</option>
                  <option value="RESP.  DE ALIMENTACION Y PRODUCCION">RESP. DE ALIMENTACION Y PRODUCCION</option>
                  <option value="RESP. DE SALUD">RESP. DE SALUD</option>
                  <option value="RESP. DE MISIONES Y GRANDES MISIONES SOCIALISTAS">RESP. DE MISIONES Y GRANDES MISIONES SOCIALISTAS</option>
                  <option value="RESP. DE LA JUVENTUD">RESP. DE LA JUVENTUD</option>
                  <option value="RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION">RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION</option>
                  <option value="RESP. DE MUJERES">RESP. DE MUJERES</option>
                  <option value="RESP. DE DEFENSA INTEGRAL">RESP. DE DEFENSA INTEGRAL</option>

                </select>
              </div>
              <!-- campo que se muestra al hacer click en comisiones ubch -->
              <div class="mb-3" id="comisionesUbchCampo" style="display: none;">
                <label>Comision UBCH</label>
                <select class='form-control' id="comisionesUbchInput" name="comisionesUbchInput">
                  <option value="">SELECCIONE</option>
                  <option value="JEFE DE UBCH">JEFE DE UBCH</option>
                  <option value="FORMACION IDEOLOGICA">FORMACION IDEOLOGICA</option>
                  <option value="AGITACION PROPAGANDA Y COMUNICACION">AGITACION PROPAGANDA Y COMUNICACION</option>
                  <option value="TECNICA ELECTORAL">TECNICA ELECTORAL</option>
                  <option value="ECONOMIA PRODUCTIVA">ECONOMIA PRODUCTIVA</option>
                  <option value="MUJERES">MUJERES</option>
                  <option value="JUVENTUD">JUVENTUD</option>
                  <option value="DEFENSA INTEGRAL">DEFENSA INTEGRAL</option>
                  <option value="COMUNAS Y MOVIMIENTOS SOCIALES">COMUNAS Y MOVIMIENTOS SOCIALES</option>
                  <option value="CLASE OBRERA">CLASE OBRERA</option>
                  <option value="MISIONES Y GRANDES MISIONES">MISIONES Y GRANDES MISIONES</option>
                  <option value="ORGANIZACION Y MOVILIZACION">ORGANIZACION Y MOVILIZACION</option>

                </select>
              </div>
              <!-- campo que se muestra al hacer click en comisiones raas -->
              <div class="mb-3" id="comisionesRaasCampo" style="display: none;">
                <label>Comision RAAS</label>
                <select class='form-control' id="comisionesRaasInput" name="comisionesRaasInput">
                  <option value="">SELECCIONE</option>

                  <optgroup label="ESTRUCTURA RAAS">
                    <option value="JEFE DE COMUNIDAD">JEFE (A) DE COMUNIDAD</option>
                    <option value="RESP. PRINCIPAL DEL CONSEJO COMUNAL">RESP. PRINCIPAL DEL CONSEJO COMUNAL</option>
                    <option value="RESP.  DE LA COMISION DE MISIONES Y GRANDES MISIONES SOCIALISTAS">RESP. DE LA COMISION DE MISIONES Y GRANDES MISIONES SOCIALISTAS</option>
                    <option value="RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION">RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION</option>
                    <option value="RESP. DE LA COMISION DE LA JUVENTUD">RESP. DE LA COMISION DE LA JUVENTUD</option>
                    <option value="RESP. DE LA COMISION DE MUJERES">RESP. DE LA COMISION DE MUJERES</option>
                    <option value="RESP. ECONOMIA PRODUCTIVA">RESP. ECONOMIA PRODUCTIVA</option>
                    <option value="COMANDANTE DE LA UPDI">COMANDANTE DE LA UPDI</option>
                  </optgroup>
                  <optgroup label="ESTADO MAYOR DEL CLAP">
                    <option value="DEFENSA INTEGRAL">DEFENSA INTEGRAL</option>
                    <option value="VOCERO DEL CONSEJO COMUNAL">VOCERO DEL CONSEJO COMUNAL</option>
                    <option value="VOCERO DE UNAMUJER">VOCERO DE UNAMUJER</option>
                    <option value="VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD">VOCERO DEL FRENTE FRANCISCO DE MIRANDA O JUVENTUD</option>
                    <option value="VOCERO DE UBCH">VOCERO DE UBCH</option>
                  </optgroup>
                </select>
              </div>


              <!-- campo que se muestra al hacer click en padron Institucional -->
              <div class="mb-3" id="TipoEntidadCampo" style="display: none;">
                <label>Tipo</label>
                <select class='form-control' id="tipo" name="tipo">
                  <option value="">SELECCIONE</option>

                  <?php foreach ($tipoint as $c2) : ?>
                    <option value="<?php echo $c2['id']; ?>"><?php echo $c2['nombre']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>



              <div class="mb-3" id="EntidadCampo" style="display: none;">
                <label>Entidad</label>
                <select class='form-control' id="tipoEntidad" name="tipoEntidad">
                  <option value="">SELECCIONE</option>
                </select>
              </div>



              <div style="margin-top: 22px; float:right" class="col-lg-4">
                <input type="submit" class="btn bg-gradient-primary w-100 mt-4 mb-0" value="Generar Reporte" />
              </div>



            </form>

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
  <?php require_once('includes/settings.php'); ?>


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




      /* Funcion para cargar las instituciones */
      function cargarInstituciones() {
        $.get("dependencias/instituciones.php", "tipo=" + $("#tipo").val(), function(data) {
          $("#tipoEntidad").html(data);
          console.log(data);

        });
      }
      $("#tipo").change(cargarInstituciones);


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