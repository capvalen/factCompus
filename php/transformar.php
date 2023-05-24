<?php 

include 'conexion.php';
include '../generales.php';

$idCabecera=$_POST['id'];
$tipoDoc=$_POST['tipo'];
$afectos=0; $exonerados=0;
$sumaTotal =0;
$caso = "-0{$_POST['tipo']}-"; // 01 para factura, 03 para boleta

switch ($_POST['tipo']) {
	case '1': $soy="FACTURA"; break;
	case '3': $soy="BOLETA DE VENTA"; break;
	case '0': $soy="NOTA DE PEDIDO"; break;
	case '-1': $soy="PROFORMA"; break;
	default: # code... break;
}
$serie = $_POST['serie'];

$sqlCorrelativo="SELECT LPAD(factCorrelativo+1, 8, '0') as contador FROM `fact_cabecera` where factSerie = '{$serie}' order by factCorrelativo desc limit 1";
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

$sqlUpdCabecera=
"UPDATE `fact_cabecera` SET `factTipoDocumento` = '{$_POST['tipo']}', 
	`factSerie` = '{$_POST['serie']}',
	`factCorrelativo` = '{$correlativo}'
	WHERE `idComprobante` = {$_POST['id']}; ";
if($esclavo->query($sqlUpdCabecera)){

	$sql="UPDATE `fact_detalle` SET `facSerieCorre` = '{$_POST['serie']}-{$correlativo}' WHERE idCabecera = {$idCabecera}; ";
	$resultado=$cadena->query($sql);


	# Generando los archivos txt para sunat 
	$sqlCabeza="SELECT * from `fact_cabecera` where idComprobante={$idCabecera};"; //echo $sqlCabeza;
	$resultadoCabeza=$cadena->query($sqlCabeza);
	$filasCabeza = $resultadoCabeza->num_rows;
	if($filasCabeza==1){
		$rowC=$resultadoCabeza->fetch_assoc();

		$descuento = $rowC['sumDescTotal'];
		$costo= str_replace (',', '',number_format($rowC['costoFinal'] ,2));
		$igvFin = str_replace (',', '',number_format($rowC['IGVFinal'],2));
		$totFin = str_replace (',', '',number_format($rowC['totalFinal'],2));
		
		$lineaCabeza = $rowC['tipOperacion'].$separador.$rowC['fechaEmision'].$separador.$rowC['horaEmision'].$separador.$rowC['fechaVencimiento'].$separador. $domicilioFiscal.$separador. $tipoDoc.$separador.$rowC['dniRUC'].$separador.$rowC['razonSocial']. $separador.$rowC['tipoMoneda'].$separador. $igvFin.$separador. $costo.$separador. $totFin . $separador. $descuento.$separador. $sumaCargos.$separador.$anticipos. $separador. $totFin.$separador.$versionUbl.$separador. $customizacion.$separador;
		//echo $lineaCabeza;

		$letras = $rowC['desLeyenda'];

		
		$archivo = fopen("{$directorio}{$nombreArchivo}.cab", "w");
		fwrite($archivo, "{$lineaCabeza}");
		fclose($archivo);
		
	}
	//Actualización Facturador v3
	$bolsas = '|-|0|0||';

	$rowProductos = array();

	$i=1;
	$lineaDetalle ='';
	$sqlDetalle="SELECT fd.*, u.undCorto FROM `fact_detalle` fd inner join unidades u on u.undSunat = codUnidadMedida
	WHERE idCabecera={$idCabecera};";
	//echo $sqlDetalle;
	$resultadoDetalle=$cadena->query($sqlDetalle);
	while($rowD=$resultadoDetalle->fetch_assoc()){ 

		$unidad = $rowD['codUnidadMedida'];
		
		$valorFin = str_replace (',', '',number_format($rowD['valorUnitario'],2));
		$igvSubFin = str_replace (',', '',number_format($rowD['igvUnitario'],2));
		$valorSubFin = str_replace (',', '',number_format($rowD['valorItem'],2));
		$precProducto = round($rowD['valorUnitario']+$rowD['igvUnitario'],2);

		if( $rowD['idGravado']=='1' ){
			$lineaDetalle =  $lineaDetalle . $unidad.$separador.$rowD['cantidadItem']. $separador.$i.$separador. $rowD['codProductoSUNAT'].$separador.$rowD['descripcionItem'].$separador. $valorFin. $separador. round($igvSubFin*$rowD['cantidadItem'],2) .$separador. $rowD['codTriIGV'] .$separador. $rowD['mtoIgvItem'].$separador. $valorSubFin.$separador. $rowD['nomTributoIgvItem'].$separador. $rowD['codTipTributoIgvItem'] .$separador.$rowD['tipAfeIGV']. $separador. $rowD['porIgvItem'] .$separador. $rowD['codTriISC'] . $separador. $rowD['mtoIscItem'] . $separador. $rowD['mtoBaseIscItem'] . $separador. $rowD['nomTributoIscItem'] .$separador . $rowD['codTipTributoIscItem'] .$separador . $rowD['tipSisISC'] .$separador. $rowD['porIscItem']. $separador. $rowD['codTriOtroItem']. $separador. $tributoOtro .$separador. $tributoOtroItem .$separador.$baseOtroItem .$separador.$rowD['codTipTributoIOtroItem'] . $separador. $rowD['porTriOtroItem'] .$separador. $bolsas.$separador. round($rowD['mtoPrecioVenta']/$rowD['cantidadItem'],2) . $separador. $rowD['mtoValorVenta']. $separador. $rowD['mtoValorReferencialUnitario']. $separador."\n";
		}else{
			$lineaDetalle =  $lineaDetalle . $unidad.$separador.$rowD['cantidadItem']. $separador.$i.$separador. $rowD['codProductoSUNAT'].$separador.$rowD['descripcionItem'].$separador. $valorFin. $separador. "0.00" .$separador. $rowD['codTriIGV'] .$separador. $rowD['mtoIgvItem'].$separador. $valorSubFin .$separador. $rowD['nomTributoIgvItem'].$separador. $rowD['codTipTributoIgvItem'] .$separador.$rowD['tipAfeIGV']. $separador. $rowD['porIgvItem'] .$separador. $rowD['codTriISC'] . $separador. $rowD['mtoIscItem'] . $separador. $rowD['mtoBaseIscItem'] . $separador. $rowD['nomTributoIscItem'] .$separador . $rowD['codTipTributoIscItem'] .$separador . $rowD['tipSisISC'] .$separador. $rowD['porIscItem']. $separador. $rowD['codTriOtroItem']. $separador. $tributoOtro .$separador. $tributoOtroItem .$separador.$baseOtroItem .$separador.$rowD['codTipTributoIOtroItem'] . $separador. $rowD['porTriOtroItem'] .$separador. $bolsas.$separador. $precProducto . $separador. $rowD['valorItem']. $separador. $rowD['mtoValorReferencialUnitario']. $separador."\n";
			
		}


		$rowProductos[$i] = array( 'cantidad'=>$rowD['cantidadItem'], 'descripcion'=> $rowD['descripcionItem'], 'precio'=> $rowD['mtoPrecioVenta'], 'costo'=> $rowD['valorUnitario'], 'preProducto'=> $precProducto , 'undCorto'=> $rowD['undCorto'] );
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
	if( $exonerados >0 ){
		$tributo = $tributo . "\n9997|EXO|VAT|".round($exonerados,2)."|0.00|";
	}

	$fTributo = fopen("{$directorio}{$nombreArchivo}.tri", "w");
	fwrite($fTributo, "{$tributo}");
	fclose($fTributo);



}


?>