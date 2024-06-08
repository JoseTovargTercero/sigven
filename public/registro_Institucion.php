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
  <meta name="theme-color" content="#e53545">
  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Registros: Institucion
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
          url: 'ajaxConsultas/consulta_registro_institucion.php',
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








    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Registros');
      h6.text('Instituciones y Movimientos Sociales');

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




<script>
    	// Loading
			var Loading = (loadingDelayHidden = 0) => {
				let loading = null;
				const myLoadingDelayHidden = loadingDelayHidden;
				let imgs = [];
				let lenImgs = 0;
				let counterImgsLoading = 0;

				function incrementCounterImgs() {
					counterImgsLoading += 1;
					if (counterImgsLoading === lenImgs) {
						hideLoading()
					}
				}

				function hideLoading() {
					if (loading !== null) {
						loading.classList.remove('show');
						setTimeout(function() {
							loading.remove()
						}, myLoadingDelayHidden)
					}
				}

				function init() {
					document.addEventListener('DOMContentLoaded', function() {
						loading = document.querySelector('.loading');
						imgs = Array.from(document.images);
						lenImgs = imgs.length;
						if (imgs.length === 2) {
							hideLoading()
						} else {
							imgs.forEach(function(img) {
								img.addEventListener('load', incrementCounterImgs, false)
							})
						}


					})

				}
				return {
					'init': init
				}
			}

			Loading(1000).init();

  </script>
</head>
<body class="g-sidenav-show bg-gray-100">
<div class="loader loading show">
        <div class="cssload-thecube">
          <div class="cssload-cube cssload-c1"></div>
          <div class="cssload-cube cssload-c2"></div>
          <div class="cssload-cube cssload-c4"></div>
          <div class="cssload-cube cssload-c3"></div>
          <img src="../assets/img/logo_extended_white.png" alt="Img" style="width: 84px;z-index: 1;transform: rotate(-45deg);    margin-top: -76px;margin-left: -18px;">
        </div>
    </div>


  <?php require_once('includes/menu.php');  ?>
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
    <!-- Navbar -->
    <?php echo $navbar; ?>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <form class="row" role="form" action="../configurar/create/institucion.php" method="POST">
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Entidad</h6>
            </div>
            <div class="card-body p-3">
              <p style="color: #c5c5c5; margin-top: -15px;     opacity: 0.5 !important;">Datos de la entidad</p>



              <div class="col-lg-12">
                <label>Tipo</label>
                <select class='form-control' id="tipo" name="tipo" required='required'>
                  <option></option>
                  <option value="1">INSTITUCIÓN</option>
                  <option value="2">PARTIDO POLITICO</option>
                  <option value="3">MOVIMIENTO SOCIAL</option>
                </select>
              </div>


              <div class="col-lg-12"  style=" margin-top: 16px;">
                <label>Nombre</label>
                  <div class="col-lg-12">
                    <input name="nombreInsti" id="nombreInsti" class="form-control" required>
                  </div>
              </div>

              <div class="col-lg-12"  style=" margin-top: 16px;">
                <label>Dirección/referencia</label>
                  <div class="col-lg-12">
                    <input name="direccionReferencia" id="direccionReferencia" class="form-control" required>
                  </div>
              </div>




            </div>
          </div>
        </div>


        <div class="col-lg-8">
          <div class="card h-100  cblur shadow-blu ">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Información del Director</h6>
            </div>
            <div class="card-body p-3 row">

              <p style="color: #c5c5c5; margin-top: -15px;  opacity: 0.5 !important;">Datos del director e información de contacto</p>








              <div class="col-lg-6">

                <label>Cedula</label>
                <div class="col-lg-12">
                  <input  type="number" pattern="[0-9]{11}"   style="margin-bottom: 16px;" name="busqueda" id="busqueda" class="form-control" required placeholder="Cedula de Identidad">
                </div>

                <section id="tabla_resultado"> </section>
              </div>


              <div class="col-lg-6">





                <div class="col-lg-12">
                  <label>Telefono</label>
                  <input type="tel" pattern="[0-9]{11}" name="telefono" id="telefono" class="form-control" required='required' placeholder="telefono">
                </div>

                <div class="col-lg-12" style="margin-top: 16px;">
                  <label>Correo</label>
                  <input type="email" name="correo" id="correo" class="form-control" required='required' placeholder="Correo Eléctronico">
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



  <?php
$varCount = 0;
$query5555 = "SELECT * FROM `instituciones` ORDER BY nombre";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

    echo '
<div  id="taf'.$fila9855['id'].'" style="display: none;" class="sss hhh container-fluid py-4">
    <div class="row">
      <div class="col-lg-12">
        <div class="card h-100  cblur shadow-blu" style="box-shadow: 0 -20px 27px 0 rgb(0 0 0 / 5%);">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">Editando datos</h6>
          </div>
          <div class="card-body p-3 ">
            <form action="../configurar/update/institucion.php" method="post" class="row"> 
            

          <input style="margin-bottom: 16px;" readonly name="id" id="id" class="form-control" required hidden value="'.$fila9855['id'].'">
                <div class="col-lg-3">
                  <label>Nombre</label>
                  <input style="margin-bottom: 16px;" name="nombre" id="nombre" class="form-control" required  value="'.$fila9855['nombre'].'">
                </div>

                
                <input hidden name="tipo" required value="'.$fila9855['tipo'].'">
                
              <div class="col-lg-3">
                <label>Telefono</label>
                <input style="margin-bottom: 16px;" type="tel" pattern="[0-9]{11}" name="telefono" id="telefono" class="form-control" required placeholder="Telefono"  value="'.$fila9855['telefono'].'">
              </div>
                
              <div class="col-lg-3">
                <label>Correo</label>
                <input style="margin-bottom: 16px;" type="email"  name="correo" id="correo" class="form-control" required placeholder="Correo Eléctronico"  value="'.$fila9855['correo'].'">
              </div>


              
            <div class="col-lg-3">
            <label>Dirección</label>
            <input style="margin-bottom: 16px;"  name="direccion" id="direccion" class="form-control" required placeholder="Direccion"  value="'.$fila9855['direccion'].'">
          </div>


                <div class="col-lg-3">
                <label>Cedula</label>
                <input style="margin-bottom: 16px;" name="cedula" id="cedula" class="form-control" required  value="'.$fila9855['CedulaDirector'].'">
                </div>

                <div class="col-lg-3">
                <label>Director</label>
                <input style="margin-bottom: 16px;"  name="nombreDirector" id="nombreDirector" class="form-control" required  value="'.$fila9855['nombreDirector'].'">
                </div>                



                <div class="col-lg-3">
                <label> &nbsp; </label>
                <input style="margin-bottom: 16px;"  type="submit" class="btn bg-gradient-primary w-100" value="ACTUALIZAR" />
                </div>
                </form>
          </div>
        </div>
      </div>
    </div>
    </div>

    <script>
    $(document).ready(function(){
      $("#edit'.$fila9855['id'].'").click(function(){
        $(".sss").hide();
        $("#taf'.$fila9855['id'].'").toggle(700, "swing");
      })
    })
  </script>
  
';

++$varCount;

$tipo = $fila9855['tipo'];
switch($tipo){
  case(1):
  $tipoInt = "Institución";
    break;
    
  case(2):
  $tipoInt = "Partido Político";
    break;
  case(3):
  $tipoInt = "Movimiento Social";
    break;

}

if( strlen($fila9855['nombre']) > 38){
  $nombreInstitucion = substr($fila9855['nombre'],0, 38).'...';
}else{
  $nombreInstitucion = $fila9855['nombre'];
}

$idInt = $fila9855['id'];

$electores = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE id_int='$idInt'");
$cantidad = mysqli_num_rows($electores);


if($_SESSION['nivel'] == 1 && $cantidad == 0){

   $delete = '<td class="icono">
   <a class="contenidoLock" id="unlock_' . $fila9855['id'] . '"><i class="line icon-lock"></i></a>
  <a style="display: none" id="lock_' . $fila9855['id'] . '" href="../configurar/delete/institucion.php?
  id=' . $fila9855['id'] . '&nombre=' . $fila9855['nombre'] . '&tipo=' . $fila9855['tipo'] . '"><i class="line icon-trash"></i></a>
  </td>
';

}else{

  $delete = '';

}




$datos .= '
   <tr class="odd gradeX ">
   <td class=tablat>' . $varCount. '</td>
   <td class=tablat>' . $tipoInt. '</td>
   <td class=tablat><abbr title="'.ucwords(mb_strtolower( $fila9855['nombre'])).'" style="text-decoration: none;">' . ucwords(mb_strtolower($nombreInstitucion)) . '</abbr></td>
   <td class=tablat>' . ucwords(mb_strtolower($fila9855['nombreDirector'])) . '</td>
   <td class=tablat>' . $fila9855['telefono'] . '</td>
   <td class=tablat>' . $cantidad . '</td>

   <td class="icono ron"><a  href="#taf'.$fila9855['id'].'" id="edit'.$fila9855['id'].'"><i class="line icon-pencil"></i></a></td>
   '.$delete.'
   
   
   



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
            <h6 class="mb-0">Instituciones y Movimientos Sociales</h6>
          </div>
          <div class="card-body p-3 row">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0 ">
                <thead>
                  <tr>
                    <th style="padding: 10px !important">#</th>
                    <th style="padding: 10px !important">Tipo</th>
                    <th style="padding: 10px !important">Nombre</th>
                    <th style="padding: 10px !important">Director</th>
                    <th style="padding: 10px !important">Telefono</th>
                    <th style="padding: 10px !important">(E)</th>
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