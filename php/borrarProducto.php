<?php 
include "conexion.php";

$sql="UPDATE `productos` SET `prodActivo` = '0' WHERE `productos`.`idProductos` = {$_POST['idProd']};";
$resultado=$cadena->query($sql);
echo "ok";
?>