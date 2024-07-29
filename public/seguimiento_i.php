<?php
include('../configurar/configuracion.php');
include('../elecciones/configuracion/conexion.php'); // CONEXION A LA BASE DE DATOS DE LAS ELECCIONES


/*
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

$jefesUnoDiex[$row['cdula_jf']] = true;



}
}
$stmt->close();







echo '<table>
<thead>
<tr>
<th>cedula</th>
<th>nombre</th>
<th>ente</th>
<th>voto</th>
<th>uno10</th>
</tr>
</thead>
<tbody>

';





$stmt = mysqli_prepare($conexion_app, "SELECT e.*, f.responsable, f.unodiez FROM `empleados_instituciones` AS e
LEFT JOIN flujo_electoral AS f ON f.cedula = e.cedula
");
$stmt->bind_param('s', $var);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    echo '
    <tr>
    <td>'.$row['nombre'].'</td>
    <td>'.$row['cedula'].'</td>
    <td>'.$row['institucion'].'</td>
    <td>'.($row['responsable'] != ''?'SI':'NO').'</td>
    <td>';
    
    if ($row['unodiez'] != '') {
      echo $row['unodiez'];
    }elseif (@$jefesUnoDiex[$row['cedula']]) {
      echo 'JEFE';
    }else {
      echo 'NO';
    }
  
    echo '</td>
    </tr>
    ';
}
}
$stmt->close();


echo '</tbody>
</table>';









exit();
*/
include('includes/navbar.php');


if ($_SESSION['nivel'] != 1) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}

if ($_SESSION["instancia"] != 0) {
  header("Location:" . $_SERVER['HTTP_REFERER']);
}

/* EN CASO DE QUE SE RECIBA LA INFO */

if (isset($_POST['instituciones'])) {

  
// OBTENER TODOS LOS 1*10


// Ejecutar la consulta SQL con exclusión de registros en flujo_electoral
$sql = "SELECT cedula, jefe, telefono
FROM unox107
UNION
SELECT cdula AS cedula, jefe, telefono
FROM unox10";

$stmt = $conexion_app->prepare($sql);
$stmt->execute();

$result = $stmt->get_result();

$data110 = array();
if ($result->num_rows > 0) {
// Obtener los datos y convertirlos en un arreglo asociativo
while($row = $result->fetch_assoc()) {
    $data110[$row['cedula']] = [$row['jefe'], $row['telefono']];
}
}

// OBTENER TODOS LOS 1*10


  $instituciones_string = $_POST['instituciones'];

  // Convertir el string en un array
  $instituciones_list = explode(",", $instituciones_string);

  // Escapar cada valor para evitar inyecciones SQL
  $instituciones_list = array_map(function ($value) use ($conexion_app) {
    return "'" . $conexion_app->real_escape_string(trim($value)) . "'";
  }, $instituciones_list);

  // Convertir el array en una cadena para usar en la cláusula IN
  $instituciones_list = implode(", ", $instituciones_list);

  $instituciones_nombre = [];

  $stmt = mysqli_prepare($conexion_app, "SELECT DISTINCT(Institucion) FROM `jefes_instituciones` WHERE id IN ($instituciones_list)");
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      array_push($instituciones_nombre, "'" . $row['Institucion'] . "'");
    }
  }

  $stmt->close();

  $instituciones_list = implode(", ", $instituciones_nombre);

  $sql = "CREATE TEMPORARY TABLE empleados AS
          SELECT *
          FROM empleados_instituciones
          WHERE institucion IN ($instituciones_list)";

  if ($conexion_app->query($sql) !== TRUE) {
    echo json_encode(['status' => false, 'mensaje' => "Error creating temporary table: " . $conexion_app->error]);
    exit();
  }





  $avance = 0;
  $pendiente = 0;

  $avanceUbicacion = [];
  $tabla = [];
  $stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.id AS id_flujo, empleados.cedula, empleados.institucion, empleados.nombre, empleados.telefono, empleados.ubicacion FROM `empleados`
                LEFT JOIN flujo_electoral ON flujo_electoral.cedula = empleados.cedula");
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

      if ($row['ubicacion'] == '') {
        $ubicacion = 'Sin ubicación definida<br>';
      } else {
        $ubicacion = $row['ubicacion'];
        $ubicacion = ucfirst(strtolower($row['ubicacion']));
      }

      $info110 = @$data110[$row['cedula']];

      $tabla[] = '<tr>
                    <td>' . $row['cedula'] . '</td>
                    <td>' . $row['nombre'] . '</td>
                    <td>' . $row['institucion'] . '</td>
                    <td>' . $ubicacion . '</td>
                    <td>' . $row['telefono'] . '</td>
                    <td>' . ($row['id_flujo'] ? '<span class="badge bg-primary">VOTO</span>' : '<span class="badge bg-info">PENDIENTE</span>') . '</td>
                    <td>' . ($info110 ? $info110[0] : '')  . '</td>
                    <td>' . ($info110 ? $info110[1] : '')  . '</td>
                  </tr>';

      if ($row['id_flujo']) {
        $avance++;

        if (@$avanceUbicacion[$row['institucion']]) {
          @$avanceUbicacion[$row['institucion']][0] += 1;
        } else {
          @$avanceUbicacion[$row['institucion']][0] = 1;
        }
      } else {
        $pendiente++;
        if (@$avanceUbicacion[$row['institucion']]) {
          @$avanceUbicacion[$row['institucion']][1] += 1;
        } else {
          @$avanceUbicacion[$row['institucion']][1] = 1;
        }
      }
    }
  }
  $stmt->close();

  $total = $avance + $pendiente;

  $porc_avance = ($avance == 0 ? 0 : number_format($avance * 100 / $total, '2', '.', ''));
  $porc_pendiente = ($pendiente == 0 ? 0 : number_format($pendiente * 100 / $total, '2', '.', ''));


  /* EN CASO DE QUE SE RECIBA LA INFO */



  

  $votos_uno_dies = contar("SELECT count(*) FROM flujo_electoral WHERE unodiez!='0'");



}

?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Evento</title>
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
    <?php echo $navbar ?>
    <div class="container-fluid">
      <div class="row mt-4">

        <?php if (!isset($_POST["instituciones"])) { ?>
          <div class="col-lg-12 mb-4 mt-4" style="margin: auto;">
            <div class="card h-100 p-3">
              <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100">
                <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                  <h6 class="font-weight-bolder mb-4">Seleccione las instituciones</h6>
                  <div class="table-responsive">
                    <form id="senData" method="POST">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Ente</th>
                            <th class="text-center">Empleados</th>
                            <th class="text-center">Participación general</th>
                            <th class="text-center">1*10/7</th>
                            <th class="text-center">Tendencia 110/7</th>
                          </tr>
                        </thead>
                        <tbody>
                          

                        <?php
                        
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


                          function getRendimientoInst($ins) {
                            global $conexion_app;
                            $votantes = 0;
                            $unod = 0;
                            $stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.centro, flujo_electoral.unodiez, empleados_instituciones.cedula FROM `empleados_instituciones` 
                            LEFT JOIN flujo_electoral ON flujo_electoral.cedula=empleados_instituciones.cedula
                            WHERE institucion = ?");
                            $stmt->bind_param('s', $ins);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $total = $result->num_rows;
                            if ($total > 0) {
                              while ($row = $result->fetch_assoc()) {
                              if ($row['centro'] != '') {
                                $votantes++;
                              }
                              if ($row['unodiez'] != '' || $row['unodiez'] != 0) {
                                $unod++;
                              }
                            }
                            }
                            $stmt->close();

                            return [$total, $votantes, $unod];
                          }


                        $stmt = mysqli_prepare($conexion_app, "SELECT * FROM jefes_instituciones");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result->num_rows > 0) {
                          while ($row = $result->fetch_assoc()) {
                            //echo '<li><span>'.$row['institucion'].'</span> <span>HOla</span></li>';
                            // imprime con un li, el nombre la institucion y a la derecha un checbox con una clase que se pueda recorrer des jquery y que tenga como valor el nombre de la institucion

                            $instit = $row['Institucion'];
                            $datos = getRendimientoInst($instit);
                            echo '
                            <tr>
                            <td><label><input type="checkbox" class="instituciones form-check-input" value="' . $row['id'] . '">
                            <span>' . $instit . '</span> </label></td>
                            <td class="text-center">'.$datos[0].'</td>
                            <td class="text-center">'.$datos[1].'</td>
                            <td class="text-center">'.$datos[2].'</td>
                            <td class="text-center " style="font-weight: bolder;">'.getTendencia($datos[2], $datos[1]).'</td>
                          </tr>';
                          }
                        }
                        $stmt->close();
                        ?>
                      
                      </tbody>
                      </table>
                      <button class="btn btn-primary mt-3" onclick="consultar_instituciones()">Seleccionar</button>

                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>

    <script>
      function consultar_instituciones() {
        // prevenir el envio del formulairo

        let inst = ''
        event.preventDefault();
        $('.instituciones').each(function() {
          if ($(this).is(':checked')) {
            inst += $(this).val() + ',';
          }
        });
        if (inst == '') {
          alert('Debe seleccionar al menos una institución');
          return;
        }
        $('#senData').append('<input type="hidden" name="instituciones" value="' + inst + '">');
        $('#senData').submit();


      }
    </script>
  <?php } ?>








  <?php if (isset($_POST['instituciones'])) { ?>




    <section>


      <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total de servidores públicos</p>
                    <h5 class="font-weight-bolder mb-0">
                      <span title="Votos duros"><?php echo number_format($total, '0', '.', '.'); ?></span>
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
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="numbers">
                  <p class="text-sm mb-0 text-capitalize font-weight-bold">Votos</p>
                  <h5 class="font-weight-bolder mb-0">
                    <?php echo number_format($avance, '0', '.', '.');  ?>
                    <span class="text-danger text-sm font-weight-bolder"><?php echo number_format($porc_avance, '2', '.', '.'); ?>%</span>
                  </h5>
                </div>

              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="numbers">
                <p class="text-sm mb-0 text-capitalize font-weight-bold">Pendientes</p>
                <h5 class="font-weight-bolder mb-0">
                  <?php echo number_format($pendiente, '0', '.', '.'); ?>
                  <span class="text-sm font-weight-bolder" style="color: #ffb2b6 !important;"><?php echo number_format($porc_pendiente, '2', '.', '.'); ?>%</span>
                </h5>
              </div>
            </div>
          </div>
        </div>
      </div>


      <div class="row mt-3">

      <div class="col-lg-12">

      <div class="card mb-3">
                      <div class="card-header">
                        <h5>Porcentaje de participación por ente</h5>
                      </div>
                      <div class="card-body">
                        <div id="chartdiv" style="height: 40vh;"></div>
                      </div>
                    </div>
                    </div>



        <div class="col-lg-12">


          <div class="card mt-3">

            <div class="card-header d-flex justify-content-between">
              <h5>Servidores públicos</h5>
              <a href="" class="btn btn-danger">Salir</a>
              <button id="exportButton" class="btn btn-success"> <i class="fa fa-file-excel"></i> Exportar a Excel</button>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table id="DataTables">
                  <thead>
                    <tr>
                      <th>Cédula</th>
                      <th>nombre</th>
                      <th>Ente</th>
                      <th>Ubicación</th>
                      <th>Teléfono</th>
                      <th>Estatus</th>
                      <th>1*10/7</th>
                      <th>Teléfono</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    foreach ($tabla as $key => $value) {
                      echo $value;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

      </div>




    </section>

  <?php
  }
  ?>

  <!-- footer -->
  </div>
  </main>



  <style>
    #chartdiv {
      width: 100%;
      height: 500px;
    }

    #chartdiv2 {
      width: 100%;
      height: 60vh;
    }
    </style>

  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/jquery.nanoscroller.min.js"></script>
  <script src="../assets/js/menubar/sidebar.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>



  <script src="../vendors/sweetAlert2/sweetalert2.all.min.js"></script>

  <script src="assets/js/core/popper.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  <script>
    var table = $('#DataTables').DataTable({
      language: {
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
      },
    });


    document.getElementById('exportButton').addEventListener('click', function() {

      // Obtener la tabla DataTable
      var table = $('#DataTables').DataTable();

      // Obtener los nombres de las columnas
      var columnNames = [];
      $('#DataTables thead th').each(function() {
        columnNames.push($(this).text());
      });

      // Obtener los datos filtrados
      var filteredData = table.rows({
        search: 'applied'
      }).nodes().toArray();

      // Procesar los datos para extraer solo el texto
      var processedData = filteredData.map(function(row) {
        var rowData = [];
        $(row).find('td').each(function() {
          rowData.push($(this).text());
        });
        return rowData;
      });

      // Insertar los nombres de las columnas como la primera fila
      processedData.unshift(columnNames);

      // Crear una hoja de trabajo (worksheet) con los datos procesados
      var ws = XLSX.utils.aoa_to_sheet(processedData);

      // Crear un libro de trabajo (workbook)
      var wb = XLSX.utils.book_new();
      XLSX.utils.book_append_sheet(wb, ws, 'Datos Filtrados');

      let nombre = ($('#dt-search-0').val() == '' ? 'Reporte de flujo electoral' :  $('#dt-search-0').val())
      // Generar el archivo Excel y descargarlo
      XLSX.writeFile(wb, nombre + '.xlsx');
    });


<?php if(isset($_POST["instituciones"])){ ?>


    // GRAFICO ENTES

    am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([
  am5themes_Animated.new(root)
]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
var chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: true,
  panY: false,
  wheelX: "panX",
  wheelY: "zoomX",
  paddingLeft: 0,
  layout: root.verticalLayout
}));

// Add scrollbar
// https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
chart.set("scrollbarX", am5.Scrollbar.new(root, {
  orientation: "horizontal"
}));

var data = [];


<?php
function abreviarIns($nombre)
{
  $abr = [
    'Secretaria ejecutiva' => 'SE',
    'Unidad educativa nacional' => 'UEN',
    '<br>' => '',
  ];
  // Reemplazar cada coincidencia en la lista de sustituciones
  foreach ($abr as $original => $abreviado) {
    $nombre = str_replace($original, $abreviado, $nombre);
  }

  if (strlen($nombre) > 35) {
    // nombre sera igual a los primeros 20 caracteres, seguido de 3 puntos mas los ultimos 15 carcateres
    $nombre = substr($nombre, 0, 15) . '...' . substr($nombre, -10);
  }
  return trim($nombre);
}
foreach ($avanceUbicacion as $key => $value) {
  $faltantes = (@$value[1] ? $value[1] : 0) + (@$value[0] ? $value[0] : 0);
  echo '
    data.push({
    "country": "' . abreviarIns($key) . '",
    "year2004": ' . $faltantes . ',
    "year2005": ' . (@$value[0] ? $value[0] : 0) . '
  })';
}
?>

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var xRenderer = am5xy.AxisRendererX.new(root, {
  minGridDistance: 70,
  minorGridEnabled: true
});

xRenderer.labels.template.setAll({
  rotation: -90,
  centerY: am5.p50,
});

var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
  categoryField: "country",
  renderer: xRenderer,

  tooltip: am5.Tooltip.new(root, {
    themeTags: ["axis"],

    animationDuration: 200
  })
}));

xRenderer.grid.template.setAll({
  location: 1
})

xAxis.data.setAll(data);

var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
  min: 0,
  renderer: am5xy.AxisRendererY.new(root, {
    strokeOpacity: 0.1
  })
}));

// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/

var series0 = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Income",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "year2004",
  categoryXField: "country",
  clustered: false,
  tooltip: am5.Tooltip.new(root, {
    labelText: "Total: {valueY}"
  })
}));

series0.columns.template.setAll({
  width: am5.percent(80),
  tooltipY: 0,
  strokeOpacity: 0
});


series0.data.setAll(data);


var series1 = chart.series.push(am5xy.ColumnSeries.new(root, {
  name: "Income",
  xAxis: xAxis,
  yAxis: yAxis,
  valueYField: "year2005",
  categoryXField: "country",
  clustered: false,
  tooltip: am5.Tooltip.new(root, {
    labelText: "Participación: {valueY}"
  })
}));

series1.columns.template.setAll({
  width: am5.percent(50),
  tooltipY: 0,
  strokeOpacity: 0
});

series1.data.setAll(data);

var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {}));


// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
chart.appear(1000, 100);
series0.appear();
series1.appear();

}); // end am5.ready()

console.clear();
<?php } ?>
  </script>
</body>

</html>