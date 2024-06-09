

<?php
include('../../configurar/configuracion.php');


if(isset($_POST['rep'])){


  function getDatosCne($cedula){
    $matriz = array();
    $cuenta = 0;
    $ar = fopen("http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad=V&cedula=" . $cedula . "", "r") or
        die("No se pudo abrir el archivo");

    while (!feof($ar)) {
        $linea = fgets($ar);

        if ($cuenta == 1) {
            $matriz[] = $linea;
            $cuenta = 0;
        }

        if (
            preg_match("/Cédula:/", $linea) ||
            preg_match("/Nombre:/", $linea) ||
            preg_match("/Estado:/", $linea) ||
            preg_match("/Municipio:/", $linea) ||
            preg_match("/Parroquia:/", $linea) ||
            preg_match("/Centro:/", $linea) ||
            preg_match("/Dirección:/", $linea)
        ) {
            $cuenta = 1;
        }
    }
    fclose($ar);


    if (sizeof($matriz) > 4) {
        $ci = strip_tags($matriz[0]);
        $nombre = strip_tags($matriz[1]);
        $estado = strip_tags($matriz[2]);
        $municipio = strip_tags($matriz[3]);
        $parroquia = strip_tags($matriz[4]);
        $centro = strip_tags($matriz[5]);

        // La cadena sin la V-
        $ci = str_replace("V-", "", $ci);

        return array(trim($nombre), trim($centro));
    }
}

$resultCne = getDatosCne($_POST['rep']);

$nombre = $resultCne[0];
$centro = $resultCne[1];
$q=$conexion->real_escape_string($_POST['rep']);
    
$query="SELECT * FROM rep28 WHERE cedula=$q LIMIT 1";
$buscarAlumnos=$conexion->query($query);
if ($buscarAlumnos->num_rows > 0){
	while($filaAlumnos= $buscarAlumnos->fetch_assoc())	{
		
        $sexo = $filaAlumnos['sexo'];
        switch($sexo){
                case("M"):
                $sexocompleto = "Masculino";
                break;
                case("F"):
                $sexocompleto = "Femenino";
                break;
        } 
                
        $firmamaduro = $filaAlumnos['firma_maduro'];
        $fechaPro = explode("/", $filaAlumnos['fecha_nac']);
        
        if(strlen($fechaPro[0]) == 1){
            $dia = "0".$fechaPro[0];
        }else{
            $dia = $fechaPro[0];
        }
        if(strlen($fechaPro[1]) == 1){
            $mes = "0".$fechaPro[1];
        }else{
            $mes = $fechaPro[1];
        }

        $fechaDeNa = $dia."/".$mes."/".$fechaPro[2]; 
       
        if($firmamaduro =="SI"){
        
          $alertaFirma = '<span class="alertaFirma">
        <i class="line icon-info" style="color: violet"></i>
        </span>';
        }


        $tabla.='
      <div class="col-xl-12">
        <input type="hidden" name="firma" id="firma" value="'.$firmamaduro.'">
        <label>Nombre</label>
        <span class="col-xl-12">
          <input style="    margin-bottom: 16px;" value="'.$nombre.'" readonly name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">
      '.$alertaFirma.'
        </div>
        <label>Fecha de Nacimiento</label>

        <div class="col-xl-12">
          <input style="    margin-bottom: 16px;"  value="'.$fechaDeNa.'" name="nac" readonly id="nac" class="form-control" required="required">

        </div>


        <label>Sexo</label>
        <div class="col-xl-12"  >
          <select class="form-control"  style=" margin-bottom: 16px;" id="sexo" name="sexo" required="required">
            <option value="'.$sexo.'">'.$sexocompleto.'</option>
          </select>
        </div>
  
  



        <label>Cetro de Votacion</label>
        <div class="col-xl-12">
          <input style="margin-bottom: 16px;"  value="'.$centro.'" readonly name="cv" id="cv" class="form-control" required="required" placeholder="Cento de Votación">
        </div>

        </div>';
	}
}else{
    $tabla= ' 
    <div class="col-xl-12">

    <label>Nombre</label>
    <div class="col-xl-12">
    <input style="    margin-bottom: 16px;" value="'.$nombre.'" readonly name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">

    </div>

    <label>Fecha de Nacimiento</label>
    <div class="col-xl-12">
      <input type="date" style=" margin-bottom: 16px;" name="nac" id="nac" class="form-control" required="required"  placeholder="Fecha de Nacimiento">
    </div>

    
    <label>Sexo</label>
      <div class="col-xl-12"  >
        <select class="form-control"  style=" margin-bottom: 16px;" id="sexo" name="sexo" required="required">
          <option></option>
          <option value="M">Masculino</option>
          <option value="F">Femenino</option>
        </select>
      </div>




  
    
    <label>Centro de Votación</label>
  <div class="col-xl-12">
  <input style="margin-bottom: 16px;"  value="'.$centro.'" readonly name="cv" id="cv" class="form-control" required="required" placeholder="Cento de Votación">

  </div>';
}



}else{
    
            
		$tabla=' 
        <div class="col-xl-12">

        <label>Nombre</label>
        <div class="col-xl-12">
        <input style="    margin-bottom: 16px;" value="'.$nombre.'" readonly name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">
        </div>
        <label>Fecha de Nacimiento</label>

        <div class="col-xl-12">
          <input style=" margin-bottom: 16px;" name="nac" id="nac" class="form-control" required="required"  placeholder="Fecha de Nacimiento">
        </div>


        <label>Sexo</label>
        <div class="col-xl-12">
          <input style=" margin-bottom: 16px;" name="sexo" id="sexo" placeholder="Sexo" class="form-control" required="required">
        </div>


        <label>Cetro de Votacion</label>
        <div class="col-xl-12">
          <input style="    margin-bottom: 16px;" name="cv" id="cv" class="form-control" required="required" placeholder="Cento de Votación">
        </div>

        </div>';
	}


echo $tabla;
?>

