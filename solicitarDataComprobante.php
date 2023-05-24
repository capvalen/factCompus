<?php
date_default_timezone_set('America/Lima');

include 'php/conexion.php';
include 'generales.php';
require "NumeroALetras.php";


$sqlSeries="SELECT `idComprobante`, `factTipoDocumento`, case `factTipoDocumento` when 1 then 'FACTURA' when 3 then 'BOLETA' when -1 then 'PROFORMA' when 0 then 'TICKET INTERNO' end as 'queDoc', `factSerie`, `factCorrelativo`, `tipOperacion`, `fechaEmision`, `fechaVencimiento`, `codLocalEmisor`, `tipDocUsuario`, `dniRUC`, `razonSocial`, `tipoMoneda`, `costoFinal`, `IGVFinal`, `totalFinal`, `sumDescTotal`, `sumOtrosCargos`, `sumTotalAnticipos`, `sumImpVenta`, `ublVersionId`, `customizationId`, `ideTributo`, `nomTributo`, `codTipTributo`, `mtoBaseImponible`, `mtoTributo`, `codLeyenda`, `desLeyenda`, `comprobanteEmitido`, `comprobanteFechado`, factPlaca FROM `fact_cabecera` WHERE factSerie='{$_POST['serie']}' and `factCorrelativo` = '{$_POST['correlativo']}';";

$resultadoSeries=$esclavo->query($sqlSeries);
$rowSeries=$resultadoSeries->fetch_assoc(); 

$caso = "-0{$rowSeries['factTipoDocumento']}-"; // 01 para factura, 03 para boleta

$serie = $rowSeries['factSerie'];
$soy = $rowSeries['queDoc'];
$correlativo = $rowSeries['factCorrelativo'];


$factura =  $serie.'-'.$correlativo;
$nombreArchivo = $rucEmisor.$caso.$factura ; 

$sqlBase="SELECT totalFinal from `fact_cabecera` where factSerie='{$_POST['serie']}' and `factCorrelativo` = '{$_POST['correlativo']}';; ";
$resultadoBase=$cadena->query($sqlBase);
$rowBase=$resultadoBase->fetch_assoc();
	
$parteEntera = intval($rowBase['totalFinal']);
$parteDecimal = ($rowBase['totalFinal']-$parteEntera)*100;
if($parteDecimal == '0'){
	$parteDecimal='00';
}

//Pedir las letras del monto facturado

$letras = trim(NumeroALetras::convertir($parteEntera)).' SOLES con '.$parteDecimal.'/100 MN';

/* ------- Fin update sobre la table fact cabecera ---------- */



$sqlCabeza="SELECT * from `fact_cabecera` where factSerie='{$_POST['serie']}' and `factCorrelativo` = '{$_POST['correlativo']}';";
$resultadoCabeza=$cadena->query($sqlCabeza);
$filasCabeza = $resultadoCabeza->num_rows;
if($filasCabeza==1){
	$rowC=$resultadoCabeza->fetch_assoc();

	if(strlen($rowC['dniRUC'])==11){
		$tipoDoc = '6';
	}else if(strlen($rowC['dniRUC'])==8){
		$tipoDoc = '1';
	}else if(strlen($rowC['dniRUC'])==0){
		$tipoDoc = '0';
	}

	$descuento = $rowC['sumDescTotal'];
	$costo= str_replace (',', '',number_format($rowC['costoFinal'],2));
	$igvFin = str_replace (',', '',number_format($rowC['IGVFinal'],2));
	$totFin = str_replace (',', '',number_format($rowC['totalFinal'],2));
	

}


$sqlCabeza="SELECT * from `fact_cabecera` where factSerie='{$_POST['serie']}' and `factCorrelativo` = '{$_POST['correlativo']}';";
$resultadoCabeza=$cadena->query($sqlCabeza);
$filasCabeza = $resultadoCabeza->num_rows;
if($filasCabeza==1){
	$rowC=$resultadoCabeza->fetch_assoc();

	if(strlen($rowC['dniRUC'])==11){
		$tipoDoc = '6';
	}else if(strlen($rowC['dniRUC'])==8){
		$tipoDoc = '1';
	}else if(strlen($rowC['dniRUC'])==0){
		$tipoDoc = '0';
	}

	$descuento = $rowC['sumDescTotal'];
	$costo= str_replace (',', '',number_format($rowC['costoFinal'],2));
	$igvFin = str_replace (',', '',number_format($rowC['IGVFinal'],2));
	$totFin = str_replace (',', '',number_format($rowC['totalFinal'],2));
	
	$lineaCabeza = $rowC['tipOperacion'].$separador.$rowC['fechaEmision'].$separador.$rowC['horaEmision'].$separador.$rowC['fechaVencimiento'].$separador. $domicilioFiscal.$separador. $tipoDoc.$separador.$rowC['dniRUC'].$separador.$rowC['razonSocial']. $separador.$rowC['tipoMoneda'].$separador. $costo.$separador. $igvFin.$separador. $totFin . $separador. $descuento.$separador. $sumaCargos.$separador.$anticipos. $separador. $totFin.$separador.$versionUbl.$separador. $customizacion.$separador;

	//echo $lineaCabeza;

}

$rowProductos = array();

$i=1;
$lineaDetalle ='';
$sqlDetalle="SELECT fd.*, u.undCorto FROM `fact_detalle` fd inner join unidades u on u.undSunat = codUnidadMedida
WHERE `facSerieCorre` ='{$_POST['serie']}-{$_POST['correlativo']}';";
$resultadoDetalle=$cadena->query($sqlDetalle);
while($rowD=$resultadoDetalle->fetch_assoc()){ 

	/* switch ($rowD['unidadMedida']) {
		case 'UNIDAD': $unidad = 'NIU'; break;
		case 'CAJA': $unidad = 'BX'; break;
		case 'BOLSA': $unidad = 'BG'; break;
		default: $unidad =''; break;
	} */
	$unidad = $rowD['codUnidadMedida'];
	
	$valorFin = str_replace (',', '',number_format($rowD['valorUnitario'],2));
	$igvSubFin = str_replace (',', '',number_format($rowD['igvUnitario'],2));
	$valorSubFin = str_replace (',', '',number_format($rowD['valorItem'],2));
	$precProducto = number_format($rowD['valorUnitario']+$rowD['igvUnitario'],2);

	$lineaDetalle =  $lineaDetalle . $unidad. $separador.$rowD['cantidadItem']. $separador.$i.$separador. $rowD['codProductoSUNAT'].$separador.$rowD['descripcionItem'].$separador. $valorFin.$separador.  $igvSubFin.$separador. $rowD['codTriIGV'] .$separador. $igvSubFin.$separador. $valorSubFin.$separador. $rowD['nomTributoIgvItem'].$separador. $rowD['codTipTributoIgvItem'] .$separador.$rowD['tipAfeIGV']. $separador. $rowD['porIgvItem'] .$separador. $rowD['codTriISC'] . $separador. $rowD['mtoIscItem'] . $separador. $rowD['mtoBaseIscItem'] . $separador. $rowD['nomTributoIscItem'] .$separador . $rowD['codTipTributoIscItem'] .$separador . $rowD['tipSisISC'] .$separador. $rowD['porIscItem']. $separador. $rowD['codTriOtroItem']. $separador. $tributoOtro .$separador. $tributoOtroItem .$separador.$baseOtroItem .$separador.$rowD['codTipTributoIOtroItem'] . $separador. $rowD['porTriOtroItem'] .$separador. $rowD['mtoPrecioVenta'] . $separador. $rowD['mtoValorVenta']. $separador. $rowD['mtoValorReferencialUnitario']. $separador."\n";

	$rowProductos[$i] = array( 'cantidad'=>$rowD['cantidadItem'], 'descripcion'=> $rowD['descripcionItem'], 'precio'=> $rowD['mtoPrecioVenta'], 'preProducto'=> $precProducto, 'undCorto'=> $rowD['undCorto']  );
	$i++;

	
}



$filas=array();
$filas = array(array ( 'rucEmisor'=> $rucEmisor, 'tipoComprobante' => $rowSeries['factTipoDocumento'], 'serie'=> $serie , 'correlativo'=> $correlativo, 'queSoy'=> $soy, 'letras'=> $letras, 'tipoCliente'=>$tipoDoc, 'ruc'=>$rowC['dniRUC'], 'razonSocial'=>$rowC['razonSocial'], 'fechaEmision'=> $rowC['fechaEmision'], 'exonerado'=> $rowC['factExonerados'], 'descuento'=> $descuento, 'costoFinal'=> $costo, 'igvFinal'=> $igvFin, "totalFinal" => $totFin, 'direccion'=> $rowC['cliDireccion'], "placa"=> $rowSeries['factPlaca'] ));

array_push ( $filas, $rowProductos);

echo json_encode($filas);
//echo "fin";

?>