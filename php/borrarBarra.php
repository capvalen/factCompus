<?php 
include "conexion.php";

$sql="UPDATE `barras` SET `activo` = '0' WHERE `barras`.`idBarra` = {$_POST['idBarra']};";
if($resultado=$cadena->query($sql)){
	echo "ok";
}else{
	echo "error";
}

?>