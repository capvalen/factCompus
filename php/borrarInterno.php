<?php 
include "conexion.php";

$sqlProductos = "SELECT idProducto, cantidadItem, serie FROM `fact_detalle`
where idCabecera = {$_POST['id']};";
$resProductos = $esclavo -> query($sqlProductos);

$ids = [];
while($productos = $resProductos->fetch_assoc()){
	//array_push($ids, $productos['idProducto']);
	if($productos['serie']<>''){
		$sqlBarra = "UPDATE `barras` SET `activo`=1 WHERE `idProducto` = ? and `barra` = ?";
		$resBarra = $datab->prepare($sqlBarra);
		$resBarra -> execute([
			$productos['idProducto'], $productos['serie']
		]);
		//echo $resBarra->debugDumpParams();
		$resBarra->closeCursor();
	}
	$_POST['proceso'] = '7';
	$_POST['idProd'] = $productos['idProducto'];
	$_POST['cantidad'] = $productos['cantidadItem'];
	$_POST['obs'] = 'Retorno de venta anulada';
	require __DIR__. '/updateStock.php';

	
}
//echo implode(',',$ids);

//die();

$sql="UPDATE `fact_cabecera` SET `comprobanteEmitido` = '4' WHERE `idComprobante` = {$_POST['id']};";
if($resultado=$cadena->query($sql)){
	echo "ok";
}else{
	echo "error";
}

?>