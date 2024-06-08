
<?php 
session_start();
$idUser = $_SESSION['id'];
$nivel = $_SESSION['nivel'];
if($idUser == ""){
    define('PAGINA_INICIO', '../index.php');
    header('Location: ' . PAGINA_INICIO);
}else{

if($_SESSION['entidad'] == '0' && $nivel != '1'){
  $entidadDisplay = "display: none;";
}else{
  $entidadDisplay = "";
}
if($_SESSION['reportes'] == '0' && $nivel != '1'){
  $reportesDisplay = "display: none;";
}else{
  $reportesDisplay = "";
}
if($_SESSION['consulta'] == '0' && $nivel != '1'){
  $consultaDisplay = "display: none;";
}else{
  $consultaDisplay = "";
}



if($_SESSION['nivel'] != 1){
  $userDisplay = "display: none;";
  $inicioDisplay = "display: none;";
}


?>

<aside class="sidenav sidebar navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main" >
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href="#" target="_blank">
      <img src="../assets/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold">Psuv Amazonas</span>
    </a>
  </div>
  <hr class="horizontal dark mt-0">
  <div class="nano-content collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main" style="list-style: none !important">
    <ul class="navbar-nav" >
      <li class="nav-item dropdown" style="<?php echo $inicioDisplay ?>">
        <a id="inicio" class="nav-link" href="../public/index.php">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-pie-chart"></i>
          </div>
          <span class="nav-link-text ms-1">Inicio</span>
        </a>
      </li>
      <li class="nav-item dropdown" style="<?php echo $entidadDisplay ?>">
        
        <a id="entidad" class="nav-link sidebar-sub-toggle">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-location-pin"></i>
          </div>
         Entidad</a>
         <ul style="list-style: none !important;">
          <li class="subItems"><a href="registro_Entidades_UBCH.php">Ubch</a></li>
          <li class="subItems"><a href="registro_Entidades_Comunidad.php">Consejo Comunal</a></li>
        </ul>
      </li>
      
      <li class="nav-item dropdown">
        
        <a id="registros" class="nav-link sidebar-sub-toggle">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-note"></i>
          </div>
         Registros</a>
         <ul style="list-style: none !important; ">
          <li class="subItems"><a href="registro_Raas.php">Estructura RAAS</a></li> 
          <li class="subItems"><a href="registro_Ubch.php">Estructura UBCH</a></li>
          <li class="subItems"><a href="registro_Patrullas.php">Patrullas Territoriales</a></li>
          <li class="subItems"><a href="registro_Padron.php">Padron Electoral</a></li>
          <li class="subLine"></li>
          <li class="subItems"><a href="registro_Institucion.php">Institución</a></li>
          <li class="subItems"><a href="registro_Padron_Institucional.php">Padron Institucional</a></li>
        </ul>
      </li>
 <li class="nav-item dropdown" style="<?php echo $userDisplay ?>">
  
        <a id="usuarios" class="nav-link sidebar-sub-toggle">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-people"></i>
          </div>
          Usuarios</a>
          <ul style="list-style: none !important;">
            <li class="subItems"><a href="registro_usuarios.php">Nuevo Usuario</a></li>
          <li class="subItems"><a href="permisos.php">Gestion de permisos</a></li>
        </ul>
      </li>
      
      

      <li class="nav-item dropdown" style="<?php echo $consultaDisplay ?>">

        <a id="consultas" class="nav-link sidebar-sub-toggle">
        <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-book-open"></i>
          </div>
          Consultas</a>
          <ul style="list-style: none !important;">
          <li class="subItems"><a href="consultas_ubch.php">Raas Comunal</a></li>
          <li class="subItems"><a href="consultas_padron_institucional.php">Instituciones</a></li>
        </ul>
      </li>
      
      <li class="nav-item dropdown" style="<?php echo $reportesDisplay ?>">
        <a id="reportes" class="nav-link" href="reportes.php">
          <div class="icon icon-sm shadow border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="line icon-printer"></i>
          </div>
          <span class="nav-link-text ms-1">Reportes</span>
        </a>
      </li>

      
    </ul>
  </div>
  
  
</aside>
<?php 
}

?>