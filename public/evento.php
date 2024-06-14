<?php
include('../configurar/configuracion.php');
include('../elecciones/configuracion/conexion.php'); // CONEXION A LA BASE DE DATOS DE LAS ELECCIONES

include('includes/navbar.php');


if ($_SESSION['nivel'] != 1) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
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
    Evento
  </title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link rel="stylesheet" href="../vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="../vendors/animatedLines/css/simple-line-icons.css">
  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../vendors/validator/fv.css" type="text/css" />
  <link href="../assets/css/sidebar.css" rel="stylesheet" />
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script type="text/javascript" src="jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
  <style>
    .text-center {
      text-align: center !important;
    }
  </style>

  <?php include('darkMode.php') ?>
  <script>
    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Inicio');
      h6.text('Administración');

      var active = document.getElementById('inicio');
      active.classList.add('active');
    })



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
    <?php

    ////////////////////////////// Contar el total de jefes de ubch registrados//////////////////////////

    function FunctionName()
    {
      global $base;
      $atures = 0;
      $altoOrinoco = 0;
      $autana = 0;
      $manapiare = 0;
      $maroa = 0;
      $rNegro = 0;
      $atabapo = 0;


      try {
        $sql = "SELECT id FROM estructuraubch WHERE cargo ='JEFE DE UBCH' AND municipio = ?";

        $resultado = $base->prepare($sql);



        $resultado->execute(array('1'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $atures += 1;
        }

        $resultado->execute(array('2'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $altoOrinoco += 1;
        }

        $resultado->execute(array('3'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $autana += 1;
        }

        $resultado->execute(array('4'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $manapiare += 1;
        }
        $resultado->execute(array('5'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $maroa += 1;
        }

        $resultado->execute(array('6'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $rNegro += 1;
        }

        $resultado->execute(array('7'));
        while ($resultado->fetch(PDO::FETCH_ASSOC)) {
          $atabapo += 1;
        }




        $resultado->closeCursor();
      } catch (Exception $e) {
        // die('Error: ' . $e->GetMessage());
      }


      return $atures . '/' .
        $altoOrinoco . '/' .
        $autana . '/' .
        $manapiare . '/' .
        $maroa . '/' .
        $rNegro . '/' .
        $atabapo;
    }

    $resultMcp = explode('/', FunctionName());




    $edadPsuv_20 = 0;
    $edadPsuv_30 = 0;
    $edadPsuv_40 = 0;
    $edadPsuv_50 = 0;
    $edadPsuv_60 = 0;
    $edadPsuv_70 = 0;
    $edadPsuv_80 = 0;
    $edadPsuv_90 = 0;
    $edadPsuv_100 = 0;
    $edadOp_20 = 0;
    $edadOp_30 = 0;
    $edadOp_40 = 0;
    $edadOp_50 = 0;
    $edadOp_60 = 0;
    $edadOp_70 = 0;
    $edadOp_80 = 0;
    $edadOp_90 = 0;
    $edadOp_100 = 0;



    $hoy = time();

    $masculinos = 0;
    $femeninas = 0;
    ////////////////////////////// Contar el total de votos y la distribucion //////////////////////////
    try {
      $sql = "SELECT voto, fecha_nac, sexo FROM padronelectoral WHERE voto= ?";

      $resultado = $base->prepare($sql);
      $resultado->execute(array('VD'));


      while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {


        switch ($registro['sexo']) {
          case ('M'):
            $masculinos += 1;
            break;
          case ('F'):
            $femeninas += 1;
            break;
        }


        if ($registro['fecha_nac'] != 'NO REP') {
          $fNacimiento = $registro['fecha_nac'];
          $fNacimiento = explode("_", $fNacimiento);
          @$fechaNacimiento = $fNacimiento[0] . "/" . $fNacimiento[1] . "/" . $fNacimiento[2];
          $Fecha_Unix = strtotime($fechaNacimiento);
          $resultadoEdad = $hoy - $Fecha_Unix;
          $resultadoEdad = $resultadoEdad / 86400;
          $edad = $resultadoEdad / 365;


          if ($edad < 30) {
            $edadPsuv_30 += 1;
          } elseif ($edad < 40) {
            $edadPsuv_40 += 1;
          } elseif ($edad < 50) {

            $edadPsuv_50 += 1;
          } elseif ($edad < 60) {
            $edadPsuv_60 += 1;
          } elseif ($edad < 70) {
            $edadPsuv_70 += 1;
          } elseif ($edad < 80) {
            $edadPsuv_80 += 1;
          } elseif ($edad < 150) {
            $edadPsuv_90 += 1;
          }
        }
      }

      $resultado->execute(array('OP'));
      while ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
        if ($registro['fecha_nac'] != 'NO REP') {
          $fNacimiento = $registro['fecha_nac'];
          $fNacimiento = explode("_", $fNacimiento);
          @$fechaNacimiento = $fNacimiento[0] . "/" . $fNacimiento[1] . "/" . $fNacimiento[2];
          $Fecha_Unix = strtotime($fechaNacimiento);
          $resultadoEdad = $hoy - $Fecha_Unix;
          $resultadoEdad = $resultadoEdad / 86400;
          $edad = $resultadoEdad / 365;


          if ($edad < 30) {
            $edadOp_30 += 1;
          } elseif ($edad < 40) {
            $edadOp_40 += 1;
          } elseif ($edad < 50) {
            $edadOp_50 += 1;
          } elseif ($edad < 60) {
            $edadOp_60 += 1;
          } elseif ($edad < 70) {
            $edadOp_70 += 1;
          } elseif ($edad < 80) {
            $edadOp_80 += 1;
          } elseif ($edad < 150) {
            $edadOp_90 += 1;
          }
        }
      }




      $resultado->closeCursor();
    } catch (Exception $e) {
      // die('Error: ' . $e->GetMessage());
    }


    $vd = contar("SELECT count(*) FROM flujo_electoral WHERE voto='VD'");
    $vb = contar("SELECT count(*) FROM flujo_electoral WHERE voto='VB'");
    $vo = contar("SELECT count(*) FROM flujo_electoral WHERE voto='OP'");
    $nc = contar("SELECT count(*) FROM flujo_electoral WHERE voto='NC'");



    $totalVotos = $vd + $vb + $vo + $nc;


    $porcetanjeVotosD = $vd * 100 / $totalVotos;
    $porcetanjeVotosB = $vb * 100 / $totalVotos;
    $porcetanjeVotosO = $vo * 100 / $totalVotos;
    $porcetanjeVotosN = $nc * 100 / $totalVotos;



    $divisorPsuv = $edadPsuv_20 + $edadPsuv_30 + $edadPsuv_40 + $edadPsuv_50 + $edadPsuv_60 + $edadPsuv_70 + $edadPsuv_80 + $edadPsuv_90 + $edadPsuv_100;
    $divisorOpos = $edadOp_20 + $edadOp_30 + $edadOp_40 + $edadOp_50 + $edadOp_60 + $edadOp_70 + $edadOp_80 + $edadOp_90 + $edadOp_100;


    $edadPsuv_30 = $edadPsuv_30 * 100 / $divisorPsuv;
    $edadPsuv_40 = $edadPsuv_40 * 100 / $divisorPsuv;
    $edadPsuv_50 = $edadPsuv_50 * 100 / $divisorPsuv;
    $edadPsuv_60 = $edadPsuv_60 * 100 / $divisorPsuv;
    $edadPsuv_70 = $edadPsuv_70 * 100 / $divisorPsuv;
    $edadPsuv_80 = $edadPsuv_80 * 100 / $divisorPsuv;
    $edadPsuv_90 = $edadPsuv_90 * 100 / $divisorPsuv;

    $edadPsuv_30 = number_format($edadPsuv_30, '0', '.', '.');
    $edadPsuv_40 = number_format($edadPsuv_40, '0', '.', '.');
    $edadPsuv_50 = number_format($edadPsuv_50, '0', '.', '.');
    $edadPsuv_60 = number_format($edadPsuv_60, '0', '.', '.');
    $edadPsuv_70 = number_format($edadPsuv_70, '0', '.', '.');
    $edadPsuv_80 = number_format($edadPsuv_80, '0', '.', '.');
    $edadPsuv_90 = number_format($edadPsuv_90, '0', '.', '.');

    $edadOp_30 = $edadOp_30 * 100 / $divisorOpos;
    $edadOp_40 = $edadOp_40 * 100 / $divisorOpos;
    $edadOp_50 = $edadOp_50 * 100 / $divisorOpos;
    $edadOp_60 = $edadOp_60 * 100 / $divisorOpos;
    $edadOp_70 = $edadOp_70 * 100 / $divisorOpos;
    $edadOp_80 = $edadOp_80 * 100 / $divisorOpos;
    $edadOp_90 = $edadOp_90 * 100 / $divisorOpos;

    $edadOp_30 = number_format($edadOp_30, '0', '.', '.');
    $edadOp_40 = number_format($edadOp_40, '0', '.', '.');
    $edadOp_50 = number_format($edadOp_50, '0', '.', '.');
    $edadOp_60 = number_format($edadOp_60, '0', '.', '.');
    $edadOp_70 = number_format($edadOp_70, '0', '.', '.');
    $edadOp_80 = number_format($edadOp_80, '0', '.', '.');
    $edadOp_90 = number_format($edadOp_90, '0', '.', '.');


    $query55555 = "SELECT * FROM inf_mcp WHERE pq='edo'";
    $buscarAlumnos55555 = $conexion->query($query55555);
    if ($buscarAlumnos55555->num_rows > 0) {
      while ($filavalor = $buscarAlumnos55555->fetch_assoc()) {

        $cantidadestrubch = $filavalor['estr_ubch'];
        $cantidadestrraas = $filavalor['estr_raas'];
        $cantidadestrpatrulla = $filavalor['estr_patrulla'];

        $calles = $filavalor['calles'];
        $comunidades = $filavalor['comunidades'];
        $ubch = $filavalor['ubch'];
      }
    }


    $cantidadestrubch = $cantidadestrubch * $ubch;
    $cantidadestrraas = $cantidadestrraas * $comunidades;



    ///////////////////////////////////// ESTADO DE LAS ESTRUCTURAS ///////////////////////////////////////////////

    $count1 = mysqli_query($conexion, "SELECT * FROM estructuraraas WHERE tipocargo='1'");
    $contarJefesCalle = mysqli_num_rows($count1);

    $totalPatrulla = $contarJefesCalle * $cantidadestrpatrulla;
    //total de patrullas que deberia haber
    $totalUbch = $cantidadestrubch;
    //total de ubch que deberia haber
    $totalRaas = $cantidadestrraas;
    //total de raas que deberia haber

    $divisorOptimo = $totalPatrulla + $totalUbch + $totalRaas;


    $count2 = mysqli_query($conexion, "SELECT * FROM estructuraraas WHERE tipocargo='0'");
    $cantidadRaas = mysqli_num_rows($count2);
    //cantidad raas cargada
    $count3 = mysqli_query($conexion, "SELECT * FROM estructuraubch");
    $cantidadUbch = mysqli_num_rows($count3);
    //cantidad ubch cargada
    $count4 = mysqli_query($conexion, "SELECT * FROM estructurapatrulla");
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




    if ($porcentaje < 40) {
      $color = "color: #63B3ED;";
    } elseif ($porcentaje < 75) {
      $color = "color: #fd7e14;";
    } else {
      $color = "color: #ea0606;";
    }
    if ($porcentajeUbchCritico < 40) {
      $colorUbch = "color: #63B3ED;";
    } elseif ($porcentajeUbchCritico < 75) {
      $colorUbch = "color: #fd7e14;";
    } else {
      $colorUbch = "color: #ea0606;";
    }
    if ($porcentajeRaasCritico < 40) {
      $colorRaas = "color: #63B3ED;";
    } elseif ($porcentajeRaasCritico < 75) {
      $colorRaas = "color: #fd7e14;";
    } else {
      $colorRaas = "color: #ea0606;";
    }
    if ($porcentajePatrullaCritico < 40) {
      $colorPatrulla  = "color: #63B3ED;";
    } elseif ($porcentajePatrullaCritico < 75) {
      $colorPatrulla = "color: #fd7e14;";
    } else {
      $colorPatrulla  = "color: #ea0606;";
    }


    ?>
    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Electores Registrados</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo number_format($totalVotos, '0', '.', '.'); ?>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-primary-Plano shadow text-center border-radius-md">
                    <i class="line icon-user text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Votos Duros</p>
                    <h5 class="font-weight-bolder mb-0">
                      <?php echo number_format($vd, '0', '.', '.');  ?>
                      <span class="text-danger text-sm font-weight-bolder"><?php echo number_format($porcetanjeVotosD, '2', '.', '.'); ?>%</span>
                    </h5>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-primary-Plano shadow text-center border-radius-md">
                    <i class="line icon-user-following text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Blandos</p>
                <h5 class="font-weight-bolder mb-0">
                  <?php echo number_format($vb, '0', '.', '.'); ?>
                  <span class="text-sm font-weight-bolder" style="color: #ffb2b6 !important;"><?php echo number_format($porcetanjeVotosB, '2', '.', '.'); ?>%</span>
                </h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Opositores</p>
                <h5 class="font-weight-bolder mb-0">
                  <?php echo number_format($vo, '0', '.', '.'); ?>
                  <span class="text-sm font-weight-bolder" style="color: #ffb2b6 !important;"><?php echo number_format($porcetanjeVotosO, '2', '.', '.'); ?>%</span>
                </h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-2 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">NC</p>
                <h5 class="font-weight-bolder mb-0">
                  <?php echo number_format($nc, '0', '.', '.'); ?>
                  <span class="text-sm font-weight-bolder" style="color: #ffb2b6 !important;"><?php echo number_format($porcetanjeVotosN, '2', '.', '.'); ?>%</span>
                </h5>
              </div>
            </div>
          </div>
        </div>
      </div>



      <div class="row mt-4">
        <div class="col-lg-6 mb-lg-0 mb-4">
          <div class="card h-100 ">
            <div class="card-body p-3">
              <div class="row">
                <div class="d-flex flex-column h-100">
                  <h6 class="font-weight-bolder mb-4 pt-2">Intencion de voto</h6>
                  <div id="chartdiv" style="height: 53vh;"></div>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <h6 class="font-weight-bolder mb-4">Reportes por Centro</h6>
                <div class="table-responsive">
                  <table class="table" id="table1">
                    <thead>
                      <tr>
                        <th class="p-0">Centro</th>
                        <th class="text-center p-0">R</th>
                        <th class="text-center p-0">Porcentaje</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php

                      function abrev($nombre)
                      {
                        // Lista de sustituciones
                        $sustituciones = [
                          'UNIDAD EDUCATIVA' => 'UE',
                          'CENTRO EDUCATIVO INTEGRAL BOLIVARIANO' => 'CEIB',
                          'ESCUELA BASICA' => 'EB',
                          'LICEO BOLIVARIANO' => 'LB',
                          'CENTRO MOVIL' => 'CM'
                        ];

                        // Reemplazar cada coincidencia en la lista de sustituciones
                        foreach ($sustituciones as $original => $abreviado) {
                          $nombre = str_replace($original, $abreviado, $nombre);
                        }

                        return $nombre;
                      }
                      $centroDatos = array();

                      $stmt = mysqli_prepare($conexion_app, "SELECT centro, CODIGO, ELECTORES FROM tablamesa");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          $centroDatos["$row[CODIGO]"] = [$row['centro'], $row['ELECTORES']];
                        }
                      }
                      $stmt->close();


                      $participacionCentro = array();


                      $stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.centro, COUNT(*) AS Nrepeticiones
                    FROM flujo_electoral
                    GROUP BY centro
                    ORDER BY Nrepeticiones ASC");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                          $participacionCentro = [abrev($centroDatos[$row['centro']][0]), number_format($row['Nrepeticiones'] * 100 / $centroDatos[$row['centro']][1], '1', '.', ',')];
                          echo '<tr>
                        <td>' . abrev($centroDatos[$row['centro']][0]) . '</td>
                        <td class="text-center">' . $row['Nrepeticiones'] . '</td>
                        <td class="text-center">' . number_format($row['Nrepeticiones'] * 100 / $centroDatos[$row['centro']][1], '1', '.', ',') . '% (' . $centroDatos[$row['centro']][1] . ')</td>
                      </tr>';
                        }
                      }
                      $stmt->close();
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
      <div class="col-lg-6 mb-lg-0 mb-4">

        <div class="card h-100 p-3">
          <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
            <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
              <h6 class="font-weight-bolder mb-4">Reportes por usuario</h6>
              <div class="table-responsive" >
                <table class="table" id="table2">
                  <thead>
                    <tr>
                      <th class="p-0">Centro</th>
                      <th class="text-center p-0">R</th>
                      <th class="text-center p-0">Porcentaje</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $nombres = array();
                    $stmt = mysqli_prepare($conexion_app, "SELECT cedula, nombre, centro, sexo FROM rep_24");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $nombres["$row[cedula]"] = [$row['nombre'], $row['centro']];
                      }
                    }
                    $stmt->close();

                    $stmt = mysqli_prepare($conexion_app, "SELECT responsable, centro, COUNT(*) AS Nrepeticiones
                    FROM flujo_electoral
                    GROUP BY responsable
                    ORDER BY Nrepeticiones ASC");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        if (@$nombres[$row['responsable']][0]) {
                          echo '<tr>
                        <td>' . $nombres[$row['responsable']][0] . '</td>
                        <td class="text-center">' . $row['Nrepeticiones'] . '</td>
                        <td class="text-center">' . number_format($row['Nrepeticiones'] * 100 / $centroDatos[$row['centro']][1], '1', '.', ',') . '% (' . $centroDatos[$row['centro']][1] . ')</td>
                      </tr>';
                        }
                      }
                    }
                    $stmt->close();
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="col-lg-6 mb-lg-0 mb-4">
        <div class="card h-100">
          <div class="card-body p-3">
            <div class="row">
              <div class="d-flex flex-column h-100">
                <h6 class="font-weight-bolder mb-4 pt-2">Jefes de UBCH registrados</h6>
                <div id="chartdivJefes" style="height: 50vh;"></div>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>



    <div class="row my-4">

      <div class="row">
        <div class="col-lg-8 mb-lg-0 mb-4">
          <div class="card h-100">
            <div class="card-body p-3">
              <div class="row">
                <div class="d-flex flex-column h-100">
                  <h6 class="font-weight-bolder mb-4 pt-2">Votos Blandos por Centro de Votaci&oacute;n</h6>
                  <div id="chartdivDist" style="height: 60vh;"></div>
                </div>

              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 mb-lg-0 mb-4">
          <div class="card h-100">
            <div class="card-body p-3">
              <div class="row">
                <div class="d-flex flex-column h-100">
                  <h6 class="font-weight-bolder mb-4 pt-2">Votos duros por genero</h6>
                  <div id="chartdivSexo" style="height: 60vh;"></div>
                </div>

              </div>
            </div>
          </div>
        </div>


      </div>

    </div>

    <div class="row my-4">

      <div class="col-lg-4 col-md-6">
        <div class="card h-100">
          <div class="card-header pb-0">
            <h6>Ultimos inicios de session</h6>

          </div>
          <div class="card-body p-3">
            <div class="timeline timeline-one-side">
              <?php
              $querry222 = "SELECT * FROM log_usuarios ORDER BY id DESC LIMIT 9";
              $search22222 = $conexion->query($querry222);
              if ($search22222->num_rows > 0) {
                while ($resultado2222222 = $search22222->fetch_assoc()) {

                  echo '<div class="timeline-block">
                              <span class="timeline-step">
                                <i style="font-size: 12px; margin-left: -3px; color: #cd454e" class="line icon-login"></i>
                              </span>
                              <div class="timeline-content">
                                <h6 style="margin-bottom: -3px">' . $resultado2222222['usuario'] . '</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">' . date("d-m-y H:i a", $resultado2222222['fecha']) . '</p>
                              </div>
                            </div>';
                }
              }
              ?>

            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-6 mb-md-0 mb-4">
        <div class="card h-100">
          <div class="card-header pb-0">
            <div class="row">
              <div class="col-lg-6 col-7">
                <h6>Ultimas10 acciones</h6>

              </div>

            </div>
          </div>
          <div class="card-body px-0 pb-2">
            <div class="table-responsive" style="padding-left: 15px;">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th style="font-size: 12px !important; padding: 8px;" class="text-xxs font-weight-bolder opacity-7">Fecha</th>
                    <th style="font-size: 12px !important;" class="text-xxs font-weight-bolder opacity-7 ps-2">Usuario</th>
                    <th style="font-size: 12px !important;" class="text-xxs font-weight-bolder opacity-7 ps-2">Tipo</th>
                    <th style="font-size: 12px !important;" class="text-xxs font-weight-bolder opacity-7 ps-2">Accion</th>
                    <th style="font-size: 12px !important;" class="text-secondary text-xxs font-weight-bolder opacity-7"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  function actividad()
                  {
                    $tabla = '';
                    global $conexion;
                    $query = "SELECT actividad_usuarios.accion, usuarios.nombre, actividad_usuarios.fecha, actividad_usuarios.tipo  FROM `actividad_usuarios`
                          LEFT JOIN usuarios ON actividad_usuarios.id_usuario=usuarios.id
                          ORDER BY actividad_usuarios.id DESC LIMIT 10";


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

                        $tabla .= '  <tr>
                            <td style="    font-size: 12px !important;">
                            <p class="text-xs text-secondary mb-0">' . $fila['fecha'] . '</p>
                            </td>
                            <td style="    font-size: 12px !important;">
                            <p class="text-xs text-secondary mb-0">' . $fila['nombre'] . '</p>
                            </td>
                            <td style="    font-size: 12px !important;">
                              <p class="text-xs font-weight-bold mb-0">' . $fila['tipo'] . '</p>
                            </td>
                            <td style="    font-size: 12px !important;">
                              <p class="text-xs font-weight-bold mb-0">' . $fila['accion'] . '</p>
                            </td style="    font-size: 12px !important;">
                            <td class="align-middle text-center text-sm">
                            ' . $hecho . '
                            </td>
                          </tr>';
                      }
                    }

                    return  $tabla;
                  }

                  echo  actividad();

                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- footer -->
  </div>
  </main>


  <script>
   var languageSettings = {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    };

    $('#table1').DataTable({
        language: languageSettings
    });

    $('#table2').DataTable({
        language: languageSettings
    });

  </script>

  <?php require_once('includes/settings.php');  ?>

  <script src="../vendors/amcharts5/index.js"></script>
  <script src="../vendors/amcharts5/percent.js"></script>
  <script src="../vendors/amcharts5/xy.js"></script>
  <script src="../vendors/amcharts5/flow.js"></script>
  <script src="../vendors/amcharts5/radar.js"></script>
  <script src="../vendors/amcharts5/themes/Animated.js"></script>
  <script src="../vendors/amcharts5/themes/Material.js"></script>

  <script src="../vendors/amcharts5/use/sliced-pictorial-stacked/index.js"></script>

  <script>
    series.data.setAll([{
        value: <?php echo $femeninas ?>,
        category: "F"
      },
      {
        value: <?php echo $masculinos ?>,
        category: "M"
      }
    ]);
  </script>
  <script src="../vendors/amcharts5/use/radar-line/index.js"></script>
  <script src="../vendors/amcharts5/use/flow-chord-directed/index.js"></script>

  <script>
    // Set data
    // https://www.amcharts.com/docs/v5/charts/flow-charts/#Setting_data
    series5.data.setAll([{
        "from": "Psuv",
        "to": "18-30",
        "value": <?php echo $edadPsuv_30 ?>
      },
      {
        "from": "Psuv",
        "to": "31-40",
        "value": <?php echo $edadPsuv_40 ?>
      },
      {
        "from": "Psuv",
        "to": "41-50",
        "value": <?php echo $edadPsuv_50 ?>
      },
      {
        "from": "Psuv",
        "to": "51-60",
        "value": <?php echo $edadPsuv_60 ?>
      },
      {
        "from": "Psuv",
        "to": "61-70",
        "value": <?php echo $edadPsuv_70 ?>
      },
      {
        "from": "Psuv",
        "to": "71-80",
        "value": <?php echo $edadPsuv_80 ?>
      },
      {
        "from": "Psuv",
        "to": "81...",
        "value": <?php echo $edadPsuv_90 ?>
      },
      {
        "from": "Oposicion",
        "to": "18-30",
        "value": <?php echo $edadOp_30 ?>
      },
      {
        "from": "Oposicion",
        "to": "31-40",
        "value": <?php echo $edadOp_40 ?>
      },
      {
        "from": "Oposicion",
        "to": "41-50",
        "value": <?php echo $edadOp_50 ?>
      },
      {
        "from": "Oposicion",
        "to": "51-60",
        "value": <?php echo $edadOp_60 ?>
      },
      {
        "from": "Oposicion",
        "to": "61-70",
        "value": <?php echo $edadOp_70 ?>
      },
      {
        "from": "Oposicion",
        "to": "71-80",
        "value": <?php echo $edadOp_80 ?>
      },
      {
        "from": "Oposicion",
        "to": "81...",
        "value": <?php echo $edadOp_90 ?>
      },
    ]);
  </script>
  <script>
    var data4 = [];

    data4.push({
      category: "Ubch",
      value: <?php echo round($porcentajeUbchCritico, 0, PHP_ROUND_HALF_DOWN); ?>
    });
    data4.push({
      category: "Raas",
      value: <?php echo round($porcentajeRaasCritico, 0, PHP_ROUND_HALF_DOWN); ?>
    });
    data4.push({
      category: "Patrullas",
      value: <?php echo round($porcentajePatrullaCritico, 0, PHP_ROUND_HALF_DOWN); ?>
    });


    var data = data4;


    series.data.setAll(data);
    xAxis.data.setAll(data);
  </script>
  <script src="../vendors/amcharts5/use/pie-donut-chart/index.js"></script>
  <script src="../vendors/amcharts5/use/xy-clustered-column/index.js"></script>

  <script src="../assets/js/core/bootstrap.min.js"></script>
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



  <script>
    // jefes de ubch //

    var data = [{
        "Municipio": "Atures",
        "total": 88,
        "avance": <?php echo $resultMcp[0] ?>,
      },
      {
        "Municipio": "A. Orinoco",
        "total": 14,
        "avance": <?php echo $resultMcp[1] ?>,
      },
      {
        "Municipio": "Autana",
        "total": 11,
        "avance": <?php echo $resultMcp[2] ?>,
      },
      {
        "Municipio": "Manapiare",
        "total": 8,
        "avance": <?php echo $resultMcp[3] ?>,
      },
      {
        "Municipio": "Maroa",
        "total": 4,
        "avance": <?php echo $resultMcp[4] ?>,
      },
      {
        "Municipio": "Rio Negro",
        "total": 6,
        "avance": <?php echo $resultMcp[5] ?>,
      },
      {
        "Municipio": "Atabapo",
        "total": 10,
        "avance": <?php echo $resultMcp[6] ?>,
      }



    ]


    // Create axes
    // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
    var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root2, {
      categoryField: "Municipio",
      renderer: am5xy.AxisRendererX.new(root2, {
        cellStartLocation: 0.1,
        cellEndLocation: 0.9
      }),
      tooltip: am5.Tooltip.new(root2, {})
    }));

    xAxis.data.setAll(data);

    var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root2, {
      renderer: am5xy.AxisRendererY.new(root2, {})
    }));


    // Add series
    // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
    function makeSeries(name, fieldName) {
      var series = chart.series.push(am5xy.ColumnSeries.new(root2, {
        name: name,
        xAxis: xAxis,
        yAxis: yAxis,
        valueYField: fieldName,
        categoryXField: "Municipio"
      }));

      series.columns.template.setAll({
        tooltipText: "{categoryX}: {name} {valueY}",
        width: am5.percent(90),
        tooltipY: 0
      });

      series.data.setAll(data);

      // Make stuff animate on load
      // https://www.amcharts.com/docs/v5/concepts/animations/
      series.appear();

      series.bullets.push(function() {
        return am5.Bullet.new(root2, {
          locationY: 0,
          sprite: am5.Label.new(root2, {
            text: "{valueY}",
            fill: root2.interfaceColors.get("alternativeText"),
            centerY: 0,
            centerX: am5.p50,
            populateText: true
          })
        });
      });

      legend.data.push(series);
    }

    makeSeries("Total", "total");
    makeSeries("Avance", "avance");
    // jefes de ubch //


    // hMapa del boto en donut chart //
    series.data.setAll([{
        value: <?php echo $vd ?>,
        category: "Voto Duro"
      },
      {
        value: <?php echo $vb ?>,
        category: "Voto Blando"
      },
      {
        value: <?php echo $vo ?>,
        category: "Voto Opositor"
      }, {
        value: <?php echo $nc ?>,
        category: "No caracterizado"
      },
    ]);


    var legend2 = chart.children.push(am5.Legend2.new(root, {
      centerX: am5.percent(50),
      x: am5.percent(50),
      marginTop: 15,
      marginBottom: 15,
    }));
    legend2.data.setAll(series.dataItems);
    // hMapa del boto en donut chart //
  </script>

</body>

</html>