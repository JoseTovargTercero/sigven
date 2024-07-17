<?php


if ($_SERVER['SERVER_NAME'] == 'localhost') {
	$user = 'root';
	$pass = '';
} else {
	$user = 'user_sigven';
	$pass = '+ij*tK&[JH$,';
}






$conexion = mysqli_connect("localhost", "$user", "$pass", "sigven");
$conexion->set_charset('utf8');
// CONEXION EN MYSQLI //////


$base = new PDO('mysql:host=localhost; dbname=sigven', "$user", "$pass");
$base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$base->exec("SET CHARACTER SET utf8");
// CONEXION EN PDO //////


date_default_timezone_set('America/Manaus');
session_start();
//error_reporting(0);
