<?php
include('../../configurar/configuracion.php');

$query=$conexion->query("select * from continente where mcp_id=$_GET[municipio_id]");
$states = array();
while($r=$query->fetch_object()){ $states[]=$r; }
if(count($states)>0){
print "<option value=''>SELECCIONE</option>";
foreach ($states as $s) {
	print "<option value='$s->id'>$s->name2</option>";
}
}else{
print "<option>NO HAY DATOS</option>";
}
?>