

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
  
  



        <label>Cetro de Votacion</label>
        <div class="col-xl-12">
          <input style="    margin-bottom: 16px;"  value="'.$filaAlumnos['cv'].'" readonly name="cv" id="cv" class="form-control" required="required" placeholder="Cento de Votación">
        </div>

        </div>';
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
      </div>




  
    
    <label>Centro de Votación</label>
  <div class="col-xl-12">
    <select  class="form-control"   style=" margin-bottom: 16px;" name="cv" id="cv" required="required">
    <option value="">SELECCIONE</option>


    <optgroup label="FERNANDO GIRON TOVAR">
        <option value="UNIDAD EDUCATIVA AMAZONAS">UNIDAD EDUCATIVA AMAZONAS</option>
        <option value="ESCUELA BASICA MONSE&Ntilde;OR ENRIQUE DE FERRARI">ESCUELA BASICA MONSE&Ntilde;OR ENRIQUE DE FERRARI</option>
        <option value="CENTRO EDUCATIVO INTEGRAL BOLIVARIANO LUISA CACERES DE ARISMENDI">CENTRO EDUCATIVO INTEGRAL BOLIVARIANO LUISA CACERES DE ARISMENDI</option>
        <option value="PREESCOLAR 5 DE JULIO">PREESCOLAR 5 DE JULIO</option>
        <option value="ESCUELA BASICA SIMON BOLIVAR">ESCUELA BASICA SIMON BOLIVAR</option>
        <option value="ESCUELA BASICA ANDRES ELOY BLANCO">ESCUELA BASICA ANDRES ELOY BLANCO</option>
        <option value="ESCUELA BASICA CACIQUE ARAMARE">ESCUELA BASICA CACIQUE ARAMARE</option>
        <option value="CENTRO MOVIL CASIQUIARE">CENTRO MOVIL CASIQUIARE</option>
        <option value="ESCUELA GRADUADA GABRIELA MISTRAL">ESCUELA GRADUADA GABRIELA MISTRAL</option>
        <option value="ESCUELA BASICA TACHIRA">ESCUELA BASICA TACHIRA</option>
        <option value="SIMONCITO PIEDRA DE LA TORTUGA">SIMONCITO PIEDRA DE LA TORTUGA</option>
        <option value="CASA DE LOS NI&Ntilde;OS WANADI CENTRO RECREATIVO">CASA DE LOS NI&Ntilde;OS WANADI CENTRO RECREATIVO</option>
        <option value="ESCUELA BASICA MENCA DE LEONI">ESCUELA BASICA MENCA DE LEONI</option>
        <option value="PREESCOLAR LOS LOROS">PREESCOLAR LOS LOROS</option>
        <option value="CENTRO MOVIL ALTO PARIMA">CENTRO MOVIL ALTO PARIMA</option>
        <option value="CENTRO EDUCATIVO INTEGRAL BOLIVARIANO RIO VENTUARI">CENTRO EDUCATIVO INTEGRAL BOLIVARIANO RIO VENTUARI</option>
        <option value="CENTRO MOVIL MAISANTA">CENTRO MOVIL MAISANTA</option>
        <option value="PRESCOLAR DIA INTERNACIONAL DEL NI&Ntilde;O">PRESCOLAR DIA INTERNACIONAL DEL NI&Ntilde;O</option>
        <option value="ESCUELA DE EDUCACION INICIAL AMAZONAS LA ARMADA">ESCUELA DE EDUCACION INICIAL AMAZONAS LA ARMADA</option>
        <option value="LICEO SANTIAGO AGUERREVERE">LICEO SANTIAGO AGUERREVERE</option>
    </optgroup>

    <optgroup label="LUIS ALBERTO GOMEZ">
        <option value="ESCUELA BASICA DON ROMULO BETANCOURT">ESCUELA BASICA DON ROMULO BETANCOURT</option>
        <option value="ESCUELA BASICA FELIX SOLANO">ESCUELA BASICA FELIX SOLANO</option>
        <option value="ESCUELA BASICA SIMON RODRIGUEZ">ESCUELA BASICA SIMON RODRIGUEZ</option>
        <option value="UNIDAD EDUCATIVA BOLIVARIANA LA INDEPENDENCIA">UNIDAD EDUCATIVA BOLIVARIANA LA INDEPENDENCIA</option>
        <option value="PRESCOLAR ROMULO BETANCOURT">PRESCOLAR ROMULO BETANCOURT</option>
        <option value="UNIDAD EDUCATIVA BOLIVARIANA LAS MANACAS">UNIDAD EDUCATIVA BOLIVARIANA LAS MANACAS</option>
        <option value="CENTRO DE EDUCACION INICIAL LOS TUNCANCITOS">CENTRO DE EDUCACION INICIAL LOS TUNCANCITOS</option>
        <option value="BAJO EL ESCONDIDO 1">BAJO EL ESCONDIDO 1</option>
        <option value="UNIDAD EDUCATIVA LOS RAUDADE">UNIDAD EDUCATIVA LOS RAUDADES</option>
        <option value="VALLE VERDE">VALLE VERDE</option>
        <option value="SIMON RODRIGUEZ EZEQUIEL ZAMORA">SIMON RODRIGUEZ EZEQUIEL ZAMORA</option>
        <option value="PREESCOLAR MADRE TERESA DE CALCUTA">PREESCOLAR MADRE TERESA DE CALCUTA</option>
        <option value="ESCUELA LIBERTADOR">ESCUELA LIBERTADOR</option>
        <option value="BRISAS DEL AEROPUERTO">BRISAS DEL AEROPUERTO</option>
        <option value="UNIVERSIDAD PEDAGOGICA EXPERIMENTAL LIBERTADOR">UNIVERSIDAD PEDAGOGICA EXPERIMENTAL LIBERTADOR</option>
        <option value="CENTRO MOVIL EL MO&Ntilde;ITO">CENTRO MOVIL EL MO&Ntilde;ITO</option>
        <option value="CENTRO MOVIL RUIZ PINEDA">CENTRO MOVIL RUIZ PINEDA</option>
        <option value="PREESCOLAR CENTRO DE EDUCACION INICIAL CURUMI">PREESCOLAR CENTRO DE EDUCACION INICIAL CURUMI</option>
        <option value="UNIDAD EDUCATIVA SOR ANA EMILIA MORENO">UNIDAD EDUCATIVA SOR ANA EMILIA MORENO</option>
        <option value="PREESCOLAR TAMANACO">PREESCOLAR TAMANACO</option>
        <option value="ESCUELA BASICA JUAN IVIRMA CASTILLO">ESCUELA BASICA JUAN IVIRMA CASTILLO</option>
        <option value="PREESCOLAR SIMON BOLIVAR">PREESCOLAR SIMON BOLIVAR</option>
        <option value="UNIDAD EDUCATIVA BOLIVARIANA AUTANA">UNIDAD EDUCATIVA BOLIVARIANA AUTANA</option>
        <option value="LICEO MARAHUACA">LICEO MARAHUACA</option>
        <option value="UNIDAD BASICA COLEGIO PADRE MAYANET">UNIDAD BASICA COLEGIO PADRE MAYANET</option>
        <option value="CENTRO EDUCATIVO INTEGRAL BOLIVARIANO SHAMANAVITH">CENTRO EDUCATIVO INTEGRAL BOLIVARIANO SHAMANAVITH</option>
        <option value="LA ESPERANZA">LA ESPERANZA</option>
        <option value="PREESCOLAR CERRO PERICO">PREESCOLAR CERRO PERICO</option>
        <option value="LICEO BOLIVARIANO CECILIO ACOSTA">LICEO BOLIVARIANO CECILIO ACOSTA</option>
        <option value="MIRADOR MONTE BELLO">MIRADOR MONTE BELLO</option>
        <option value="AMBULATORIO CASA INDIGENA">AMBULATORIO CASA INDIGENA</option>
        <option value="CENTRO EDUCATIVO INTEGRAL BOLIVARIANO AYACUCHO">CENTRO EDUCATIVO INTEGRAL BOLIVARIANO AYACUCHO</option>
        <option value="CENTRO MOVIL SANTA ROSA">CENTRO MOVIL SANTA ROSA</option>
        <option value="CENTRO MOVIL UPATA">CENTRO MOVIL UPATA</option>
        <option value="UPATA">UPATA</option>
    </optgroup>


    <optgroup label="PARHUEÑA">
        <option value="CENTRO MOVIL PROVINCIA">CENTRO MOVIL PROVINCIAL</option>
        <option value="UNIDAD EDUCATIVA MARIA TERESA DEL TORO">UNIDAD EDUCATIVA MARIA TERESA DEL TORO</option>
        <option value="OJO DE AGUA">OJO DE AGUA</option>
        <option value="CENTRO MOVIL GALIPERO">CENTRO MOVIL GALIPERO</option>
        <option value="ESCUELA PAYARAIMA">ESCUELA PAYARAIMA</option>
        <option value="PALMAR DE GALIPERO">PALMAR DE GALIPERO</option>
        <option value="ESCUELA SANTIAGO AGUERREVERE">ESCUELA SANTIAGO AGUERREVERE</option>
        <option value="UNIDAD EDUCATIVA INTEGRAL BOLIVARIANA FELIX RAMON RIVAS">UNIDAD EDUCATIVA INTEGRAL BOLIVARIANA FELIX RAMON RIVAS</option>
        <option value="CENTRO MOVIL EL CEJAL">CENTRO MOVIL EL CEJAL</option>
        <option value="CENTRO MOVIL TOPOCHO">CENTRO MOVIL TOPOCHO</option>
        <option value="CEJALITO">CEJALITO</option>
        <option value="SAN CARLOS DE PARHUEÑA">SAN CARLOS DE PARHUEÑA</option>
        <option value="ESCUELA TECNICA ROBINSONIANA Y ZAMORANA EMILIO ARVELO">ESCUELA TECNICA ROBINSONIANA Y ZAMORANA EMILIO ARVELO</option>
        <option value="CENTRO MOVIL BAMBU LUCERA">CENTRO MOVIL BAMBU LUCERA</option>
        <option value="CENTRO MOVIL POZON DE BABILLA">CENTRO MOVIL POZON DE BABILLA</option>
        <option value="CENTRO MOVIL PAVONI">CENTRO MOVIL PAVONI</option>
        <option value="CENTRO MOVIL BETANIA">CENTRO MOVIL BETANIA</option>
        <option value="ESCUELA SAMUEL ROBINSON">ESCUELA SAMUEL ROBINSON</option>
    </optgroup>

    <optgroup label="PLATANILLAL">
        <option value="SCUELA BASICA DANIEL NAVEA">SCUELA BASICA DANIEL NAVEA</option>
        <option value="PRE ESCOLAR DANIEL NAVEA">PRE ESCOLAR DANIEL NAVEA</option>
        <option value="CENTRO MOVIL LA REFORMA">CENTRO MOVIL LA REFORMA</option>
        <option value="CENTRO MOVIL PINTAO">CENTRO MOVIL PINTAO</option>
        <option value="CENTRO MOVIL RUEDA">CENTRO MOVIL RUEDA</option>
        <option value="CUCURITAL LA ROCA">CUCURITAL LA ROCA</option>
        <option value="SAN ANTONIO">SAN ANTONIO</option>
        <option value="SAN GABRIEL">SAN GABRIEL</option>
        <option value="ESCUELA BASICA EL LIBERTADOR">ESCUELA BASICA EL LIBERTADOR</option>
        <option value="CENTRO MOVIL GAVILAN">CENTRO MOVIL GAVILAN</option>
        <option value="CENTRO MOVIL SAN PEDRO DE CATANIAPO">CENTRO MOVIL SAN PEDRO DE CATANIAPO</option>
        <option value="CENTRO MOVIL SAN PABLO DE CATANIAPO">CENTRO MOVIL SAN PABLO DE CATANIAPO</option>
        <option value="CENTRO MOVIL PLATANILLAL">CENTRO MOVIL PLATANILLAL</option>
        <option value="CENTRO MOVIL PARIA GRANDE">CENTRO MOVIL PARIA GRANDE</option>
        <option value="CENTRO MOVIL DE AGUA BLANCA">CENTRO MOVIL DE AGUA BLANCA</option>
        <option value="CENTRO MOVIL SABANA DE TIGRE">CENTRO MOVIL SABANA DE TIGRE</option>
        <option value="PARIA GRANDE">PARIA GRANDE</option>
        <option value="ESCUELA BASICA PADRE LUIS ROTTMAYER">ESCUELA BASICA PADRE LUIS ROTTMAYER</option>
    </optgroup>

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
        </div>


        <label>Cetro de Votacion</label>
        <div class="col-xl-12">
          <input style="    margin-bottom: 16px;" name="cv" id="cv" class="form-control" required="required" placeholder="Cento de Votación">
        </div>

        </div>';
	}


echo $tabla;
?>

