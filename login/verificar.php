<?php
	
	include('../configurar/configuracion.php');
	
	$doc = $_POST['login'];
	$doc = addslashes($doc);
	$doc = strip_tags($doc);

	$contrasena = $_POST['password'];
	$contrasena = addslashes($contrasena);
	$contrasena = strip_tags($contrasena);





	try {
		$sql = "SELECT *
		FROM usuarios WHERE cedula= ?";
	  
		$resultado = $base->prepare($sql);
		$resultado->execute(array($doc));
	  
		if ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {

			$ultimoCambio = $registro['last_change'];
			$passAlmacenada = $registro['contrasena'];
			$idUsuario = $registro['id'];
			

			if (password_verify($contrasena, $passAlmacenada)) {
				$_SESSION['nombre']=$registro['nombre'];
				$_SESSION['nivel']=$registro['nivel'];
				$_SESSION['id']=$registro['id'];
				$_SESSION['apellido']=$registro['apellido'];
				$_SESSION['entidad']=$registro['entidad'];
				$_SESSION['reportes']=$registro['reportes'];
				$_SESSION['consulta']=$registro['consulta'];
				$_SESSION['respaldos']=$registro['respaldos'];

				$id = $_SESSION['id'];
				$nombre = $_SESSION['nombre'];
				$fecha = time();
				$nivel = $_SESSION['nivel'];


				if($id != '500'){ 
					$insert = $conexion->query("INSERT INTO log_usuarios (id_user, usuario, fecha) VALUES ('$id','$nombre','$fecha')"); 
				}

				if($_SESSION['nivel'] == '1'){
					define('PAGINA_INICIO','../public/index.php');
					header('Location: '.PAGINA_INICIO);
				}else{
					define('PAGINA_INICIO','../public/perfil.php');
					header('Location: '.PAGINA_INICIO);
				}
				exit();

			}else {
				if(md5($contrasena) == $passAlmacenada && $ultimoCambio == '0'){
						$_SESSION['noticia'] = "Accion necesaria/La contraseña necesita ser actualizada/info/5000";
						define('PAGINA_INICIO','../recuperar.php');
						header('Location: '.PAGINA_INICIO);
						exit();
					}else{
						$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
						define('PAGINA_INICIO','../index.php');
						header('Location: '.PAGINA_INICIO);
						exit();
					}
			exit();

			}


		}
		$resultado->closeCursor();
	  
	  
	  
	  } catch(Exception $e) {
	   // die('Error: ' . $e->GetMessage());
	  }













	  $_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
	  define('PAGINA_INICIO','../index.php');
	  header('Location: '.PAGINA_INICIO);













/*
    $query = "SELECT * FROM usuarios WHERE cedula='$doc' AND activado='0' LIMIT 1";
    $search = $conexion->query($query);
    if ($search->num_rows > 0) {
        while ($fila = $search->fetch_assoc()) {

			$ultimoCambio = $fila['last_change'];
			$passAlmacenada = $fila['contrasena'];
			$idUsuario = $fila['id'];


			if (password_verify($contrasena, $passAlmacenada)) {
				$_SESSION['nombre']=$fila['nombre'];
				$_SESSION['nivel']=$fila['nivel'];
				$_SESSION['id']=$fila['id'];
				$_SESSION['apellido']=$fila['apellido'];

				$id = $_SESSION['id'];
				$nombre = $_SESSION['nombre'];
				$fecha = date ('d/m/Y');
				$hora =  date ('h:i A');
				$nivel = $_SESSION['nivel'];


				if($id != '500'){ 
					$insert = $conexion->query("INSERT INTO log_usuarios (id_user, usuario, fecha, hora, nivel) VALUES ('$id','$nombre','$fecha','$hora','$nivel')"); 
				}

			

				if($_SESSION['nivel'] == '1'){
					define('PAGINA_INICIO','../public/index.php');
					header('Location: '.PAGINA_INICIO);
				}else{
					define('PAGINA_INICIO','../public/perfil.php');
					header('Location: '.PAGINA_INICIO);
				}

				}else {

					
					
					if(md5($contrasena) == $passAlmacenada && $ultimoCambio == '0'){
						$_SESSION['noticia'] = "Accion necesaria/La contraseña necesita ser actualizada/info/5000";
						define('PAGINA_INICIO','../recuperar.php');
						header('Location: '.PAGINA_INICIO);
					}else{
						$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
						define('PAGINA_INICIO','../index.php');
						header('Location: '.PAGINA_INICIO);
					}
					



				}

		}


	}else{
		$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
			define('PAGINA_INICIO','../index.php');
			header('Location: '.PAGINA_INICIO);
	}
*/