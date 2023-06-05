<?php 
include "conexion.php";

$sql="UPDATE `proveedores` SET `activo` = 0 WHERE `id` = {$_POST['id']};";
if($resultado=$cadena->query($sql)){
	echo "ok";
}else{
	echo "error";
}

?>