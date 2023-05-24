<?php 

include "conexion.php";

$sql="SELECT * FROM `configuracion`
where confVariable in ('ruc', 'razonSocial', 'nomComercial', 'direccion', 'celular', 'logo', 'ticketera', 'facturador', 'crearArchivo', 'carpeta', 'verCantidad')";
$resultado=$cadena->query($sql);
$filas = array();
while($row=$resultado->fetch_assoc()){ 
	if( $row['confVariable']=='ruc' ){ $filas['ruc']=$row['confValor']; }
	if( $row['confVariable']=='razonSocial' ){ $filas['razonSocial']=$row['confValor']; }
	if( $row['confVariable']=='nomComercial' ){ $filas['nomComercial']=$row['confValor']; }
	if( $row['confVariable']=='direccion' ){ $filas['direccion']=$row['confValor']; }
	if( $row['confVariable']=='celular' ){ $filas['celular']=$row['confValor']; }
	if( $row['confVariable']=='logo' ){ $filas['logo']=$row['confValor']; }
	if( $row['confVariable']=='ticketera' ){ $filas['ticketera']=$row['confValor']; }
	if( $row['confVariable']=='facturador' ){ $filas['facturador']=$row['confValor']; }
	if( $row['confVariable']=='crearArchivo' ){ $filas['crearArchivo']=$row['confValor']; }
	if( $row['confVariable']=='carpeta' ){ $filas['carpeta']=$row['confValor']; }
}

$sqlSerie="SELECT * FROM `fact_series`";
$resultadoSerie=$cadena->query($sqlSerie);
$rowSerie=$resultadoSerie->fetch_assoc();
$filas['serieFactura'] = $rowSerie['serieFactura'];
$filas['serieBoleta'] = $rowSerie['serieBoleta'];

?>