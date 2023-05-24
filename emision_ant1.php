<?php

include 'conexion.php';
include 'generales.php';
require "NumeroALetras.php";

$caso = "-0{$_POST['emitir']}-"; // 01 para factura, 03 para boleta


$sqlSeries="SELECT * FROM `fact_series`";
$resultadoSeries=$esclavo->query($sqlSeries);
$rowSeries=$resultadoSeries->fetch_assoc();

switch ($_POST['emitir']) {
	case '1': $serie = $rowSeries['serieFactura']; $soy="FACTURA"; break;
	case '3': $serie = $rowSeries['serieBoleta']; $soy="BOLETA"; break;
	default: # code... break;
}

$sqlCorrelativo="SELECT LPAD(factCorrelativo+1, 8, '0') as contador  FROM `fact_cabecera` where idLocal='{$_POST['local']}' and factSerie = '{$serie}' order by factCorrelativo desc limit 1";
$resultadoCorrelativo=$cadena->query($sqlCorrelativo);
$filasCorrelativo = $resultadoCorrelativo->num_rows;
if($filasCorrelativo==0){
	$correlativo='00000001';
}else{
	$rowCorrelativo=$resultadoCorrelativo->fetch_assoc(); 
	$correlativo = $rowCorrelativo['contador'];
}


$factura =  $serie.'-'.$correlativo;

$nombreArchivo = $rucEmisor.$caso.$factura ;

$sqlBase="select totalFinal from `fact_cabecera` where 	idNegocio = '{$_POST['negocio']}' and idLocal='{$_POST['local']}' and idTicket='{$_POST['ticket']}'; ";
$resultadoBase=$cadena->query($sqlBase);
$rowBase=$resultadoBase->fetch_assoc();
	
$parteEntera = intval($rowBase['totalFinal']);
$parteDecimal = ($rowBase['totalFinal']-$parteEntera)*100;

//Pedir las letras del monto facturado

$letras = trim(NumeroALetras::convertir($parteEntera)).' SOLES '.$parteDecimal.'/100 MN';


/* ------- Haciendo un update sobre la table fact cabecera ---------- */


$sql="UPDATE `fact_cabecera` SET
`factTipoDocumento`= '{$_POST['emitir']}',
`factSerie`= '{$serie}',
`factCorrelativo`= '{$correlativo}',
`desLeyenda`= '{$letras}',
`comprobanteEmitido` =1,
`comprobanteFechado` =now() where idNegocio = '{$_POST['negocio']}' and idLocal='{$_POST['local']}' and idTicket='{$_POST['ticket']}';";

$resultado=$cadena->query($sql);

/* ------- Fin update sobre la table fact cabecera ---------- */




$sqlCabeza="select * from `fact_cabecera` where idNegocio = '{$_POST['negocio']}' and idLocal='{$_POST['local']}' and idTicket='{$_POST['ticket']}';";
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
	$archivo = fopen("{$directorio}{$nombreArchivo}.cab", "w");
	fwrite($archivo, "{$lineaCabeza}");
	fclose($archivo);

}

$rowProductos = array();

$i=1;
$lineaDetalle ='';
$sqlDetalle="SELECT * FROM `fact_detalle` WHERE idNegocio = '{$_POST['negocio']}' and idLocal='{$_POST['local']}' and idTicket='{$_POST['ticket']}';";
$resultadoDetalle=$cadena->query($sqlDetalle);
while($rowD=$resultadoDetalle->fetch_assoc()){ 

	/* switch ($rowD['unidadMedida']) {
		case 'UNIDAD': $unidad = 'NIU'; break;
		case 'CAJA': $unidad = 'BX'; break;
		case 'BOLSA': $unidad = 'BG'; break;
		default: $unidad =''; break;
	} */
	$unidad = 'NIU';
	
	$valorFin = str_replace (',', '',number_format($rowD['valorUnitario'],2));
	$igvSubFin = str_replace (',', '',number_format($rowD['igvUnitario'],2));
	$valorSubFin = str_replace (',', '',number_format($rowD['valorItem'],2));

	$lineaDetalle =  $lineaDetalle . $unidad.$separador.$rowD['cantidadItem']. $separador.$i.$separador. $rowD['codProductoSUNAT'].$separador.$rowD['descripcionItem'].$separador. $valorFin.$separador.  $igvSubFin.$separador. $rowD['codTriIGV'] .$separador. $igvSubFin.$separador. $valorSubFin.$separador. $rowD['nomTributoIgvItem'].$separador. $rowD['codTipTributoIgvItem'] .$separador.$rowD['tipAfeIGV']. $separador. $rowD['porIgvItem'] .$separador. $rowD['codTriISC'] . $separador. $rowD['mtoIscItem'] . $separador. $rowD['mtoBaseIscItem'] . $separador. $rowD['nomTributoIscItem'] .$separador . $rowD['codTipTributoIscItem'] .$separador . $rowD['tipSisISC'] .$separador. $rowD['porIscItem']. $separador. $rowD['codTriOtroItem']. $separador. $tributoOtro .$separador. $tributoOtroItem .$separador.$baseOtroItem .$separador.$rowD['codTipTributoIOtroItem'] . $separador. $rowD['porTriOtroItem'] .$separador. $rowD['mtoPrecioVenta'] . $separador. $rowD['mtoValorVenta']. $separador. $rowD['mtoValorReferencialUnitario']. $separador."\n";

	$rowProductos[$i] = array( 'cantidad'=>$rowD['cantidadItem'], 'descripcion'=> $rowD['descripcionItem'], 'precio'=> $rowD['mtoPrecioVenta']  );
	$i++;

	
}
//echo $lineaDetalle ;

$detalle = fopen("{$directorio}{$nombreArchivo}.det", "w");
fwrite($detalle, "{$lineaDetalle}");
fclose($detalle);

$leyenda = $rowC['codLeyenda'].$separador. $letras .$separador;

$fLeyenda = fopen("{$directorio}{$nombreArchivo}.ley", "w");
fwrite($fLeyenda, "{$leyenda}");
fclose($fLeyenda);



$tributo = $rowC['ideTributo'] . $separador . $rowC['nomTributo'] . $separador .  $rowC['codTipTributo']  . $separador . $rowC['mtoBaseImponible'] . $separador . $rowC['mtoTributo'] . $separador;

$fTributo = fopen("{$directorio}{$nombreArchivo}.tri", "w");
fwrite($fTributo, "{$tributo}");
fclose($fTributo);



$filas=array();
$filas = array(array ( 'rucEmisor'=> $rucEmisor, 'tipoComprobante' => $_POST['emitir'], 'serie'=> $serie , 'correlativo'=> $correlativo, 'queSoy'=> $soy, 'letras'=> $letras, 'tipoCliente'=>$tipoDoc, 'ruc'=>$rowC['dniRUC'], 'razonSocial'=>$rowC['razonSocial'], 'fechaEmision'=> $rowC['fechaEmision'], 'descuento'=> $descuento, 'costoFinal'=> $costo, 'igvFinal'=> $igvFin, "totalFinal" => $totFin) );

array_push ( $filas, $rowProductos);

echo json_encode($filas);
//echo "fin";

?>