<?php 
include "conexion.php";

$queEmite=array();

$sqlSerie="SELECT * FROM `fact_series`";
$resultadoSerie=$cadena->query($sqlSerie);
$rowSerie=$resultadoSerie->fetch_assoc();


$sql="SELECT * FROM `fact_cabecera` where idComprobante = '{$_POST['id']}'; ";
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();
$queEmite = array();
if(in_array($row['tipDocUsuario'], [0,1])){ //es dni de 8
	$queEmite = array( 'que'=> 'Boleta', 'serie'=> $rowSerie['serieBoleta'], 'tipo'=> 3);
}else if($row['tipDocUsuario']==6){ //es ruc 11
	$queEmite = array( 'que'=> 'Factura', 'serie'=> $rowSerie['serieFactura'], 'tipo'=> 1);
}
echo json_encode($queEmite);
?>