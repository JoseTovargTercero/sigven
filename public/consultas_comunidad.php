<?php
include('../configurar/configuracion.php');
include('includes/navbar.php');


if ($_SESSION['nivel'] != 1 && $_SESSION['consulta'] != '1') {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}

$id = $_GET['id'];

if (!$id) {
  define('PAGINA_INICIO', 'consultas_ubch.php');
  header('Location: ' . PAGINA_INICIO);
}

///////////////////////////// A PARTIR DE AQUI DE EJECUTAN LAS CONSULTAS ///////////////////////////
$varCount = 0;
$query5555 = "SELECT continente.name2, pais.name1, mcp.mcp, pais.id, ciudad.name FROM `ciudad`
LEFT JOIN `pais` ON pais.id=ciudad.pais_id 
LEFT JOIN `continente` ON continente.id=pais.continente_id 
LEFT JOIN `mcp` ON mcp.id=continente.mcp_id 
WHERE ciudad.id='$id'";
$buscarAlumnos5555 = $conexion->query($query5555);
if ($buscarAlumnos5555->num_rows > 0) {
  while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {
    $nombreParroquia = $fila9855['name2'];
    $nombreUbch = $fila9855['name1'];
    $idUbch = $fila9855['id'];
    $nombreMunicipio = $fila9855['mcp'];
    $nombreComunidad = $fila9855['name'];
  }
}

$varCount = 0;

$query555 = "SELECT * FROM `padronelectoral` WHERE comunidad='$id' ORDER BY calle";
$buscarAlumnos55555 = $conexion->query($query555);
if ($buscarAlumnos55555->num_rows > 0) {
  while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {



    ++$varCount;
    if ($fila98555['firma'] == "SI") {
      $resultadofirma = '<td class=tablat violet>' . $varCount . '</td>';
    } else {
      $resultadofirma = '<td class=tablat>' . $varCount . '</td>';
    }

    $datos2 .= '
<tr class="odd gradeX">
' . $resultadofirma . '


<td class=tablat>' . ucwords(mb_strtolower($fila98555['calle'])) . '</td>
<td class=tablat>' . ucwords(mb_strtolower($fila98555['nombre'])) . '</td>
<td class=tablat>' . $fila98555['cedula'] . '</td>
<td class=tablat>' . $fila98555['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($fila98555['cv'])) . '</td>

</tr>
';
  }
}

/////////////////////////////////////// patrullas y jefes de calle ///////////////////////////
/////////////////////////////////////// patrullas y jefes de calle ///////////////////////////
/////////////////////////////////////// patrullas y jefes de calle ///////////////////////////

function patrullas()
{
  global $conexion;
  global $id;

  $datos3 = '';


  $varCount22 = 0;
  $query55555 = "SELECT * FROM `estructuraraas` WHERE tipocargo='1' AND comunidad='$id' ORDER BY cargo";
  $buscarAlumnos55555 = $conexion->query($query55555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {

      ++$varCount22;


      if (strlen($fila98555['cv']) > 38) {
        $cv = substr($fila98555['cv'], 0, 38) . '...';
      } else {
        $cv = $fila98555['cv'];
      }


      $name = explode(" ", $fila98555['nombre']);

      if (count($name) >= 3) {
        $nameCorto2 = $name[2] . " " . $name[0];
      } else {
        $nameCorto2 = $fila98555['nombre'];
      }
      $idCalle = $fila98555['cargo'];


      $datos3 .= '

    <td></td>
    <td>
<div class="d-flex px-2 py-1">

  <div class="d-flex flex-column justify-content-center">
    <h6 class="mb-0 text-sm"><abbr style="text-decoration: none" title="' . ucwords(mb_strtolower($fila98555['nombre'])) . '">' . ucwords(mb_strtolower($nameCorto2)) . '</abbr></h6>
    <p class="text-xs text-secondary mb-0">Lider de Calle</p>
  </div>
</div>
</td>
<td class=tablat>' . ucwords(mb_strtolower($idCalle)) . '</td>
<td class=tablat>' . $fila98555['cedula'] . '</td>
<td class=tablat>' . $fila98555['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($cv)) . '</td>
</tr>';
      $varCount2 = 0;
      $query5555 = "SELECT * FROM `estructurapatrulla` WHERE comunidad='$id' AND calle='$idCalle' ORDER BY calle";
      $buscarAlumnos5555 = $conexion->query($query5555);
      if ($buscarAlumnos5555->num_rows > 0) {
        while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

          ++$varCount2;
          if ($fila9855['firma'] == "SI") {
            $resultadofirma = '<td class=tablat violet>' . $varCount2 . '></td>';
          } else {
            $resultadofirma = '<td class=tablat>' . $varCount2 . '</td>';
          }

          if (strlen($fila9855['cargo']) > 38) {
            $cargo = substr($fila9855['cargo'], 0, 38) . '...';
          } else {
            $cargo = $fila9855['cargo'];
          }

          if (strlen($fila9855['cv']) > 38) {
            $cv = substr($fila9855['cv'], 0, 38) . '...';
          } else {
            $cv = $fila9855['cv'];
          }

          $name = explode(" ", $fila9855['nombre']);

          if (count($name) >= 3) {
            $nameCorto = $name[2] . " " . $name[0];
          } else {
            $nameCorto = $fila9855['nombre'];
          }

          $datos3 .= '
<tr class="odd gradeX ">
' . $resultadofirma . '

<td>
<div class="d-flex px-2 py-1">

<div class="d-flex flex-column justify-content-center">
<h6 class="mb-0 text-sm"><abbr style="text-decoration: none" title="' . ucwords(mb_strtolower($fila9855['nombre'])) . '">' . ucwords(mb_strtolower($nameCorto)) . '</abbr></h6>
    <p class="text-xs text-secondary mb-0">' . ucwords(mb_strtolower($cargo)) . '</p>
    </div>
    </div>
</td>
<td class=tablat>' . ucwords(mb_strtolower($fila9855['calle'])) . '</td>
<td class=tablat>' . $fila9855['cedula'] . '</td>
<td class=tablat>' . $fila9855['telefono'] . '</td>
<td class=tablat>' . ucwords(mb_strtolower($cv)) . '</td>
</tr>';
        }
      }
      $datos3 .= '
    <tr class="odd gradeX ">
    <td class="divisor"</td>
    <td class="divisor"</td>
    <td class="divisor"</td>
    <td class="divisor"</td>
    <td class="divisor"</td>
    <td class="divisor"</td>
    </tr>';
    }
  }

  return $datos3;
}

///////////////////////////////////// CONTADORES ///////////////////////////////////////////////
$opositores = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE comunidad='$id' AND voto='OP'");
$cantidadOp = mysqli_num_rows($opositores);
$blandos = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE comunidad='$id' AND voto='VB'");
$cantidadBd = mysqli_num_rows($blandos);
$duros = mysqli_query($conexion, "SELECT * FROM padronelectoral WHERE comunidad='$id' AND voto='VD'");
$cantidadDu = mysqli_num_rows($duros);
$totalVotos = $cantidadOp + $cantidadBd + $cantidadDu;




$query55555 = "SELECT * FROM inf_mcp WHERE pq='mp'";
$buscarAlumnos55555 = $conexion->query($query55555);
if ($buscarAlumnos55555->num_rows > 0) {
  while ($filavalor = $buscarAlumnos55555->fetch_assoc()) {

    $cantidadestrubch = $filavalor['estr_ubch'];
    $cantidadestrraas = $filavalor['estr_raas'];
    $cantidadestrpatrulla = $filavalor['estr_patrulla'];
  }
}





///////////////////////////////////// ESTADO DE LAS ESTRUCTURAS ///////////////////////////////////////////////

$count1 = mysqli_query($conexion, "SELECT * FROM estructuraraas WHERE comunidad='$id' AND tipocargo='1'");
$contarJefesCalle = mysqli_num_rows($count1);

$totalPatrulla = $contarJefesCalle * $cantidadestrpatrulla;
//total de patrullas que deberia haber
$totalUbch = $cantidadestrubch;
//total de ubch que deberia haber
$totalRaas = $cantidadestrraas;
//total de raas que deberia haber

$divisorOptimo = $totalPatrulla + $totalUbch + $totalRaas;


$count2 = mysqli_query($conexion, "SELECT * FROM estructuraraas WHERE comunidad='$id' AND tipocargo='0'");
$cantidadRaas = mysqli_num_rows($count2);
//cantidad raas cargada
$count3 = mysqli_query($conexion, "SELECT * FROM estructuraubch WHERE ubch='$idUbch'");
$cantidadUbch = mysqli_num_rows($count3);
//cantidad ubch cargada
$count4 = mysqli_query($conexion, "SELECT * FROM estructurapatrulla WHERE comunidad='$id' ");
$patrullasCargadas = mysqli_num_rows($count4);
$cantidadPatrulla  = $patrullasCargadas + $contarJefesCalle;
//cantidad patrulla cargada

$cargadoOptimo = $cantidadRaas + $cantidadUbch + $cantidadPatrulla;
//cantidad total de las estructuras cargadas 
$porcentaje = $cargadoOptimo * 100 / $divisorOptimo;
//porcentaje total de las estructuras cargadas 

$porcentaRaasRepresenta = $totalRaas * 100 / $divisorOptimo;
//porcentaje total de las estructuras  raas
$porcentaUbchRepresenta = $totalUbch * 100 / $divisorOptimo;
//porcentaje total de la estructura ubch 
$porcentaPatrullaRepresenta = $totalPatrulla * 100 / $divisorOptimo;
//porcentaje total de las estructuras patrullas

$porcentajeRaasCritico = $cantidadRaas * 100 / $totalRaas;
//porcentaje total de las estructuras en relacion a si misma
$porcentajeUbchCritico = $cantidadUbch * 100 / $totalUbch;
//porcentaje total de la estructura  en relacion a si misma
$porcentajePatrullaCritico = $cantidadPatrulla * 100 / $totalPatrulla;
//porcentaje total de las estructuras en relacion a si misma




if($porcentaje < 40){$color = "color: #63B3ED;";}elseif ($porcentaje < 75) {$color = "color: #fd7e14;";}else{$color = "color: #ea0606;";}
if($porcentajeUbchCritico < 40){$colorUbch = "color: #63B3ED;";}elseif ($porcentajeUbchCritico < 75) {$colorUbch = "color: #fd7e14;";}else{$colorUbch = "color: #ea0606;";}
if($porcentajeRaasCritico < 40){$colorRaas = "color: #63B3ED;";}elseif ($porcentajeRaasCritico < 75) {$colorRaas = "color: #fd7e14;";}else{$colorRaas = "color: #ea0606;";}
if($porcentajePatrullaCritico < 40){$colorPatrulla  = "color: #63B3ED;";}elseif ($porcentajePatrullaCritico < 75) {$colorPatrulla = "color: #fd7e14;";}else{$colorPatrulla  = "color: #ea0606;";}


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Consultas: Comunidad
  </title>
  <link rel="stylesheet" href="../vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="../vendors/animatedLines/css/simple-line-icons.css">
  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../vendors/validator/fv.css" type="text/css" />
  <link href="../assets/css/sidebar.css" rel="stylesheet" />
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script type="text/javascript" src="jquery.min.js"></script>
  <?php include('darkMode.php') ?>
  <script>
    /*Funcion que activa el icono del menu y cambia el nombre de la pagina*/
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Consultas');
      h6.text('Comunidad');

      var active = document.getElementById('consultas');
      active.classList.add('active');
    })


    $(document).ready(function() {
      $("#padronButtom").click(function() {

        $("#padron").show(400, "linear");
        $("#patrulla").hide(400, "linear");

        var text = $('#text');
        text.text('Padron Electoral');

      })
    })

    $(document).ready(function() {
      $("#patrullaButtom").click(function() {

        $("#padron").hide(400, "linear");
        $("#patrulla").show(400, "linear");

        var text = $('#text');
        text.text('Patrulla Territorial');

      })
    })

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


    <div class="container-fluid">

      <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/2-cut.jpg'); background-position-y: 50%;">
        <span class="mask bg-gradient-primary opacity-4"></span>
      </div>
      <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">

        <div class="row gx-4">

          <div class="col-auto">

          </div>
          <div class="col-auto my-auto">
            <div class="h-100">
              <h5 class="mb-1">
                <?php echo $nombreComunidad ?>
                <p class="mb-0  text-sm">
                  <?php echo $nombreUbch ?>
                </p>
            </div>
          </div>




          <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
            <div class="nav-wrapper position-relative end-0">
              <div style="display: grid; place-content: flex-end;">
                <span class="ms-1">
                  <a href="">
                    Ver en GITCOM
                  </a>
                </span>
              </div>
            </div>
          </div>




        </div>
      </div>
    </div>


    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-lg-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Estado de las estructuras</h6>
            </div>
            <div class="card-body p-3">
              <table style="width: 100%;">
                <tbody>
                  <tr>
                    <td>Estructura completa:</td>
                    <td><?php echo $cargadoOptimo; ?> / <?php echo $divisorOptimo; ?></td>
                    <td style="<?php echo $color ?>"><?php echo round($porcentaje, 0, PHP_ROUND_HALF_DOWN); ?>%</td>
                  </tr>
                  <tr>
                    <td>Ubch:</td>
                    <td><?php echo $cantidadUbch; ?> / <?php echo $totalUbch; ?></td>
                    <td style="<?php echo $colorUbch ?>"><?php echo round($porcentajeUbchCritico, 0, PHP_ROUND_HALF_DOWN); ?>%</td>
                  </tr>
                  <tr>
                    <td>Raas:</td>
                    <td><?php echo $cantidadRaas; ?> / <?php echo $totalRaas; ?></td>
                    <td style="<?php echo $colorRaas ?>"><?php echo round($porcentajeRaasCritico, 0, PHP_ROUND_HALF_DOWN); ?>%</td>
                  </tr>
                  <tr>
                    <td>Patrullas:</td>
                    <td><?php echo $cantidadPatrulla; ?> / <?php echo $totalPatrulla; ?></td>
                    <td style="<?php echo $colorPatrulla ?>"><?php echo round($porcentajePatrullaCritico, 0, PHP_ROUND_HALF_DOWN); ?>%</td>
                  </tr>


                </tbody>
              </table>

            </div>
          </div>
        </div>

      


        <div class="col-md-2">
          <div class="card" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-people"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Total</h6>
              <span class="text-xs">Total de Electores</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $totalVotos ?></h5>
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-user-following"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Duros</h6>
              <span class="text-xs">Votos Duros</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadDu ?></h5>
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-user-follow"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Blandos</h6>
              <span class="text-xs">Votos Blandos</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadBd ?></h5>
            </div>
          </div>
        </div>

        <div class="col-md-2">
          <div class="card" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                <i class="line icon-user-unfollow"></i>
              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <h6 class="text-center mb-0">Opositores</h6>
              <span class="text-xs">Votos Opositores</span>
              <hr class="horizontal dark my-3">
              <h5 class="mb-0"><?php echo $cantidadOp ?></h5>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid py-4">
      <div class="row">

        <div class="col-lg-6 ho">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Estructura Raas</h6>
            </div>
            <div class="card-body p-3" style="height: 620px; overflow: scroll;">

              <?php

              $query5555 = "SELECT * FROM `estructuraraas` WHERE comunidad=$id ORDER BY id_propio";
              $buscarAlumnos5555 = $conexion->query($query5555);
              if ($buscarAlumnos5555->num_rows > 0) {
                while ($fila9855 = $buscarAlumnos5555->fetch_assoc()) {

                  switch ($fila9855['cargo']) {
                    case ("RESP.  DE LA COMISION DE MISIONES Y GRANDES MISIONES SOCIALISTAS"):
                      $cargo = "MISIONES Y GRANDES MISIONES SOCIALISTAS";
                      break;
                    case ("RESP. DE LA COMISION DE AGITACION PROPAGANDA Y COMUNICACION"):
                      $cargo = "AGITACION PROPAGANDA Y COMUNICACION";
                      break;
                    case ("RESP. DE LA COMISION DE LA JUVENTUD"):
                      $cargo = "COMISION DE LA JUVENTUD";
                      break;
                    case ("RESP. DE LA COMISION DE MUJERES"):
                      $cargo = "COMISION DE MUJERES";
                      break;
                    case ("RESP. ECONOMIA PRODUCTIVA"):
                      $cargo = "ECONOMIA PRODUCTIVA";
                      break;
                    default:
                      $cargo = $fila9855['cargo'];
                      break;
                  }

                  $name = explode(" ", $fila9855['nombre']);

                  if (count($name) >= 3) {
                    $nameCorto = $name[2] . " " . $name[0];
                  } else {
                    $nameCorto = $fila9855['nombre'];
                  }
                  if (strlen($fila9855['cv']) > 25) {
                    $cv = substr($fila9855['cv'], 0, 25) . '...';
                  } else {
                    $cv = $fila9855['cv'];
                  }






                  $datos .= '
            <tr>
          
            <td>
            <div class="d-flex px-2 py-1">
           
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm"><abbr style="text-decoration: none" title="' . ucwords(mb_strtolower($fila9855['nombre'])) . '">' . ucwords(mb_strtolower($nameCorto)) . '</abbr></h6>
                <p class="text-xs text-secondary mb-0">' . ucwords(mb_strtolower($cargo)) . '</p>
              </div>
            </div>
          </td>



            <td>' . $fila9855['cedula'] . '</td>
            <td><abbr style="text-decoration: none" title="' . $fila9855['cv'] . '">' . ucwords(mb_strtolower($cv))  . '</abbr></td>
            <td>' . $fila9855['telefono'] . '</td>
            </tr>';
                }
              }

              ?>

              <table class="table">
                <thead>
                  <tr>

                    <th style="padding: 10px !important">Nombre</th>
                    <th style="padding: 10px !important">Cedula</th>
                    <th style="padding: 10px !important">Centro de Votaci&oacute;n</th>
                    <th style="padding: 10px !important">Telefono</th>

                  </tr>
                </thead>
                <tbody>

                  <?php

                  echo $datos;

                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="col-lg-12" style="height: 550px;">
            <div class="card h-100">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Mapa del voto</h6>
              </div>
              <div class="card-body p-3">

                <div style="height: 50vh;" id="chartdiv"></div>
              </div>
            </div>
          </div>

          <div class="col-lg-12" style="margin-top: 15px;">
            <div class="card h-100">
              <div class="card-body p-3">

                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th style="padding: 10px !important"></th>
                      <th style="padding: 10px !important;  text-align: center">Total</th>
                      <th style="padding: 10px !important;  text-align: center">Duros</th>
                      <th style="padding: 10px !important;  text-align: center">Blandos</th>
                      <th style="padding: 10px !important;  text-align: center">Opositores</th>
                    </tr>


                    <tr>
                      <td style="font-size: 13px;">SIGVEN</td>
                      <td style="font-size: 13px; text-align: center"><?php echo $totalVotos ?></td=>
                      <td style="font-size: 13px; text-align: center"><?php echo $cantidadDu ?></td>
                      <td style="font-size: 13px; text-align: center"><?php echo $cantidadBd ?></td>
                      <td style="font-size: 13px; text-align: center"><?php echo $cantidadOp ?></td>
                    </tr>
                    <tr>
                      <td style="font-size: 13px;">GITCOM</td>
                      <td style="font-size: 13px; text-align: center"><?php echo 0 ?></td=>
                      <td style="font-size: 13px; text-align: center"><?php echo 0 ?></td>
                      <td style="font-size: 13px; text-align: center"><?php echo 0 ?></td>
                      <td style="font-size: 13px; text-align: center"><?php echo 0 ?></td>
                    </tr>


                  </thead>
                  <tbody>
                  </tbody>
                </table>

              
              </div>
            </div>

          </div>
        </div>
      </div>


      <div class="container-fluid py-4">
        <div class="row">

          <div class="col-lg-12 ho">
            <div class="card h-100">
              <div class="card-header pb-0 p-3">
                <h6 id="text" style="position: absolute; margin-top: 10px;">Padron Electoral</h6>

                <div class="col-lg-4 " style="float: right;">
                  <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                      <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab" id="padronButtom">
                          <span class="ms-1">Padron Electoral</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a style="cursor: pointer;" class="nav-link mb-0 px-0 py-1 " data-bs-toggle="tab" id="patrullaButtom">
                          <span class="ms-1">Patrulla Territorial</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>


              </div>

              <div class="card-body p-3" id="padron" style="margin-top: 15px; height: 600px; overflow-y: scroll">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>

                        <th style="padding: 10px !important">#</th>
                        <th style="padding: 10px !important">Calle</th>
                        <th style="padding: 10px !important">Nombre</th>
                        <th style="padding: 10px !important">Cedula</th>
                        <th style="padding: 10px !important">Telefono</th>
                        <th style="padding: 10px !important">Centro de Votación</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php echo  $datos2; ?>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="card-body p-3" id="patrulla" style="margin-top: 15px; height: 600px; overflow-y: scroll; display: none;">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0 ">
                    <thead>
                      <tr>

                        <th style="padding: 10px !important">#</th>
                        <th style="padding: 10px !important">Nombre</th>
                        <th style="padding: 10px !important">Calle</th>
                        <th style="padding: 10px !important">Cedula</th>
                        <th style="padding: 10px !important">Telefono</th>
                        <th style="padding: 10px !important">Centro de Votación</th>

                      </tr>
                    </thead>
                    <tbody>
                      <?php echo patrullas(); ?>
                    </tbody>
                  </table>
                </div>
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

    });
  </script>






  <script src="../vendors/amcharts5/index.js"></script>
  <script src="../vendors/amcharts5/percent.js"></script>
  <script src="../vendors/amcharts5/themes/Animated.js"></script>
  <script src="../vendors/amcharts5/themes/Material.js"></script>


  <script src="../vendors/amcharts5/use/pie-donut-chart/index.js"></script>
  <script>
    // Set data
    // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
    series.data.setAll([{
        value: <?php echo $cantidadDu ?>,
        category: "VD"
      },
      {
        value: <?php echo $cantidadBd ?>,
        category: "VB"
      },
      {
        value: <?php echo $cantidadOp ?>,
        category: "VO"
      },
    ]);


    // Create legend
    // https://www.amcharts.com/docs/v5/charts/percent-charts/legend-percent-series/
    var legend = chart.children.push(am5.Legend.new(root, {
      centerX: am5.percent(50),
      x: am5.percent(50),
      marginTop: 15,
      marginBottom: 15,
    }));

    legend.data.setAll(series.dataItems);
  </script>






  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../vendors/sweetAlert2/sweetalert2.all.min.js"></script>


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