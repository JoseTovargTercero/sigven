<?php
	include('configuracion.php');
	$id = $_SESSION['id'];
	
	$password11 = $_POST['password11'];
	$password211 = $_POST['password211'];

	$contrasena = $_POST['password'];
	$contrasena = addslashes($contrasena);
	$contrasena = strip_tags($contrasena);
	$query = "SELECT * FROM usuarios WHERE id='$id'";
    $search = $conexion->query($query);
    if ($search->num_rows > 0) {
		while ($fila = $search->fetch_assoc()) {

		$passAlmacenada = $fila['contrasena'];

		if (password_verify($contrasena, $passAlmacenada)) {

			if($password11 === $password211){

				$passEncrypted = password_hash($_POST['password11'], PASSWORD_BCRYPT);

				$update = "UPDATE usuarios SET contrasena='$passEncrypted' WHERE id='$id'";
				$result = mysqli_query($conexion, $update );
	
				if ( !$result ) {
					$_SESSION['noticia'] = "Algo no ha ido bien/Error inesperado/error/5000";
				}else{
					$_SESSION['noticia'] = "¡Perfecto!/Tarea realizada correctamente/success/3000";
				}
	
			}else{
				$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
			}

		}else{
			$_SESSION['noticia'] = "Algo no ha ido bien/Las contraseña no coinciden/error/5000";
		}

	
	}


	}else{
		$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
	}

	define( 'PAGINA_RETORNO', '../public/perfil.php' );
	header( 'Location: '.PAGINA_RETORNO );

?>