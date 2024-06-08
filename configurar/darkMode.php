<?php
include('configuracion.php');
$user = $_SESSION['id'];
$id = $_GET['id'];

$update = "UPDATE usuarios SET darkMode='$id' WHERE id='$user'";
$result = mysqli_query( $conexion, $update );

?>
	
	<script>
		window.history.go(-1)
	</script>