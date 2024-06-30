<?php
session_start();
if (@$_SESSION['noticia'] != "") {
  $alerta = explode("/", $_SESSION['noticia']);
  unset($_SESSION['noticia']);
} else {

  $alerta[0] = "";
  $alerta[1] = "";
  $alerta[2] = "";
  $alerta[3] = "NO ASIGNADO";
  unset($_SESSION['noticia']);
}

unset($_SESSION);
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>Instituciones</title>
  <link rel="stylesheet" href="vendors/animatedLines/css/animate.css">
  <link rel="stylesheet" href="vendors/animatedLines/css/simple-line-icons.css">


  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <script src="vendors/jquery-3.1.1.min.js" crossorigin="anonymous"></script>

  <script>
    function alertaNoticia(title, text, tipo) {
      Swal.fire({
        type: tipo,
        title: title,
        text: text,
        timer: 3000, //el tiempo que dura el mensaje en ms

      });
    };
  </script>

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
</head>

<body class="">


  <div class="loading spinner-wrapper show">
    <div class="spinner">
      <div class="bounce1"></div>
      <div class="bounce2"></div>
      <div class="bounce3"></div>
    </div>
  </div>




  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="pages/dashboard.html">
              SIGVEN <script>
                document.write(new Date().getFullYear())
              </script>
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
          
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-primary text-gradient">Bienvenido</h3>
                  <p class="mb-0">Introduce tu cedula</p>
                </div>
                <div class="card-body">
                  <label>Cedula</label>
                  <div class="mb-3">
                    <input type="number" class="form-control" placeholder="Cedula" autofocus aria-label="Cedula" id="cedula" >
                  </div>
                  <div class="text-center">
                    <button id="btn_verificar" class="btn bg-gradient-primary w-100 mt-4 mb-0">Verificar</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('assets/img/<?php echo rand(1, 7); ?>.jpg'); opacity: 0.8;">
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>


  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">

        <div class="col-lg-8 mb-4 mx-auto text-center">
          <p class="text-secondary me-xl-5 me-3 mb-sm-0 mb-2">
            <br>
            <?php

            switch (rand(1, 7)) {
              case (01):
                echo '“Si yo me callo, gritarían las piedras de los pueblos de América Latina, que están dispuestos a ser libres de todo colonialismo después de 500 años de coloniaje”.  ~ Hugo Chavez';
                break;
              case (02):
                echo '“La libertad del nuevo mundo, es la esperanza del universo”.  ~ Simón Bolívar';
                break;
              case (03):
                echo '"Estamos comenzando a mirar lo que el Padre Libertador imaginaba: Una gran región donde debe reinar la justicia, la igualdad y la libertad. Fórmula mágica para la vida de las naciones y la paz entre los pueblos”.  ~ Hugo Chavez';
                break;
              case (04):
                echo '“Más cuesta mantener el equilibrio de la libertad que soportar el peso de la tiranía”. ~ Simón Bolívar';
                break;
              case (05):
                echo '“Seguiremos batallando por la verdadera unidad e integración de nuestros pueblos, pero no es con el imperialismo que vamos a integrarnos. Bastante daño le hizo el imperio al proyecto de Bolívar”.  ~ Hugo Chavez';
                break;
              case (06):
                echo '“Como amo la libertad tengo sentimientos nobles y liberales; y si suelo ser severo, es solamente con aquellos que pretenden destruirnos”. ~ Simón Bolívar';
                break;
              case (07):
                echo '“Toda mi vida y por amor a un pueblo, la dedicaré hasta el último segundo de ella, para la lucha por la democracia y el respeto de los derechos humanos. Yo lo juro”.  ~ Hugo Chavez';
                break;
            }

            ?>
          </p>

        </div>

        <div class="col-lg-8 mx-auto text-center mb-4 mt-2">
          <a href="#" class="text-secondary me-xl-4 me-4">
            <i class="text-lg line icon-lock"></i>
          </a>
          <a href="#" class="text-secondary me-xl-4 me-4">
            <i class="text-lg line icon-shield"></i>
          </a>
          <a href="#" class="text-secondary me-xl-4 me-4">
            <i class="text-lg line icon-location-pin"></i>
          </a>

        </div>


      </div>
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            Copyright © <script>
              document.write(new Date().getFullYear())
            </script> Gloster III.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="vendors/sweetAlert2/sweetalert2.all.min.js"></script>

  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>



  <script>
    function validar() {
      let c = document.getElementById('cedula').value

      if (c == '') { return alertaNoticia('Vacio', 'Indique su numero de cedula', 'info'); }
      
      
      $.get("login/verificar_cedula.php", "c=" + c, function(data) {
        const result = JSON.parse(data)

        if (result.status == 'ok') {
          cargarInfo(result.institucion, c)
        } else {
          console.log(data)
          alertaNoticia('Rechazado', 'No se encontro el usuario', 'error');
        }
      });
    }
    document.getElementById('btn_verificar').addEventListener('click', validar)


    function cargarInfo(param_1, param_2) {
      let form = document.createElement('form');
      form.method = 'POST';
      form.action = 'seguir_data.php';

      let input1 = document.createElement('input');
      input1.type = 'hidden';
      input1.name = 'param_1';
      input1.value = param_1;

      let input2 = document.createElement('input');
      input2.type = 'hidden';
      input2.name = 'param_2';
      input2.value = param_2;

      form.appendChild(input1);
      form.appendChild(input2);

      document.body.appendChild(form);
      form.submit();
      

    }

    $(document).ready(function() {
      console.clear()
    });
  </script>
</body>

</html>