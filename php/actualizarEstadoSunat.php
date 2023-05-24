<?php 
date_default_timezone_set('America/Lima');
include "conexion.php";


$sql="UPDATE `fact_cabecera` SET `comprobanteEmitido`= 3 where `factSerie`='{$_POST['serieTemp']}' and `factCorrelativo`={$_POST['correlativoTemp']}";
//echo $sql;
if($cadena->query($sql)){
	//echo "reg. actualizado";
	return true;
}else{
	return false;
}

?>