<?php 

include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true);

foreach ($_POST['comprobantes'] as $comprobante) {

	$sqlEstado="SELECT comprobanteEmitido FROM `fact_cabecera` where idComprobante={$comprobante}	";
	$resultadoEstado=$esclavo->query($sqlEstado);
	$rowEstado=$resultadoEstado->fetch_assoc();
	if($rowEstado['comprobanteEmitido']=='2'){
		$estado=2;
	}else{
		$estado=3;
	}

	$sql="UPDATE `facturador` SET `estado` = '{$estado}' WHERE `facturador`.`idComprobante` = {$comprobante};";
	//echo $sql;
	$resultado=$cadena->query($sql);
	
}

?>