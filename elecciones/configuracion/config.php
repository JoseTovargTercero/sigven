<?php



if ($_SERVER['SERVER_NAME'] == 'localhost') {
	define('URL', 'http://localhost/elecciones/');
    define('USER', 'root');
    define('PASSWORD', "");
} else {
	define('URL', 'https://sigven.net/elecciones/');
    define('USER', 'tercero_elecciones');
    define('PASSWORD', "KsxyhWrb[Oex");

}

define('HOST', 'localhost');
define('DB', 'eleccionespresidenciales');
define('CHARSET', 'utf8mb4');

?>