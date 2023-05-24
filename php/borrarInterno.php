<?php 
include "conexion.php";

$sql="UPDATE `fact_cabecera` SET `comprobanteEmitido` = '4' WHERE `idComprobante` = {$_POST['id']};";
if($resultado=$cadena->query($sql)){
	echo "ok";
}else{
	echo "error";
}

?>