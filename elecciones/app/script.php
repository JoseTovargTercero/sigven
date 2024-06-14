<?php 
require('../configuracion/conexion.php');
require('../configuracion/datos_cne.php');


$unox10 = array();

$stmt = mysqli_prepare($conexion_app, "SELECT id, cdula FROM `unox10`");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $unox10["$row[cdula]"] = $row['id'];
    }
}
$stmt->close();

$stmt = mysqli_prepare($conexion_app, "SELECT voto, id, cedula FROM `flujo_electoral` ");
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];

        if (@$unox10["$row[cedula]"]) {
            $id1x10 = $unox10["$row[cedula]"];
            if ($row['voto'] != 'OP') {
                $stmt2 = $conexion_app->prepare("UPDATE `flujo_electoral` SET `voto`='VD', `unodiez`='$id1x10' WHERE id=?");
                $stmt2->bind_param("s", $id);
                $stmt2->execute();
                $stmt2 -> close();
            }else {
                $stmt2 = $conexion_app->prepare("UPDATE `flujo_electoral` SET `unodiez`='$id1x10' WHERE id=?");
                $stmt2->bind_param("s", $id);
                $stmt2->execute();
                $stmt2 -> close();
            }
        }
    }
}
$stmt->close();




?>