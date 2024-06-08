<?php
include('../../configurar/configuracion.php');
$query=$conexion->query("select * from instituciones where tipo=$_GET[tipo]");
$states = array();
while($r=$query->fetch_object()){ $states[]=$r; }
if(count($states)>0){
print "<option value=''>SELECCIONE</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->nombre</option>";
}
}else{
print "<option>NO HAY DATOS</option>";
}
?>

