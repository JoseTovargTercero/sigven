<?php
include('../configurar/configuracion.php');
include('../elecciones/configuracion/conexion.php'); // CONEXION A LA BASE DE DATOS DE LAS ELECCIONES

include('includes/navbar.php');


if ($_SESSION['nivel'] != 1) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}

if ($_SESSION["instancia"] == 0) {
  $extraCondicion = '';
  $extraCondicionW = '';
} elseif ($_SESSION['instancia'] == '1') {
  $extraCondicion = 'AND mcp=' . $_SESSION["id_instancia"];
  $extraCondicionW = 'WHERE mcp=' . $_SESSION["id_instancia"];
} else {
  $extraCondicion = 'AND pq=' . $_SESSION["id_instancia"];
  $extraCondicionW = 'WHERE pq=' . $_SESSION["id_instancia"];
}

//instancia - $_SESSION['id_instancia']



// sacar las metas por centro




// Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
/*$sql = "SELECT centro, COUNT(*) AS repeticiones FROM ( 
  SELECT centro, cedula FROM unox107 
  UNION ALL SELECT 
  cv as centro, cdula AS cedula FROM unox10 ) AS combined GROUP BY centro ORDER BY centro;";
*/$sql = "SELECT centro, COUNT(*) AS repeticiones FROM ( SELECT DISTINCT centro, cedula FROM ( SELECT centro, cedula FROM unox107 UNION ALL SELECT cv as centro, cdula AS cedula FROM unox10 ) AS combined ) AS distinct_combined GROUP BY centro ORDER BY centro;";

$stmt = $conexion_app->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

$metas_1107 = array();
if ($result->num_rows > 0) {
  // Obtener los datos y convertirlos en un arreglo asociativo
  while ($row = $result->fetch_assoc()) {
    $metas_1107[$row['centro']] = $row['repeticiones'];
  }
}



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








$reportes_unodiex = [];
$stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(unodiez), COUNT(*) as reportes FROM `flujo_electoral` WHERE unodiez!='' GROUP BY unodiez");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
$reportes_unodiex[$row['unodiez']] = $row['reportes'];
}
}


$jefesUnoDiex = [];

$tabla_reportes_110 = [];
$stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(cdula_jf), jefe, telefono, COUNT(*) AS cantidad FROM ( 
SELECT cdula_jf, telefono, jefe  FROM unox107
UNION ALL SELECT 
jfcedula, telefono, jefe FROM unox10) AS combined
GROUP BY cdula_jf");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
while ($row = $result->fetch_assoc()) {
if(@$reportes_unodiex[$row['cdula_jf']]){
$reportes = $reportes_unodiex[$row['cdula_jf']];
$porcentaje = $reportes * 100 / $row['cantidad'];
}else {
$reportes = 0;
$porcentaje = 0;
}
$jefesUnoDiex[$row['cdula_jf']] = true;

$tabla_reportes_110[] = '<tr>
<td>' . $row['cdula_jf'] . '</td>
<td>' . $row['jefe'] . '</td>
<td class="text-center">' . $row['telefono'] . '</td>
<td class="text-center">' . $reportes.' / '.$row['cantidad'] . '</td>
<td class="text-center">' . number_format($porcentaje, '2', '.', ',') . '%</td>
</tr>';

}
}
$stmt->close();



$jefesParticiparon = 0;
$stmt = mysqli_prepare($conexion_app, "SELECT cedula FROM flujo_electoral $extraCondicionW");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    if (@$jefesUnoDiex[$row['cedula']]) {
      $jefesParticiparon++;
    }
  }
}
$stmt->close();
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
    <?php echo $navbar;

    $votos_uno_dies = contar("SELECT count(*) FROM flujo_electoral WHERE unodiez!='0' $extraCondicion");


    $vd = contar("SELECT count(*) FROM flujo_electoral WHERE voto='VD' $extraCondicion");
    $vb = contar("SELECT count(*) FROM flujo_electoral WHERE voto='VB' $extraCondicion");
    $vo = contar("SELECT count(*) FROM flujo_electoral WHERE voto='OP' $extraCondicion");
    $nc = contar("SELECT count(*) FROM flujo_electoral WHERE voto='NC' $extraCondicion");



    $totalVotos = $vd + $vb + $vo + $nc;


    $porcetanjeVotosD = $vd * 100 / $totalVotos;
    $porcetanjeVotosB = $vb * 100 / $totalVotos;
    $porcetanjeVotosO = $vo * 100 / $totalVotos;
    $porcetanjeVotosN = $nc * 100 / $totalVotos;



    ?>
    <div class="container-fluid">

      <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Electores Registrados</p>
                    <h5 class="font-weight-bolder mb-0">
                      <span title="Votos duros"><?php echo number_format($totalVotos, '0', '.', '.'); ?></span> /
                      <small title="1*10/7"><?php echo number_format($votos_uno_dies, '0', '.', '.'); ?>/</small>
                      <small title="Jefes de 1*10/7"><?php echo number_format($jefesParticiparon, '0', '.', '.'); ?></small>
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
        <div class="col-xl-2 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Duros</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php echo number_format($vd, '0', '.', '.');  ?>
                    <span class="text-danger text-sm font-weight-bolder"><?php echo number_format($porcetanjeVotosD, '2', '.', '.'); ?>%</span>
                  </h5>
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
   




        <?php if ($_SESSION["instancia"] == 0) { ?>

          <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
            <div class="card h-100">
              <div class="card-body p-3">
                <div class="row">
                  <div class="d-flex flex-column h-100">
                    <h6 class="font-weight-bolder mb-4 pt-2">Reportes por municipios</h6>
                    <div id="chartdivJefes" style="height: 30vh;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>



        <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                    <div class="card h-100">
                      <div class="card-body p-3">
                        <div class="row">
                          <div class="d-flex flex-column h-100">
                            <h6 class="font-weight-bolder mb-4 pt-2">Reportes de apertura</h6>
                            <div class="table-responsive">
                  <table id="table5">
                    <thead>
                      <tr>
                        <th class="">Centro</th>
                        <th class="">Status</th>
                        <th class="">Hora apertura</th>
                        <th class="text-center" >Cantidad de electores</th>
                        <th class="" >Reporte</th>
                      </tr>
                    </thead>
                    <tbody>
                            <?php 
                              $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `tablamesa`");
                              $stmt->execute();
                              $result = $stmt->get_result();
                              if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                  if ($row['USER_REPORTE'] == '0') {
                                    $status = '<span class="badge bg-info">CERRADO</span>';
                                    $user_reporte = '';
                                    $hora_apertura = '';
                                  }else {
                                    $status = '<span class="badge bg-danger">ABIERTO</span>';
                                    $user_reporte = $row['USER_REPORTE'];
                                    $hora_apertura = $row['HORA_APERTURA'];
                                  }
                                  echo '<tr>
                                  <td>' . $row['centro'] . '</td>
                                  <td>' .$status . '</td>
                                  <td>' .$hora_apertura . '</td>
                                  <td class="text-center" >' . $row['ELECTORES_APERTURA'] . '</td>
                                  <td class="text-center">' . $user_reporte . '</td>
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
        <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                    <div class="card h-100">
                      <div class="card-body p-3">
                        <div class="row">
                          <div class="d-flex flex-column h-100" >
                            <h6 class="font-weight-bolder mb-4 pt-2">Reportes por 1*10/7</h6>

                            <div class="table-responsive">
                  <table id="table4">
                    <thead>
                      <tr>
                        <th class="p-0">Cedula</th>
                        <th class="p-0">Jefe</th>
                        <th class="text-center p-0" >Teléfono</th>
                        <th class="text-center p-0" >Electores</th>
                        <th class="text-center p-0">Porcentaje</th>
                      </tr>
                    </thead>
                    <tbody>
                          <?php 
                          foreach ($tabla_reportes_110 as $item) {
                            echo $item;
                          }
                          ?>
                    </tbody>
                  </table>
                            </div>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>



        <?php 
        $votos_por_hora = array(
          '05' => 0,
          '06' => 0,
          '07' => 0,
          '08' => 0,
          '09' => 0,
          '10' => 0,
          '11' => 0,
          '12' => 0,
          '13' => 0,
          '14' => 0,
          '15' => 0,
          '16' => 0,
          '17' => 0,
          '18' => 0,
          '19' => 0,
          '20' => 0,
          '21' => 0,
          '22' => 0,
          '23' => 0
        );



        $stmt = mysqli_prepare($conexion_app, "SELECT cedula, hora_reporte FROM `flujo_electoral` ORDER BY id ASC");
        $stmt->execute();
        $result = $stmt->get_result();
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $datetime = $row['hora_reporte'];
              $timestamp = strtotime($datetime);
              $time = date("H", $timestamp);
             
                $votos_por_hora[$time] += 1;
             
          }
        }
        $stmt->close();
        
        ?>


        <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
            <div class="card h-100">
              <div class="card-body p-3">
                <div class="row">
                  <div class="d-flex flex-column h-100">
                    <h6 class="font-weight-bolder mb-4 pt-2">Flujo de electores por hora</h6>
                    <div id="chartdivHora" style="height: 30vh;"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>



        <div class="col-lg-12 mb-4 mt-4">
          <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">

                <div class="   d-flex justify-content-between">
                  <h6 class="font-weight-bolder mb-4">Reportes por Centro</h6>
                  <a href="seguimiento_i.php" class="btn btn-sm btn-danger">Instituciones</a>

                </div>
                <div class="table-responsive">
                  <table id="table44">
                    <thead>
                      <tr>
                        <th class="p-0">Centro</th>
                        <th class="text-center p-0" style="background-color: #dbdbdb;">Asistencia <br> deseada</th>
                        <th class="text-center p-0" style="background-color: #dbdbdb;">Porcentaje</th>
                        <th class="text-center p-0">Tendencia 110/7</th>
                        <th class="text-center p-0">Total</th>
                        <th class="text-center p-0"></th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php


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

                      function getTendencia($favor, $total)
                      {
                        $result = $favor - ($total - $favor);
                        if ($result > 0) {
                          return '<span class="text-success"> + ' . $result . '</span>';
                        } elseif ($result < 0) {
                          return '<span class="text-danger"> - ' . abs($result) . '</span>';
                        } else {
                          return '<span class="text-warning">0</span>';
                        }
                      }
                      //SELECT flujo_electoral.centro, COUNT(*) AS Nrepeticiones, SUM(CASE WHEN unodiez != 0 THEN 1 ELSE 0 END) AS unodiez_diferente_de_cero FROM flujo_electoral GROUP BY centro ORDER BY `unodiez_diferente_de_cero` DESC
                      $favor_total = 0;
                      $contra_total = 0;
                      $stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.centro, COUNT(*) AS Nrepeticiones, SUM(CASE WHEN unodiez != 0 THEN 1 ELSE 0 END) AS unodiez_diferente_de_cero FROM flujo_electoral  $extraCondicionW GROUP BY centro ORDER BY `Nrepeticiones` ASC");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {

                          $participacionCentro = [abrev($centroDatos[$row['centro']][0]), number_format($row['Nrepeticiones'] * 100 / $centroDatos[$row['centro']][1], '1', '.', ',')];

                          if (abrev($centroDatos[$row['centro']][0]) != '') {
                            $meta = $metas_1107[$row['centro']];
                            $asistencia_1107 = $row['unodiez_diferente_de_cero'];


                            $tendencia1107 = getTendencia($asistencia_1107, $row['Nrepeticiones']);

                            $favor_total += $asistencia_1107;
                            $contra_total += $row['Nrepeticiones'] - $asistencia_1107;

                            echo '<tr>
                              <td>
  
                              <a href="reportes/estado_ubch.php?c='.$row['centro'].'">
                              <i style="margin-right: 15px;font-size: 17px;" class="line icon-cloud-download"></i>
                              
                              </a>
                              
                              ' . abrev($centroDatos[$row['centro']][0]) . '</td>
                              <td class="text-center" style="background-color: #dbdbdb;">' . $asistencia_1107 . ' / ' . $meta . ' </td>
                              <td class="text-center" style="background-color: #dbdbdb;">' . number_format($asistencia_1107 * 100 / $meta, '1', '.', ',') . '%</td>
                              <td class="text-center">' . $tendencia1107 . '</td>
                              <td class="text-center">' . $row['Nrepeticiones'] . '</td>
                              <td class="text-center"><button class="btn btn-sm btn-info" style="padding: 8px;">
                              <i class="line icon-share-alt" style="    font-size: 18px;"></i>
                              </button></td>
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



        <div class="col-lg-6 mb-4 mt-4">
          <div class="card h-100 ">
            <div class="card-body p-3">
              <div class="row">
                <div class="d-flex flex-column h-100">
                  <h6 class="font-weight-bolder mb-4 pt-2">Tendencia 110/7</h6>
                  <div id="chartdiv3" style="height: 53vh;"></div>
                </div>

              </div>
            </div>
          </div>
        </div>


        <div class="col-lg-6 mb-4 mt-4">
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
      </div>
      <div class="row mt-4">

      <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <h6 class="font-weight-bolder mb-4">Operadores</h6>
                <div class="table-responsive">
                  <table class="" id="table3">
                    <thead>
                      <tr>
                        <th class="p-0">Centro</th>
                        <th class="text-center p-0">Operador</th>
                        <th class="text-center p-0">Tipo</th>
                        <th class="text-center p-0">Reportes</th>
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
                                $nombres["$row[cedula]"] = [$row['nombre'], $row['centro'], ];
                              }
                            }
                            $stmt->close();
      


                            $stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(responsable), COUNT(*) AS Nreportes FROM `flujo_electoral` GROUP BY responsable");
                            $stmt->bind_param('s', $var);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                              while ($row = $result->fetch_assoc()) {
                                $reportes_user[$row['responsable']] = $row['Nreportes'];
                              }
                            }
                            $stmt->close();
                            

                      $stmt = mysqli_prepare($conexion_app, "SELECT * FROM tablamesa");
                      $stmt->execute();
                      $result = $stmt->get_result();
                      if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {



                        if ($row['OPERADOR'] != '' && $row['OPERADOR'] != 0) {
                          echo '<tr>
                          <td>' . $row['centro'] . '</td>
                          <td>' . $nombres[$row['OPERADOR']][0] . '</td>
                          <td class="text-center">Operador</td>
                          <td class="text-center">'.($reportes_user[$row['OPERADOR']] ? $reportes_user[$row['OPERADOR']] : 0).'</td>';
                        }
                        if ($row['OPERADOR_PUNTO'] != '' && $row['OPERADOR_PUNTO'] != 0) {
                          echo '<tr>
                          <td>' . $row['centro'] . '</td>
                          <td>' . $nombres[$row['OPERADOR_PUNTO']][0] . '</td>
                          <td class="text-center">Punto</td>
                          <td class="text-center">'.($reportes_user[$row['OPERADOR_PUNTO']] ? $reportes_user[$row['OPERADOR_PUNTO']] : 0).'</td>';
                        }
                        if ($row['OPERADOR_UBCH'] != '' && $row['OPERADOR_UBCH'] != 0) {
                          echo '<tr>
                          <td>' . $row['centro'] . '</td>
                          <td>' . $nombres[$row['OPERADOR_UBCH']][0] . '</td>
                          <td class="text-center">Ubch</td>
                          <td class="text-center">'.($reportes_user[$row['OPERADOR_UBCH']] ? $reportes_user[$row['OPERADOR_UBCH']] : 0).'</td>';
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




        <div class="col-lg-6 mb-lg-0 mt-4">
          <div class="card h-100 p-3">
            <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
              <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                <h6 class="font-weight-bolder mb-4">Reportes por usuario</h6>
                <div class="table-responsive">
                  <table class="" id="table2">
                    <thead>
                      <tr>
                        <th class="p-0">Centro</th>
                        <th class="text-center p-0">R</th>
                        <th class="text-center p-0">Porcentaje</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                
                      $stmt = mysqli_prepare($conexion_app, "SELECT responsable, centro, COUNT(*) AS Nrepeticiones
                    FROM flujo_electoral $extraCondicionW
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

   


      </div>

      <?php

        $cedulas_jefe = [];
        foreach ($jefesUnoDiex as $key => $item) {
          array_push($cedulas_jefe, $key);
        }
        $cedulas_jefe_list = implode("','", $cedulas_jefe);
        $cedulas_jefe_list = "'" . $cedulas_jefe_list . "'";


      $municipios = array(
        '1' => ['MP. ATURES'],
        '2' => ['MP. ATABAPO'],
        '3' => ['MP. MAROA'],
        '4' => ['MP. RIO NEGRO'],
        '5' => ['MP. AUTANA'],
        '6' => ['MP. MANAPIARE'],
        '7' => ['MP. ALTO ORINOCO']
      );


      foreach ($municipios as $key => $value) {
        $cantidadFlujo = contar("SELECT count(*) FROM flujo_electoral WHERE mcp='$key'");
        $cantidadFlujoUnod = contar("SELECT count(*) FROM flujo_electoral WHERE mcp='$key' AND unodiez!=0 AND unodiez!=''");


        $sql = "SELECT COUNT(*) AS count 
        FROM flujo_electoral 
        WHERE cedula IN ($cedulas_jefe_list) AND  mcp='$key'";

        $result = $conexion_app->query($sql);

        if ($result->num_rows > 0) {
            // Obtener el resultado
            $row = $result->fetch_assoc();
            $cantidadJefesParticiparon = $row['count'];
        } else {
          $cantidadJefesParticiparon = 0;
        }





        array_push($municipios[$key], $cantidadFlujo, $cantidadFlujoUnod,  $cantidadJefesParticiparon);
      }


      ?>



      <!-- footer -->
    </div>
    </main>

    <style>
      #chartdiv3 {
        width: 100%;
        height: 500px;
      }
      #chartdivHora {
        width: 100%;
        height: 600px;
      }
    </style>



    <script src="../vendors/amcharts5/index.js"></script>
    <script src="../vendors/amcharts5/percent.js"></script>
    <script src="../vendors/amcharts5/xy.js"></script>
    <script src="../vendors/amcharts5/flow.js"></script>
    <script src="../vendors/amcharts5/radar.js"></script>
    <script src="../vendors/amcharts5/themes/Animated.js"></script>
    <script src="../vendors/amcharts5/themes/Material.js"></script>


    <script src="../vendors/amcharts5/use/pie-donut-chart/index.js"></script>
    <?php if ($_SESSION["instancia"] == 0) { ?>
      <script src="../vendors/amcharts5/use/xy-clustered-column/index.js"></script>
    <?php } ?>
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
      $('#table4').DataTable({
        language: languageSettings
      });

      $('#table2').DataTable({
        language: languageSettings
      });
      $('#table3').DataTable({
        language: languageSettings
      });
      $('#table5').DataTable({
        language: languageSettings
      });
      $('#table44').DataTable({
        language: languageSettings
      });
    </script>


    <script>
      function grafico_tendencias() {
        am5.ready(function() {

          // Create root element
          // https://www.amcharts.com/docs/v5/getting-started/#Root_element
          var root = am5.Root.new("chartdiv3");


          // Set themes
          // https://www.amcharts.com/docs/v5/concepts/themes/
          root.setThemes([
            am5themes_Animated.new(root),
            am5themes_Material.new(root)
          ]);


          // Create chart
          // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/
          var chart = root.container.children.push(am5percent.PieChart.new(root, {
            layout: root.verticalLayout
          }));


          // Create series
          // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Series
          var series = chart.series.push(am5percent.PieSeries.new(root, {
            alignLabels: true,
            calculateAggregates: true,
            valueField: "value",
            categoryField: "category"
          }));

          series.slices.template.setAll({
            strokeWidth: 3,
            stroke: am5.color(0xffffff)
          });

          series.labelsContainer.set("paddingTop", 30)


          // Set up adapters for variable slice radius
          // https://www.amcharts.com/docs/v5/concepts/settings/adapters/
          series.slices.template.adapters.add("radius", function(radius, target) {
            var dataItem = target.dataItem;
            var high = series.getPrivate("valueHigh");

            if (dataItem) {
              var value = target.dataItem.get("valueWorking", 0);
              return radius * value / high
            }
            return radius;
          });


          // Set data
          // https://www.amcharts.com/docs/v5/charts/percent-charts/pie-chart/#Setting_data
          series.data.setAll([{
            value: <?php echo $favor_total ?>,
            category: "Favor"
          }, {
            value: <?php echo $contra_total ?>,
            category: "Contra"
          }]);


          // Create legend
          // https://www.amcharts.com/docs/v5/charts/percent-charts/legend-percent-series/
          var legend = chart.children.push(am5.Legend.new(root, {
            centerX: am5.p50,
            x: am5.p50,
            marginTop: 15,
            marginBottom: 15
          }));

          legend.data.setAll(series.dataItems);


          // Play initial series animation
          // https://www.amcharts.com/docs/v5/concepts/animations/#Animation_of_series
          series.appear(1000, 100);

        }); // end am5.ready()
      }
      grafico_tendencias();


function graficoHora(){
  am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdivHora");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);



var data = [
  { x: 5, value: <?php echo $votos_por_hora['05'] ?> },
  { x: 6, value: <?php echo $votos_por_hora['06'] ?> },
  { x: 7, value: <?php echo $votos_por_hora['07'] ?> },
  { x: 8, value: <?php echo $votos_por_hora['08'] ?> },
  { x: 9, value: <?php echo $votos_por_hora['09'] ?> },
  { x: 10, value: <?php echo $votos_por_hora['10'] ?> },
  { x: 11, value: <?php echo $votos_por_hora['11'] ?> },
  { x: 12, value: <?php echo $votos_por_hora['12'] ?> },
  { x: 13, value: <?php echo $votos_por_hora['13'] ?> },
  { x: 14, value: <?php echo $votos_por_hora['14'] ?> },
  { x: 15, value: <?php echo $votos_por_hora['15'] ?> },
  { x: 16, value: <?php echo $votos_por_hora['16'] ?> },
  { x: 17, value: <?php echo $votos_por_hora['17'] ?> },
  { x: 18, value: <?php echo $votos_por_hora['18'] ?> },
  { x: 19, value: <?php echo $votos_por_hora['19'] ?> },
  { x: 20, value: <?php echo $votos_por_hora['20'] ?> },
  { x: 21, value: <?php echo $votos_por_hora['21'] ?> },
  { x: 22, value: <?php echo $votos_por_hora['22'] ?> },
  { x: 23, value: <?php echo $votos_por_hora['23'] ?> }
];


// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(
  am5xy.XYChart.new(root, {
    panX: true,
    panY: true,
    wheelX: "panX",
    wheelY: "zoomX"
  })
);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xAxis = chart.xAxes.push(
  am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererX.new(root, {
      minGridDistance: 50
    }),
    tooltip: am5.Tooltip.new(root, {})
  })
);

var yAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    renderer: am5xy.AxisRendererY.new(root, {})
  })
);

// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
var series = chart.series.push(
  am5xy.LineSeries.new(root, {
    minBulletDistance: 10,
    xAxis: xAxis,
    yAxis: yAxis,
    valueYField: "value",
    valueXField: "x",
    tooltip: am5.Tooltip.new(root, {
      pointerOrientation: "horizontal",
      labelText: "{valueY}"
    })
  })
);

series.strokes.template.setAll({
  strokeWidth: 3
});

series.data.setAll(data);

series.bullets.push(function () {
  return am5.Bullet.new(root, {
    sprite: am5.Circle.new(root, {
      radius: 6,
      fill: series.get("fill"),
      stroke: root.interfaceColors.get("background"),
      strokeWidth: 2
    })
  });
});

// Add cursor
// https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
  xAxis: xAxis
}));
cursor.lineY.set("visible", false);



// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000, 100);
chart.appear(1000, 100);

// Function to add process control ranges
function addLimits(lower, upper) {
  // Add range fill
  createRange(lower, upper, undefined, am5.color(0xff8484));
  
  // Add upper/average/lower lines
  createRange(lower, undefined, "Baja participación", am5.color(0xc71f1f));
  createRange(upper, undefined, "Participación optima", am5.color(0xc71f1f));
  createRange(lower + (upper - lower) / 2, undefined, "Participación aceptable", am5.color(0xc71f1f), true);
  
}

//addLimits(10, 20)

function createRange(value, endValue, label, color, dashed) {
  var rangeDataItem = yAxis.makeDataItem({
    value: value,
    endValue: endValue
  });
  
  var range = yAxis.createAxisRange(rangeDataItem);
  
  if (endValue) {
    range.get("axisFill").setAll({
      fill: color,
      fillOpacity: 0.2,
      visible: true
    });
  }
  else {
    range.get("grid").setAll({
      stroke: color,
      strokeOpacity: 1,
      strokeWidth: 2,
      location: 1
    });
    
    if (dashed) {
      range.get("grid").set("strokeDasharray", [5, 3]);
    }
  }

  if (label) {
    range.get("label").setAll({
      text: label,
      location: 1,
      fontSize: 19,
      inside: true,
      centerX: am5.p0,
      centerY: am5.p100
    });
  }
}

}); // end am5.ready()
}

graficoHora()











      <?php if ($_SESSION["instancia"] == 0) { ?>
        // jefes de ubch //

        var data = []
/*

        array_push($municipios[$key], $cantidadFlujo, $cantidadFlujoUnod);

*/

        <?php
        foreach ($municipios as $key => $value) {
          echo 'data.push({
          "Municipio": "' . $value[0] . '",
          "total": ' . $value[1] . ',
          "avance": ' . $value[2] . ',
          "unoxdiez": ' . $value[3] . '
        });';
        }
        ?>


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
        makeSeries("Uno x diez", "unoxdiez");
        // jefes de ubch //
      <?php } ?>

      // hMapa del boto en donut chart //
      series.data.setAll([{
          value: <?php echo $vd ?>,
          category: "Duro"
        },
        {
          value: <?php echo $vb ?>,
          category: "Blando"
        },
        {
          value: <?php echo $vo ?>,
          category: "Opositor"
        }, {
          value: <?php echo $nc ?>,
          category: "NC"
        },
      ]);

      // hMapa del boto en donut chart //
    </script>

</body>

</html>