<?php
ob_start();
include('elecciones/configuracion/conexion.php');

if (!isset($_POST["param_2"])) {
  header("Location: seguir.php");
}

$cedula = $_POST["param_2"];
$institucion = $_POST["param_1"];

$stmt = mysqli_prepare($conexion_app, "SELECT institucion FROM jefes_instituciones WHERE cedula = ?");
$stmt->bind_param('s', $cedula);
$stmt->execute();
if ($stmt->execute()) {
  $result = $stmt->get_result();

  if (!$result->num_rows > 0) {
    header("Location: seguir.php");
  }else{
    $row = $result->fetch_assoc();
    $institucion = $row["institucion"];
  }
} else {
  header("Location: seguir.php");
}
$stmt->close();


$avance = 0;
$pendiente = 0;

$avanceUbicacion = [];
$tabla = [];
$stmt = mysqli_prepare($conexion_app, "SELECT flujo_electoral.id AS id_flujo, empleados_instituciones.cedula, empleados_instituciones.nombre, empleados_instituciones.telefono, empleados_instituciones.ubicacion FROM `empleados_instituciones`
                LEFT JOIN flujo_electoral ON flujo_electoral.cedula = empleados_instituciones.cedula
                 WHERE institucion = ? ");
$stmt->bind_param('s', $institucion);
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

    $tabla[] = '<tr>
                    <td>' . $row['cedula'] . '</td>
                    <td>' . $row['nombre'] . '</td>
                    <td>' . $ubicacion . '</td>
                    <td>' . $row['telefono'] . '</td>
                    <td>' . ($row['id_flujo'] ? '<span class="badge bg-primary">VOTO</span>' : '<span class="badge bg-info">PENDIENTE</span>') . '</td>
                  </tr>';

    if ($row['id_flujo']) {
      $avance++;

      if (@$avanceUbicacion[$ubicacion]) {
        @$avanceUbicacion[$ubicacion][0] += 1;
      } else {
        @$avanceUbicacion[$ubicacion][0] = 1;
      }
    } else {
      $pendiente++;
      if (@$avanceUbicacion[$ubicacion]) {
        @$avanceUbicacion[$ubicacion][1] += 1;
      } else {
        @$avanceUbicacion[$ubicacion][1] = 1;
      }
    }
  }
}
$stmt->close();

$total = $avance + $pendiente;

$porc_avance = ($avance == 0 ? 0 : number_format($avance * 100 / $total, '2', '.', ''));
$porc_pendiente = ($pendiente == 0 ? 0 : number_format($pendiente * 100 / $total, '2', '.', ''));


// No estoy interesado, ya tengo uno de 1500va.
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>
    <?php echo $institucion ?>
  </title>
  <link rel="stylesheet" href="vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="vendors/animatedLines/css/simple-line-icons.css">


  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script src="vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script><!--
  <link rel="stylesheet" href="vendors/datatables/datatables.min.css" />
  <script src="vendors/datatables/datatables.min.js"></script> -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css" />
  <script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

  <style>
    p {
      font-size: 12px;
    }

    .lista-ubicaciones {
      height: 110vh;
      overflow-y: auto;
    }

    .lista-ubicaciones::-webkit-scrollbar {
      width: 8px;
      height: 8px;
    }

    .lista-ubicaciones::-webkit-scrollbar-thumb {
      background-color: gray;
      border-radius: 4px;
    }
    .sombra-bajo-lista{
      box-shadow: 0px -14px 13px 18px #ddd;
      height: 10px;
      z-index: 99999;
      position: relative;
    }
    .counter {
        font-weight: bold;
        font-size: 20px;
    }
  </style>
</head>

<body class="">
  <div class="loading spinner-wrapper show">
    <div class="spinner">
      <div class="bounce1"></div>
      <div class="bounce2"></div>
      <div class="bounce3"></div>
    </div>
  </div>
  <div class=" top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="pages/dashboard.html">
              SIGVEN 2024
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="pages/dashboard.html">
                    PSUV - AMAZONAS
                  </a>
                </li>


              </ul>
              <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                  <a href="seguir.php" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-danger">Salir</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content ">
    <section>
      <div class="page-header ">
        <div style="width: 100% !important;margin: 15px 33px;">
          <div class="row">
            <div class="col lg-12">

              <div class="d-flex justify-content-between">
                <div>
                  <h3 class="mb-0">Participación</h3>
                  <span class="text-muted"><?php echo $institucion ?></span>
                </div>

                <div class="d-flex">
                  <div class="card bg-primary p-2 text-center text-white me-2 " style="width: 126px;">
                    <div class="counter">
                      <?php echo $avance ?>
                    </div>
                    <span>
                      Participacion
                    </span>
                  </div>
                  <div class="card bg-info p-2 text-center text-white " style="width: 126px;">
                    <div class="counter">
                      <?php echo $pendiente ?>
                    </div>
                    <span>Pendientes</span>
                  </div>
                </div>
                <div>
                </div>

              </div>
              <hr>
              <div class="mt-3">

                <div class="row">
                  <div class="col-lg-3">
                    <div class="card mb-3" style="background: #d5d5d5;">
                      <div class="card-body">
                        <h5 class="mb-0">Por ubicación</h5>
                        <small class="text-muted">Listado de reportes por ubicaciones</small>
                      </div>
                    </div>
                    <div class="row lista-ubicaciones">
                      <?php
                      foreach ($avanceUbicacion as $key => $value) {
                        echo '<div class="col-lg-12 col-sm-4" style="margin-bottom: 10px !important">
                              <div class="card">
                                <div class="card-body">
                                <p class="card-title">' . $key . '</p>
                                <p class="card-text">
                                  VOTOS <span class="badge bg-primary">' . (@$value[0] ? $value[0] : 0) . '</span>
                                  FALTA <span class="badge bg-info">' . (@$value[1] ? $value[1] : 0) . '</span>
                                </p>
                              </div>
                            </div>
                          </div>';
                      }
                      ?>
                    </div>
                    <div class="sombra-bajo-lista">
                    </div>
                  </div>
                  <div class="col-lg-9">

                    <div class="card mb-3">
                      <div class="card-header">
                        <h5>Porcentaje de anvance</h5>
                      </div>
                      <div class="card-body">
                        <div style="height: 35vh;" id="chartdiv"></div>
                      </div>
                    </div>



                    <div class="card mt-3" >
                      
                      <div class="card-header d-flex justify-content-between">
                        <h5>Empleados</h5>
                        <button id="exportButton" class="btn btn-success"> <i class="fa fa-file-excel"></i> Exportar a Excel</button>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="DataTables">
                            <thead>
                              <tr>
                                <th>Cédula</th>
                                <th>nombre</th>
                                <th>Ubicación</th>
                                <th>Teléfono</th>
                                <th>Estatus</th>
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


              </div>

            </div>
          </div>
        </div>
    </section>
  </main>
  <style>
    #chartdiv {
      width: 100%;
      height: 500px;
    }
  </style>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="vendors/sweetAlert2/sweetalert2.all.min.js"></script>

  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>


  <script src="vendors/amcharts5/index.js"></script>
  <script src="vendors/amcharts5/percent.js"></script>
  <script src="vendors/amcharts5/themes/Animated.js"></script>



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
      var filteredData = table.rows({ search: 'applied' }).nodes().toArray();
      
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
      
      // Generar el archivo Excel y descargarlo
      XLSX.writeFile(wb, $('#dt-search-0').val()+'.xlsx');
  });



  </script>
  <script>
    am5.ready(function() {
      // Create root and chart
      var root = am5.Root.new("chartdiv");

      root.setThemes([
        am5themes_Animated.new(root)
      ]);

      var chart = root.container.children.push(
        am5percent.PieChart.new(root, {
          layout: root.verticalLayout
        })
      );

      // Create series
      var series = chart.series.push(
        am5percent.PieSeries.new(root, {
          valueField: "percent",
          categoryField: "type",
          fillField: "color",
          alignLabels: false
        })
      );

      series.slices.template.set("templateField", "sliceSettings");
      series.labels.template.set("radius", 30);

      // Set up click events
      series.slices.template.events.on("click", function(event) {
        console.log(event.target.dataItem.dataContext)
        if (event.target.dataItem.dataContext.id != undefined) {
          selected = event.target.dataItem.dataContext.id;
        } else {
          selected = undefined;
        }
        series.data.setAll(generateChartData());
      });

      // Define data
      var selected;
      var types = [{
        type: "Avance",
        percent: <?php echo $porc_avance ?>,
        color: am5.color("#FF6B6B"), // Color rojo
      }, {
        type: "Pendiente",
        percent: <?php echo $porc_pendiente ?>,
        color: am5.color("#FFD6D6"), // Color rosado
      }];
      series.data.setAll(generateChartData());

      function generateChartData() {
        var chartData = [];
        for (var i = 0; i < types.length; i++) {
          if (i == selected) {
            for (var x = 0; x < types[i].subs.length; x++) {
              chartData.push({
                type: types[i].subs[x].type,
                percent: types[i].subs[x].percent,
                color: types[i].color,
                pulled: true,
                sliceSettings: {
                  active: true
                }
              });
            }
          } else {
            chartData.push({
              type: types[i].type,
              percent: types[i].percent,
              color: types[i].color,
              id: i
            });
          }
        }
        return chartData;
      }

    }); // end am5.ready()
  </script>




  <script>
    function alertaNoticia(title, text, tipo) {
      Swal.fire({
        type: tipo,
        title: title,
        text: text,
        timer: 3000, //el tiempo que dura el mensaje en ms

      });
    };

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
          if (imgs.length === 0) {
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
</body>

</html>
<?php
ob_end_flush();
?>