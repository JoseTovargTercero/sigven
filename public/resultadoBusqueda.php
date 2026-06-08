<?php
include('../configurar/configuracion.php');
include('includes/navbar.php');

if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
  define('PAGINA_INICIO', '../index.php');
  header('Location: ' . PAGINA_INICIO);
}

$cedula = $_GET['cedula'];
if (!$cedula) {
  define('PAGINA_INICIO', 'consultas_ubch.php');
  header('Location: ' . PAGINA_INICIO);
}
$datosRep = '<ul class="list">';



////// REP FUNCTION //////// 
function rep()
{
  global $cedula;
  global $conexion;
  global $nombre;

  $query555 = "SELECT * FROM `rep28` WHERE cedula='$cedula'";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {
      $fNacimiento = $fila98555['fecha_nac'];
      $fNacimiento = explode("/", $fNacimiento);
      if ($fNacimiento[1] < 10) {
        $mes = "0" . $fNacimiento[1];
      } else {
        $mes = $fNacimiento[1];
      }
      $fechaNacimiento = $fNacimiento[2] . "/" . $mes . "/" . $fNacimiento[0];
      $datosRep = '
                   <li>' . ucwords(mb_strtolower($fila98555['nombre'])) . '</li>
                    <li><script>document.write(getEdad("' . $fechaNacimiento . '"))</script> A&ntilde;os</li>
                    <li>' . ucwords(mb_strtolower($fila98555['cv'])) . '</li>
                    <li>' . ucwords(mb_strtolower($fila98555['firma_maduro'])) . '</li>
                    ';
      $nombre = $fila98555['nombre'];
      if ($fila98555['firma_maduro'] == "1") {
        $datosRep = '<li> Firmo en contra</li>';
      }
    }
  }
  return $datosRep;
}
$datosRep .= rep();
$datosRep .= '</ul>';
////// END OF REP FUNCTION//////// 





////// SIGVEN FUNCTION //////// 
$datosSigven = '<ul class="list">';
function sigven()
{
  global $cedula;
  global $conexion;
  global  $nombre;

  $query555 = "SELECT padronelectoral.nombre, padronelectoral.calle, ciudad.name, padronelectoral.voto, padronelectoral.telefono FROM `padronelectoral`
LEFT JOIN `ciudad` ON padronelectoral.comunidad=ciudad.id 
WHERE padronelectoral.cedula='$cedula'";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {

      $datosSigven = '
      <li>Voto:  ' . $fila98555['voto'] . '</li>
      <li>' . ucwords(mb_strtolower($fila98555['telefono'])) . '</li>
      <li>' . ucwords(mb_strtolower($fila98555['name'])) . '</li>
      <li>' . $fila98555['calle'] . '</li>
    ';

      if (!$nombre) {
        $nombre  = $fila98555['nombre'];
      }
    }
  } else {
    $datosSigven = '<li>No Encontrado</li>';
  }

  return $datosSigven;
}

$datosSigven .= sigven();

function consultaRaas()
{
  global $cedula;
  global $conexion;
  global $nombre;
  $query555 = "SELECT estructuraraas.cargo, estructuraraas.tipocargo, ciudad.name FROM `estructuraraas`
  LEFT JOIN `ciudad` ON estructuraraas.comunidad=ciudad.id 
  WHERE estructuraraas.cedula='$cedula' LIMIT 1";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {
      if ($fila98555['tipocargo'] == 1) {
        $datosSigven = '<li><strong>JC:</strong> ' . ucwords(mb_strtolower($fila98555['cargo'])) . " (" . ucwords(mb_strtolower($fila98555['name'])) . ')</li>';
      } else {
        $datosSigven = '<li><strong>RAAS:</strong> ' . ucwords(mb_strtolower($fila98555['cargo'])) . " (" . ucwords(mb_strtolower($fila98555['name'])) . ')</li>';
      }
      if (!$nombre) {
        $nombre  = $fila98555['nombre'];
      }
    }
  } else {
    $datosSigven = '<li>No Encontrado</li>';
  }
  return $datosSigven;
}
$datosSigven .= consultaRaas();


function consultaPatrulla()
{
  global $cedula;
  global $conexion;
  global $nombre;
  $query555 = "SELECT estructurapatrulla.cargo, ciudad.name FROM `estructurapatrulla`
    LEFT JOIN `ciudad` ON estructurapatrulla.comunidad=ciudad.id 
    WHERE estructurapatrulla.cedula='$cedula' LIMIT 1";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {
      $datosSigven = '<li><strong>(P):</strong> ' . ucwords(mb_strtolower($fila98555['cargo'])) . " (" . ucwords(mb_strtolower($fila98555['name'])) . ')</li>';
      if (!$nombre) {
        $nombre  = $fila98555['nombre'];
      }
    }
  } else {
    $datosSigven = '<li>No Encontrado</li>';
  }
  return $datosSigven;
}
$datosSigven .= consultaPatrulla();

function consultaUbch()
{
  global $cedula;
  global $conexion;
  global $nombre;
  $query555 = "SELECT estructuraubch.cargo, pais.name1 FROM `estructuraubch`
    LEFT JOIN `pais` ON estructuraubch.ubch=pais.id 
    
    WHERE estructuraubch.cedula='$cedula' LIMIT 1";
  $buscarAlumnos55555 = $conexion->query($query555);
  if ($buscarAlumnos55555->num_rows > 0) {
    while ($fila98555 = $buscarAlumnos55555->fetch_assoc()) {
      $datosSigven = '<li><strong>UBCH:</strong> ' . ucwords(mb_strtolower($fila98555['cargo'])) . " (" . ucwords(mb_strtolower($fila98555['name1'])) . ')</li>';
      if (!$nombre) {
        $nombre  = $fila98555['nombre'];
      }
    }
  } else {
    $datosSigven = '<li>No Encontrado</li>';
  }
  return $datosSigven;
}
$datosSigven .= consultaUbch();
$datosSigven .= '</ul>';

////// END OF SIGVEN FUNCTION ////////


?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Busqueda
  </title>
  <link rel="stylesheet" href="../vendors/animatedLines/css/simple-line-icons.css">

  <link rel="stylesheet" href="../vendors/animatedLines/css/animate.css">
  <script src="../vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="../vendors/validator/fv.css" type="text/css" />
  <link href="../assets/css/sidebar.css" rel="stylesheet" />
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script type="text/javascript" src="jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/ol@v7.3.0/dist/ol.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@v7.3.0/ol.css">
  <style>
    #map {
      height: 400px;
      width: 100%;
      border-radius: 8px;
    }

    .ol-popup {
      position: absolute;
      background-color: white;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #cccccc;
      bottom: 12px;
      left: -50px;
      min-width: 280px;
    }

    .ol-popup:after,
    .ol-popup:before {
      top: 100%;
      border: solid transparent;
      content: " ";
      height: 0;
      width: 0;
      position: absolute;
      pointer-events: none;
    }

    .ol-popup:after {
      border-top-color: white;
      border-width: 10px;
      left: 48px;
      margin-left: -10px;
    }

    .ol-popup:before {
      border-top-color: #cccccc;
      border-width: 11px;
      left: 48px;
      margin-left: -11px;
    }

    .ol-popup-closer {
      text-decoration: none;
      position: absolute;
      top: 2px;
      right: 8px;
    }

    .ol-popup-closer:after {
      content: "✖";
    }
  </style>
  <?php include('darkMode.php');  ?>
  <script>
    function getEdad(dateString) {
      let hoy = new Date()
      let fechaNacimiento = new Date(dateString)
      let edad = hoy.getFullYear() - fechaNacimiento.getFullYear()
      let diferenciaMeses = hoy.getMonth() - fechaNacimiento.getMonth()
      if (
        diferenciaMeses < 0 ||
        (diferenciaMeses === 0 && hoy.getDate() < fechaNacimiento.getDate())
      ) {
        edad--
      }
      return edad
    }

    $(function() {
      var h6 = $('.namePagina');
      var li = $('.index');
      li.text('Consultas');
      h6.text('Busqueda');

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
          if (imgs.length === 3) {
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
      <div class="row">


        <div class="col-md-3">
          <div class="card  h-100" style="place-content: center; display: grid;">
            <div class="card-header mx-4 p-3 text-center">
              <div class="icon icon-shape icon-lg shadow text-center back" style="height: 150px;width: 150px;border-radius: 50%;background-image: url(../assets/img/user_3.png);background-position: center;background-size: inherit;filter: grayscale(1);opacity: 0.4;">



              </div>
            </div>
            <div class="card-body pt-0 p-3 text-center">
              <p style="margin-top: 15px;  margin-bottom: 5px;"><?php echo ucwords(mb_strtolower($nombre)) ?></p>
              <h5 class="mb-0"><?php echo $cedula ?></h5>
            </div>
          </div>
        </div>



        <div class="col-md-9">
          <div class="card  h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Resultados</h6>
            </div>
            <div class="card-body">
              <div class="row">

                <div class="col-lg-4">
                  <h6>REP</h6>
                  <?php echo $datosRep ?>
                </div>
                <div class="col-lg-4">
                  <h6>SIGVEN</h6>
                  <?php echo $datosSigven ?>
                </div>
                <di class="col-lg-4">
                  <h6>1x10</h6>
                  <ul class="list">

                    <?php
                    require '../elecciones/configuracion/conexion.php';

                    $stmt = mysqli_prepare($conexion_app, "SELECT * FROM `unox10` WHERE cdula = ? LIMIT 1");
                    $stmt->bind_param('s', $cedula);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {

                        echo '<li>Encontrado</li>';
                        echo '<li>Jefe: ' . explode(';', $row['jefe'])[0] . '</li>';
                        echo '<li>Cedula Jefe: ' . (explode(';', $row['jefe'])[1] == '' ? $row['jfcedula'] : explode(';', $row['jefe'])[1]) . '</li>';
                      }
                    }
                    ?>

                  </ul>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid py-4">
      <div class="row">


        <div class="col-md-12">
          <div class="card  h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">Posicion Geogtrafica</h6>
            </div>
            <div class="card-body centerDiv">

              <div class="cotenedor" style="position: relative;">
                <h6 class="ndp" id="map-ndp">No Disponible</h6>
                <div id="map"></div>
                <div id="popup" class="ol-popup">
                  <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                  <div id="popup-content"></div>
                </div>
              </div>


            </div>
          </div>
        </div>




      </div>
    </div>
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



    async function consultarCedulas() {
      const url = 'https://gitcom-ve.com/back/api_consulta_cedulas.php';

      const cedula = '<?php echo $cedula; ?>'; // Reemplaza con la cédula que deseas consultar
      const cuerpoPeticion = {
        token: '123456789-9',
        cedulas: [cedula]
      };

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(cuerpoPeticion)
        });

        const resultado = await response.json();

        if (!response.ok) {
          throw new Error(resultado.error || 'Error desconocido');
        }

        console.log('Datos recibidos:', resultado);

        if (resultado && resultado.success && resultado.datos && resultado.datos.length > 0) {
          const dato = resultado.datos[0];
          const lon = parseFloat(dato.coordenada_este);
          const lat = parseFloat(dato.coordenada_norte);

          if (!isNaN(lon) && !isNaN(lat)) {
            const ndpEl = document.getElementById('map-ndp');
            if (ndpEl) ndpEl.style.display = 'none';

            const mapCenter = ol.proj.fromLonLat([lon, lat]);

            const container = document.getElementById('popup');
            const content = document.getElementById('popup-content');
            const closer = document.getElementById('popup-closer');

            const overlay = new ol.Overlay({
              element: container,
              autoPan: {
                animation: {
                  duration: 250,
                },
              },
            });

            closer.onclick = function() {
              overlay.setPosition(undefined);
              closer.blur();
              return false;
            };

            const map = new ol.Map({
              target: 'map',
              layers: [
                new ol.layer.Tile({
                  source: new ol.source.OSM()
                })
              ],
              overlays: [overlay],
              view: new ol.View({
                center: mapCenter,
                zoom: 15
              })
            });

            const marker = new ol.Feature({
              geometry: new ol.geom.Point(mapCenter)
            });

            const vectorSource = new ol.source.Vector({
              features: [marker]
            });

            const markerVectorLayer = new ol.layer.Vector({
              source: vectorSource,
              style: new ol.style.Style({
                image: new ol.style.Circle({
                  radius: 7,
                  fill: new ol.style.Fill({
                    color: 'red'
                  }),
                  stroke: new ol.style.Stroke({
                    color: 'white',
                    width: 2
                  })
                })
              })
            });

            map.addLayer(markerVectorLayer);

            // Set popup content and position
            content.innerHTML = `
              <p style="margin-bottom: 5px;"><strong>Cédula:</strong> ${dato.cedula}</p>
              <p style="margin-bottom: 5px;"><strong>Teléfono:</strong> ${dato.telefono}</p>
              <p style="margin-bottom: 0;"><strong>Coordenadas:</strong> ${lat}, ${lon}</p>
            `;
            overlay.setPosition(mapCenter);
          }
        }

        return resultado;

      } catch (error) {
        console.error('Error al consultar:', error.message);
      }
    }

    // Para ejecutarla:
    document.addEventListener('DOMContentLoaded', () => {
      consultarCedulas();
      // oculta .loader 
      document.querySelector('.loader').style.display = 'none';
    });
  </script>

</body>

</html>