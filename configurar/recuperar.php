<?php
	include('configuracion.php');
	
	
$id = $_POST['cedula'];
$password11 = $_POST['newPass'];
$password211 = $_POST['repeatPass'];


$contrasena = md5($_POST['oldPass']);
$contrasena = addslashes($contrasena);
$contrasena = strip_tags($contrasena);


if ($password11 != $password211) {
	$_SESSION['noticia'] = "Algo no ha ido bien/Las contraseñas no coinciden/error/5000";
	define( 'PAGINA_RETORNO', '../recuperar.php' );
	header( 'Location: '.PAGINA_RETORNO );
}else {

try {
  $sql = "SELECT nombre
  FROM usuarios WHERE cedula= ? and contrasena= ?";

  $resultado = $base->prepare($sql);
  $resultado->execute(array($id,$contrasena));

  if ($registro = $resultado->fetch(PDO::FETCH_ASSOC)) {
	$var = 1;
  }
  $resultado->closeCursor();



} catch(Exception $e) {
 // die('Error: ' . $e->GetMessage());
}
$passEncrypted = password_hash($password11, PASSWORD_BCRYPT);




if ($var != 1) {
	$_SESSION['noticia'] = "Algo no ha ido bien/Verifique sus credenciales y vuelva a intentarlo/error/5000";
	define( 'PAGINA_RETORNO', '../recuperar.php' );
	header( 'Location: '.PAGINA_RETORNO );
  }else {
try {
  
	$sql2 = "UPDATE usuarios SET contrasena= ?, last_change='1' WHERE cedula= ?";
  
	$resultado2 = $base2->prepare($sql2);
	$resultado2->execute(array($passEncrypted, $id));
  
	$resultado2->closeCursor();
  
  } catch(Exception $e2) {
  }

  $_SESSION['noticia'] = "¡Perfecto!/Tarea realizada correctamente/success/5000";
				define( 'PAGINA_RETORNO', '../index.php' );
				header( 'Location: '.PAGINA_RETORNO );

  }
}

?>