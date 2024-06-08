<?php
	$conexion=mysqli_connect("localhost","root","","sigven");
	$conexion->set_charset('utf8'); 
	// CONEXION EN MYSQLI //////
	
	
	$base=new PDO('mysql:host=localhost; dbname=sigven', 'root', '');
	$base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$base->exec("SET CHARACTER SET utf8");
	// CONEXION EN PDO //////
	
	
	date_default_timezone_set('America/Manaus');
	session_start();
	error_reporting(0);


/*
	if ($_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 2) {
		define('PAGINA_INICIO', '../index.php');
		header('Location: ' . PAGINA_INICIO);
		exit();
	}*/
?>