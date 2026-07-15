<?php

require_once __DIR__ . '/../elecciones/configuracion/config.php';

define('SIGVEN_USER', $_ENV['SIGVEN_USER']);
define('SIGVEN_PASS', $_ENV['SIGVEN_PASS']);
define('SIGVEN_HOST', $_ENV['SIGVEN_HOST']);
define('SIGVEN_DB', $_ENV['SIGVEN_DB']);
define('SIGVEN_CHARSET', $_ENV['SIGVEN_CHARSET'] ?? 'utf8');

$conexion = mysqli_connect(SIGVEN_HOST, SIGVEN_USER, SIGVEN_PASS, SIGVEN_DB);
$conexion->set_charset(SIGVEN_CHARSET);

$base = new PDO('mysql:host=' . SIGVEN_HOST . ';dbname=' . SIGVEN_DB, SIGVEN_USER, SIGVEN_PASS);
$base->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$base->exec("SET CHARACTER SET " . SIGVEN_CHARSET);

date_default_timezone_set('America/Manaus');
session_start();
