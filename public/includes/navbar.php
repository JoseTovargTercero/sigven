<?php

$Nombre = $_SESSION['nombre'];
$id = $_SESSION['id'];
if($_SESSION['nivel'] == 1){
  $userType = "<small> - admin</small>";
}else{
  $userType = "";
}


function queryCerrar(){
  global $conexion; 
  global $id; 
  $query = "SELECT * FROM log_usuarios WHERE id_user='$id' ORDER BY id DESC LIMIT 1";
  $search = $conexion->query($query);
  if ($search->num_rows > 0) {
      while ($fila = $search->fetch_assoc()) {
        $var1 = $fila["last_act"];
      }
  }
return $var1;
}


if(queryCerrar() - 900 > time()){
  define( 'PAGINA_INICIO', '../login/salir.php' );
  header( 'Location: '.PAGINA_INICIO );
}



function query(){
  $time = time();
  global $conexion; 
  global $id; 
  $query = "SELECT * FROM log_usuarios WHERE id_user='$id' ORDER BY id DESC LIMIT 1";
  $search = $conexion->query($query);
  if ($search->num_rows > 0) {
      while ($fila = $search->fetch_assoc()) {
        $var1 = $fila["fecha"]." - ".$fila["hora"];
        $last_id = $fila["id"];
      }
  }

  $update = "UPDATE log_usuarios SET last_act='$time' WHERE id='$last_id'";
  mysqli_query( $conexion, $update );

  
return $var1;
}





$navbar = '
<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Paginas</a></li>
        <li class="breadcrumb-item text-sm text-dark active index" aria-current="page"></li>
      </ol>
      <h6 class="font-weight-bolder mb-0 namePagina"></h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div class="input-group">
          <span class="input-group-text text-body">
          <i class="fas line icon-magnifier" aria-hidden="true"></i>
          </span>
          
          
          <input type="number" id="buscador" class="form-control form-noShadow" placeholder="Cedula a buscar...">
          
          
          <span class="input-group-text text-body" >
          <a id="link"  style="display: none; margin-bottom: -3px" href="" style="margin-top: 5px">
          <i class="fas line icon-arrow-right-circle" ></i>
          </a>
       

          </span>
         
    
      

        </div>
      </div>
      <ul class="navbar-nav  justify-content-end">


        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>
       <li class="nav-item px-3 d-flex align-items-center">
        <a href="javascript:;" class="nav-link text-body p-0">
          <i class="line icon-settings fixed-plugin-button-nav cursor-pointer"></i>
        </a>
      </li>
     
        <li class="nav-item dropdown pe-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="line icon-user cursor-pointer"></i>
           ';
           if($_SESSION['info'] == "passVence"){
            $navbar .= '   <i class="line icon-info noticia"></i>';

          }
          $navbar .= '
          </a>
          <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="perfil.php">
                <div class="d-flex py-1">
                <div class="avatar avatar-sm bg-gradient-primary  me-3  my-auto">
                <i class="line icon-user"></i>
               </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold">'.$_SESSION['nombre'].'</span> '.$userType.'
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      ';
                      $navbar .=  query();
                     
                      $navbar .= '

                    </p>
                  </div>
                </div>
              </a>
            </li>
          
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="../login/salir.php">
                <div class="d-flex py-1">
                  <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                   <i class="line icon-lock"></i>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Cerrar sesion actual
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      Salir
                    </p>
                  </div>
                </div>
              </a>
            </li>';

            if($_SESSION['info'] == "passVence"){
            
$navbar .= '
<li>
<a class="dropdown-item border-radius-md" href="perfil.php">
  <div class="d-flex py-1">
    <div class="avatar avatar-sm bg-gradient-info  me-3  my-auto">
     <i class="line icon-info"></i>
    </div>
    <div class="d-flex flex-column justify-content-center">
      <h6 class="text-sm font-weight-normal mb-1">
        Actualizar contraseña
      </h6>
      <p class="text-xs text-secondary mb-0">
        Vencerá pronto
      </p>
    </div>
  </div>
</a>
</li>';
            }

$navbar .= '
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<script>
$(document).ready(function() {
function actualizarLink() {
  var valor = $("#buscador").val()
    if (valor != "") {
       $("#link").attr("href", "resultadoBusqueda.php?cedula=" + valor);
       $("#link").show(500, "swing");

      }else{
      $("#link").attr("href", "");
      $("#link").hide(500, "swing");
			
    }
}
$("#buscador").keyup(actualizarLink);
});
</script>


';
?>