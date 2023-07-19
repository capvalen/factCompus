<?php 
include "conexion.php";

$sql="UPDATE creditos set estado = 1, diaPago=now() where id = {$_POST['idCredito']};";
//echo $sql;

if($cadena->query($sql)){
	ob_start();
	$_POST['accion'] = 'entradaEnCaja';
	include './caja.php';
	ob_end_clean();
  echo "ok";
}else{
   echo "fallo algo";
}
?>