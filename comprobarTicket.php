<?php

include("php/conexion.php");
include "generales.php";

$sql="select idTicket from `fact_cabecera` where 	idNegocio = '{$_POST['negocio']}' and idLocal='{$_POST['local']}' and idTicket='{$_POST['ticket']} and fechaEmision = curdate()'; ";
//echo $sql;
$resultado=$cadena->query($sql);
$lineas = $resultado->num_rows;
if( $lineas >=1){
	echo $_POST['ticket'];
}else{
	echo 'sin coincidencia';
}


?>