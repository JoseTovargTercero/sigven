<?php
	
	include('configuracion.php');
	$idUser = $_SESSION['id'];


	if ($_SESSION['nivel'] == 1 || $_SESSION['respaldos'] == '1') {

		exec('C:/xampp/mysql/bin/mysqldump.exe --user=root --password="" --host=localhost sigven > file.sql');

		$name = "sigven_".date("Y-m-d H-i-s").".sql";
	
		if(rename('file.sql',"respaldos/$name")){
			header("Location: respaldos/$name");
		}else{
			echo 'Error de ejecucion';
		}	
	
	}








	?>