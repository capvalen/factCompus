<?php 
date_default_timezone_set('America/Lima');
include 'conexion.php';
include '../generales.php';
require "../NumeroALetras.php";

$_POST = json_decode(file_get_contents('php://input'),true); 
//var_dump( $_POST);


/* Verifico si existe, el cliente, sino lo guardo */
$sqlCliente = "SELECT * FROM `clientes` where cliRuc = '{$_POST['cliente']['dni']}' and cliActivo=1;";
$respCliente = $datab->query($sqlCliente)->fetchAll();
if(count($respCliente)==0){
	$prepCliente = $datab->prepare("INSERT INTO `clientes`(`cliRuc`, `cliRazonSocial`, `cliDomicilio`) VALUES (?, ?, ?)");
	$respCliente2 = $prepCliente->execute([ $_POST['cliente']['dni'], $_POST['cliente']['razon'], $_POST['cliente']['direccion'] ]);
}else{
	$prepCliente2 = $datab->prepare("UPDATE `clientes` SET `cliRazonSocial`=?, `cliDomicilio`=? WHERE `cliRuc`= ?;");
	$respCliente2 = $prepCliente2->execute([ $_POST['cliente']['razon'], $_POST['cliente']['direccion'], $_POST['cliente']['dni'] ]);
}

$caso = "-0{$_POST['cabecera']['tipo']}-"; // 01 para factura, 03 para boleta

switch ($_POST['cabecera']['tipo']) {
	case '1': $soy="FACTURA"; break;
	case '3': $soy="BOLETA DE VENTA"; break;
	case '0': $soy="NOTA DE PEDIDO"; break;
	case '-1': $soy="PROFORMA"; break;
	default: # code... break;
}
$serie = $_POST['cabecera']['serie'];

$hoy = new DateTime();
if( $_POST['cabecera']['fecha']== $hoy->format('Y-m-d') ){
	$fecha = 'curdate()';
}else{
	//$fecha = "'".$_POST['cabecera']['fecha']."'";
	$fecha = "'{$_POST['cabecera']['fecha']}'";
}

$productos= $_POST['jsonProductos'];
$afectos=0; $exonerados=0;
$sumaTotal =0;
for ($i=0; $i < count($productos) ; $i++) { 
	if( $productos[$i]['afecto']=='1' ){
		$afectos = $afectos + ( $productos[$i]['cantidad']*$productos[$i]['precio'] );
	}else{
		$exonerados= $exonerados + ( $productos[$i]['cantidad']*$productos[$i]['precio'] );
	}
}
$sumaTotal = round($afectos+$exonerados,2);
$baseTotal = round($afectos/ $porcentajeIGV1 ,2);
$igvTotal = round($afectos-$baseTotal,2);


$parteEntera = intval($sumaTotal);
$parteDecimal = round(($sumaTotal-$parteEntera)*100,0);
if($parteDecimal == '0'){
	$parteDecimal='00';
}



if(strlen($_POST['cliente']['dni'])==11){
	$tipoDoc = '6';
}else if(strlen($_POST['cliente']['dni'])==8){
	$tipoDoc = '1';
}else if(strlen($_POST['cliente']['dni'])<8){
	$tipoDoc = '0';
}

//Pedir las letras del monto facturado
$letras = trim(NumeroALetras::convertir($parteEntera)).' SOLES con '.$parteDecimal.'/100 MN';

$sqlCorrelativo="SELECT LPAD(factCorrelativo+1, 8, '0') as contador FROM `fact_cabecera` where factSerie = '{$serie}' order by factCorrelativo desc limit 1";
$resultadoCorrelativo=$cadena->query($sqlCorrelativo);
$filasCorrelativo = $resultadoCorrelativo->num_rows;
if($filasCorrelativo==0){
	$correlativo='00000001';
}else{
	$rowCorrelativo=$resultadoCorrelativo->fetch_assoc(); 
	$correlativo = $rowCorrelativo['contador'];
}

$sql="INSERT INTO `fact_cabecera`(`idComprobante`, `factTipoDocumento`, `factSerie`, `factCorrelativo`, `fechaEmision`, `horaEmision`, `tipDocUsuario`,
 `dniRUC`, `razonSocial`,
 `factExonerados`, `costoFinal`, `IGVFinal`, `totalFinal`,`sumImpVenta`, `mtoBaseImponible`, `mtoTributo`, `desLeyenda`,
  `comprobanteEmitido`, `comprobanteFechado`, `cliDireccion`, `factPlaca`) 
VALUES (null,{$_POST['cabecera']['tipo']},'{$serie}','{$correlativo}',{$fecha}, curtime(),{$tipoDoc},
	'{$_POST['cliente']['dni']}', '{$_POST['cliente']['razon']}',
	{$exonerados}, {$baseTotal}, {$igvTotal}, {$sumaTotal}, {$sumaTotal}, {$baseTotal}, {$igvTotal}, '{$letras}',
	1,now(), '{$_POST['cliente']['direccion']}', '' );";
	//echo $sql;

$factura =  $serie.'-'.$correlativo;
$nombreArchivo = $_POST['empresa']['ruc'].$caso.$factura ;

$resultado=$cadena->query($sql);
$idCabecera = $cadena->insert_id;

$sqlProd  =''; 
for ($i=0; $i < count($productos) ; $i++) { 
	if( $productos[$i]['subTotal']<>0){
		$canti = $productos[$i]['cantidad'];
		$prec = $productos[$i]['precio'];
		if( $productos[$i]['afecto']=='1'  ){
			$subTo = round($canti*$prec,2);
			$costoUnit = round($prec/ $porcentajeIGV1 ,2);
			$igvUnit= round($prec-$costoUnit,2);
			$valorUnit = round($costoUnit*$canti,2);
			$igvCant=round($igvUnit*$canti,2);
			$exonerado=0;
			$codigoIGV='1000'; $nomTributo ='IGV'; $tipAfecto=10;
		}else{
			$exonerado = round($prec,2);
			$subTo = $exonerado;
			$costoUnit = $exonerado;
			$igvUnit= 0;
			$valorUnit = round($costoUnit*$canti,2);
			$igvCant= 0;
			$codigoIGV='9997'; $nomTributo ='EXO'; $tipAfecto=20;
		}
		
		$sqlProd = "INSERT INTO `fact_detalle`(`codItem`, `idCabecera`,`facSerieCorre`, `codUnidadMedida`, `cantidadItem`, `codProducto`, `descripcionItem`,
		`valorUnitario`, `valorExonerado`, `igvUnitario`, `mtoIgvItem`, `valorItem`, `mtoPrecioVenta`, `mtoValorVenta`, `codTriIGV`, `nomTributoIgvItem`, `tipAfeIGV`, `fechaEmision`, `idGravado`, `idProducto`, `porIgvItem`) VALUES
		 (null, {$idCabecera}, concat('{$serie}','-','{$correlativo}'), '{$productos[$i]['unidadSunat']}', {$canti}, {$i}, '{$productos[$i]['nombre']}',
		 {$costoUnit}, {$exonerado}, {$igvUnit}, {$igvCant}, {$valorUnit},{$subTo},{$valorUnit}, {$codigoIGV}, '{$nomTributo}', {$tipAfecto}, now(), {$productos[$i]['afecto']}, {$productos[$i]['id']}, {$porcentajeIGV});";
		 $cadena->query($sqlProd);

		 $_POST['idProd']=$productos[$i]['id'];
		 $_POST['proceso']='3';
		 $_POST['cantidad']=$canti;
		 $_POST['obs']='';
		 require 'updateStock.php';
		 

		// echo $sqlProd;
	}
}



# Generando los archivos txt para sunat 
$sqlCabeza="SELECT * from `fact_cabecera` where idComprobante={$idCabecera}; "; //factSerie = '{$serie}' and factCorrelativo='{$correlativo}' //echo $sqlCabeza;
$resultadoCabeza=$cadena->query($sqlCabeza);
$filasCabeza = $resultadoCabeza->num_rows;
if($filasCabeza==1){
	$rowC=$resultadoCabeza->fetch_assoc();

	$descuento = $rowC['sumDescTotal'];
	if($rowC['costoFinal']==0){
		$costo= str_replace (',', '',number_format($rowC['factExonerados'] ,2));
	}else{
		$costo= str_replace (',', '',number_format($rowC['costoFinal'] ,2));
	}
	$igvFin = str_replace (',', '',number_format($rowC['IGVFinal'],2));
	$totFin = str_replace (',', '',number_format($rowC['totalFinal'],2));
	
	$lineaCabeza = $rowC['tipOperacion'].$separador.$rowC['fechaEmision'].$separador.$rowC['horaEmision'].$separador.$rowC['fechaVencimiento'].$separador. $domicilioFiscal.$separador. $tipoDoc.$separador.$rowC['dniRUC'].$separador.$rowC['razonSocial']. $separador.$rowC['tipoMoneda'].$separador. $igvFin.$separador. $costo.$separador. $totFin . $separador. $descuento.$separador. $sumaCargos.$separador.$anticipos. $separador. $totFin.$separador.$versionUbl.$separador. $customizacion.$separador;
	//echo $lineaCabeza;

	if($_POST['empresa']['crearArchivo']==1 && $soy!='NOTA DE PEDIDO' ){
		$dire= $_POST['empresa']['facturador'] . "\sunat_archivos\sfs\DATA/";
		$archivo = fopen("{$dire}{$nombreArchivo}.cab", "w");
		fwrite($archivo, "{$lineaCabeza}");
		fclose($archivo);
	}
}
//Actualización Facturador v3
$bolsas = '|-|0|0||';

$rowProductos = array();

$i=1;
$lineaDetalle ='';
$sqlDetalle="SELECT fd.*, u.undCorto FROM `fact_detalle` fd inner join unidades u on u.undSunat = codUnidadMedida
WHERE idCabecera={$idCabecera};"; //`facSerieCorre` ='{$serie}-{$correlativo}'
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

if($_POST['empresa']['crearArchivo']==1 && $soy!='NOTA DE PEDIDO' ){
	$dire= $_POST['empresa']['facturador'] . "\sunat_archivos\sfs\DATA/";
	$detalle = fopen("{$dire}{$nombreArchivo}.det", "w");
	fwrite($detalle, "{$lineaDetalle}");
	fclose($detalle);

	$leyenda = $rowC['codLeyenda'].$separador. $letras .$separador;

	$fLeyenda = fopen("{$dire}{$nombreArchivo}.ley", "w");
	fwrite($fLeyenda, "{$leyenda}");
	fclose($fLeyenda);



	$tributo = $rowC['ideTributo'] . $separador . $rowC['nomTributo'] . $separador .  $rowC['codTipTributo']  . $separador . $rowC['mtoBaseImponible'] . $separador . $rowC['mtoTributo'] . $separador;
	if( $exonerados >0 ){
		$tributo = $tributo . "\n9997|EXO|VAT|".round($exonerados,2)."|0.00|";
	}

	$fTributo = fopen("{$dire}{$nombreArchivo}.tri", "w");
	fwrite($fTributo, "{$tributo}");
	fclose($fTributo);


}




//echo $serie."-".$correlativo."-OK";
$filas=array();
$filas = array(array ( 'rucEmisor'=> $_POST['empresa']['ruc'], 'tipoComprobante' => $_POST['cabecera']['tipo'], 'serie'=> $serie , 'correlativo'=> $correlativo, 'queSoy'=> $soy, 'letras'=> $letras, 'tipoCliente'=>$tipoDoc, 'ruc'=>$_POST['cliente']['dni'], 'razonSocial'=>$_POST['cliente']['razon'], 'fechaEmision'=> $rowC['fechaEmision'], 'exonerado'=> $exonerados,  'descuento'=> $descuento, 'costoFinal'=> $costo, 'igvFinal'=> $igvFin, "totalFinal" => $totFin, 'direccion'=> $rowC['cliDireccion'], "placa"=> '' ));

array_push ( $filas, $rowProductos);

echo json_encode($filas);


?>