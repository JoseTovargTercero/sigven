

<?php
include('../../configurar/configuracion.php');


if(isset($_POST['rep'])){

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
          <input style="    margin-bottom: 16px;" value="'.$filaAlumnos['nombre'].'" readonly name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">
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
  
  

';
	}
}else{
    $tabla= ' 
    <div class="col-xl-12">

    <label>Nombre</label>
    <div class="col-xl-12">
      <input style="    margin-bottom: 16px;" name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">
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
      </div>';
}



}else{
    
            
		$tabla=' 
        <div class="col-xl-12">

        <label>Nombre</label>
        <div class="col-xl-12">
          <input style="    margin-bottom: 16px;" name="nombre" id="nombre" class="form-control" required="required" placeholder="Nombre y Apellido">
        </div>
        <label>Fecha de Nacimiento</label>

        <div class="col-xl-12">
          <input style=" margin-bottom: 16px;" name="nac" id="nac" class="form-control" required="required"  placeholder="Fecha de Nacimiento">
        </div>


        <label>Sexo</label>
        <div class="col-xl-12">
          <input style=" margin-bottom: 16px;" name="sexo" id="sexo" placeholder="Sexo" class="form-control" required="required">
        </div>';
	}


echo $tabla;
?>

