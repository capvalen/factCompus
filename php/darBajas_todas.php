<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Lima');
include "conexion.php";
include "../generales.php";


$current_dir = dirname(__FILE__);
//echo $current_dir;
$directorio="../comprobantes/";
//$path = realpath( $current_dir . '/../comprobantes/' );
$path = realpath( $current_dir . '/'.$directorio );

$lineaBaja ='';
$lineaActualizar ='';

//echo "Estamos en ". $path;

$sqlNum="SELECT LPAD(count(idComprobante)+1,3,0) as contBajas
FROM `fact_cabecera`
where comprobanteEmitido = 2"; //factTipoDocumento = {$_POST['boleta']} and
$resultadoNum=$conf->query($sqlNum);
$rowNum=$resultadoNum->fetch_assoc();
//echo $rowNum['contBajas'];

$sqlComprobantes = '';
$queComprobantes = '';
foreach ($_POST['comprobantes'] as $comprobante) {
	$queComprobantes .= $comprobante. ',';
}
$queComprobantes = substr($queComprobantes, 0, -1);
$sqlComprobantes.="SELECT * from `fact_cabecera` WHERE `idComprobante` in ({$queComprobantes}); ";

//echo $sqlComprobantes;
$resultComprobantes=$cadena->query($sqlComprobantes);
while($rowComprobantes=$resultComprobantes->fetch_assoc()){
	if($rowComprobantes['comprobanteEmitido']==2){
		if($rowComprobantes['factTipoDocumento']==1){ //Factura
			

			$sqlC="SELECT concat(`factSerie`,'-', `factCorrelativo`) as facCorre, `fechaEmision` FROM `fact_cabecera` WHERE `idComprobante`={$rowComprobantes['idComprobante']};";
			$resultadoC=$conf->query($sqlC);
			$rowC=$resultadoC->fetch_assoc();

			$lineaBaja = $lineaBaja . $rowC['fechaEmision']. "|".date('Y-m-d')."|01|".$rowC['facCorre']."|".strtoupper($rowComprobantes['motivoBaja'])."|"."\n";
			//echo $nombreArchivoBorrar;	
		}
		if($rowComprobantes['factTipoDocumento']==3){ //Boleta
			$nombreArchivo = $rucEmisor."-RA-".date('Ymd')."-".$rowNum['contBajas'];

			$sqlC="SELECT concat(`factSerie`,'-', `factCorrelativo`) as facCorre, `fechaEmision`, tipDocUsuario, dniRUC, costoFinal, IGVFinal, totalFinal FROM `fact_cabecera` WHERE `idComprobante`={$rowComprobantes['idComprobante']};";
			$resultadoC=$cadena->query($sqlC);
			$rowC=$resultadoC->fetch_assoc();

			/* $lineaBaja = $lineaBaja . $rowC['fechaEmision']. "|". date('Y-m-d') ."|03|".$rowC['facCorre']."|".$rowC['tipDocUsuario']."|".$rowC['dniRUC']."|PEN|".$rowC['costoFinal']."|0|0|0|0|0|". $rowC['totalFinal']."|||||||||3|"; */
			$lineaBaja = $lineaBaja . $rowC['fechaEmision']. "|".date('Y-m-d')."|03|".$rowC['facCorre']."|BAJA DE BOLETA DE VENTA|"."\n";
			//echo $nombreArchivo;
		}
		$lineaActualizar.="UPDATE `fact_cabecera` SET `comprobanteEmitido` = 2,
		fechaBaja = CONVERT_TZ(now(), '+00:00', '-05:00')
		where `idComprobante` = {$rowComprobantes['idComprobante']}";

		$nombreArchivoBorrar = $rucEmisor."-RA-".date('Ymd')."-".$rowNum['contBajas'];

		$baja = fopen("{$path}/{$nombreArchivoBorrar}.cba", "w"); //{$directorio}{$nombreArchivo}
		fwrite($baja, "{$lineaBaja}");
		fclose($baja);

		if($lineaActualizar<>''){
			$sql= $lineaActualizar;
			$resultado=$conf->query($sql);
}


	}
}
