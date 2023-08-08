<?php
include "fpdf/fpdf.php";
include('phpqrcode/qrlib.php'); 

include 'php/conexion.php';
include 'generales.php';
require "NumeroALetras.php";



$sqlSeries="SELECT fc.`idComprobante`, `factTipoDocumento`, case `factTipoDocumento` when 1 then 'FACTURA ELECTRÓNICA' when 3 then 'BOLETA ELECTRÓNICA' when -1 then 'PROFORMA' when 0 then 'TICKET' end as 'queDoc', `factSerie`, `factCorrelativo`, `tipOperacion`, fc.`fechaEmision`, `fechaVencimiento`, `codLocalEmisor`, `tipDocUsuario`, `dniRUC`, `razonSocial`, `tipoMoneda`, `costoFinal`, `IGVFinal`, `totalFinal`, `sumDescTotal`, `sumOtrosCargos`, `sumTotalAnticipos`, `sumImpVenta`, `ublVersionId`, `customizationId`, `ideTributo`, `nomTributo`, `codTipTributo`, `mtoBaseImponible`, `mtoTributo`, `codLeyenda`, `desLeyenda`, `comprobanteEmitido`, `comprobanteFechado`, count(fd.codItem) as cantFilas
FROM `fact_cabecera` fc
inner join fact_detalle fd on concat(fc.factSerie, '-', fc.factCorrelativo) = fd.facSerieCorre
WHERE fc.factSerie = '{$_GET['serie']}' and fc.factCorrelativo='{$_GET['correlativo']}'
group by fd.idCabecera; ";
//echo $sqlSeries; 
$resultadoSeries=$esclavo->query($sqlSeries);
$rowSeries=$resultadoSeries->fetch_assoc();
//var_dump( $rowSeries);

$sqlCreditos = "SELECT c.*, date_format(c.fecha, '%d/%m/%Y') as fechaLatam FROM `creditos` c where idComprobante = {$rowSeries['idComprobante']};";
$respCreditos = $esclavo->query($sqlCreditos);
//echo 'cant. creditos son '. var_dump($respCreditos->num_rows); die();


$caso = "-0{$rowSeries['factTipoDocumento']}-"; // 01 para factura, 03 para boleta

$serie = $rowSeries['factSerie'];
$soy = $rowSeries['queDoc'];
$correlativo = $rowSeries['factCorrelativo'];

if( in_array( $rowSeries['queDoc'] , ['PROFORMA', 'TICKET INTERNO']) ){ $factura =  $correlativo; }else{$factura =  $serie.'-'.$correlativo;}
$nombreArchivo = $rucEmisor.$caso.$factura ; 


$sqlBase="SELECT totalFinal from `fact_cabecera` where factSerie = '{$_GET['serie']}' and factCorrelativo='{$_GET['correlativo']}'; ";
$resultadoBase=$cadena->query($sqlBase);
$rowBase=$resultadoBase->fetch_assoc();
	
$parteEntera = intval($rowBase['totalFinal']);
$parteDecimal = ($rowBase['totalFinal']-$parteEntera)*100;
if($parteDecimal == '0'){
	$parteDecimal='00';
}
//Pedir las letras del monto facturado

$letras = trim(NumeroALetras::convertir($parteEntera)).' SOLES '.$parteDecimal.'/100 MN';



$sqlCabeza="SELECT * from `fact_cabecera` where factSerie = '{$_GET['serie']}' and factCorrelativo='{$_GET['correlativo']}';";

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

$fecha= new DateTime($rowC['fechaEmision']);

$tempDir = './';
$filename = "qrtemp";
$body =  $rucEmisor .$separador. $rowSeries['factTipoDocumento'] .$separador. $serie .$separador. $correlativo .$separador. $rowC['IGVFinal'] .$separador. $rowC['totalFinal'] . $separador. $fecha->format('d/m/Y') . $separador. $tipoDoc . $separador. $rowC['dniRUC'] . $separador . $rowC['factPlaca']. $separador;
$codeContents = $body; 
QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);


//Extraido de https://evilnapsis.com/2018/04/26/php-formato-de-ticket-basico-para-impresoras-de-tickets-con-fpdf/
$pdf = new FPDF($orientation='P',$unit='mm', array(75, ( 20 * (5+$rowSeries['cantFilas']) + (5+$respCreditos->num_rows)  )+ 60 )); //N° lineas * 6 + 60 espacio minimo //75 es los mm de ancho
$pdf->AddPage();
$pdf->SetFont('Arial','B',10);    //Letra Arial, negrita (Bold), tam. 20
$textypos = 5;
$pdf->setY(2);
$pdf->setX(2);
$pdf->Image('./images/empresa_black.png', 20, 0, -300);
$pdf->setY(23);
$pdf->Ln();
$pdf->Cell( 0, $textypos, utf8_decode($soy), 0, 0, 'C');
$pdf->Ln();
$pdf->Cell( 0, $textypos, $factura, 0, 0, 'C');
$pdf->Ln();
$pdf->Cell( 0, $textypos, "R.U.C. ". $rucEmisor, 0, 0, 'C');
$pdf->Ln();
$pdf->Cell( 0, $textypos, $nombreEmisor, 0, 0, 'C');
$pdf->SetFont('Arial','', 9);
$pdf->Ln();
$pdf->MultiCell( 0, 3, utf8_decode( $direccionEmisor), 0, 'C');
$pdf->Cell( 0, $textypos, 'Celular: '. $celularEmisor, 0, 0, 'C');
$pdf->Ln();
$pdf->Cell( 0, $textypos, '-------------' , 0, 0, 'C'); $pdf->Ln();
$pdf->setX(2);
$pdf->Cell( 0, 3, 'RUC: '. $rowC['dniRUC'], 0, 0); 
$pdf->Ln(); $pdf->setX(2);
$pdf->Cell( 0, 3, utf8_decode('Razón Social: '. $rowC['razonSocial']), 0, 0);
$pdf->Ln(); $pdf->setX(2);
$pdf->Cell( 0, 3, utf8_decode('Dirección: '. $rowC['cliDireccion']), 0, 0);
$pdf->Ln(); $pdf->setX(2);
$pdf->Cell( 0, 3, utf8_decode('Fecha emisión: '. $fecha->format('d/m/Y')), 0, 0);
$pdf->Ln(); $pdf->setX(2);
$pdf->Cell( 0, 3, utf8_decode('Moneda: Soles'), 0, 0); $pdf->Ln();
$pdf->Cell( 0, $textypos, '-------------' , 0, 0, 'C'); $pdf->Ln();

$pdf->setX(2);
$pdf->SetFont('Arial','B', 9);
$pdf->Cell(5,$textypos,'Cant.    Producto                            SubTotal (S/)');
$pdf->SetFont('Arial','', 9);

$i=1;
$rowProductos = array();

$lineaDetalle ='';
$sqlDetalle="SELECT fd.*, u.undCorto FROM `fact_detalle` fd inner join unidades u on u.undSunat = codUnidadMedida WHERE facSerieCorre = '{$_GET['serie']}-{$_GET['correlativo']}'";
$resultadoDetalle=$cadena->query($sqlDetalle);
while($rowD=$resultadoDetalle->fetch_assoc()){ 

	$unidad = 'NIU';
	
	$valorFin = str_replace (',', '',number_format($rowD['valorUnitario'],2));
	$igvSubFin = str_replace (',', '',number_format($rowD['igvUnitario'],2));
	$valorSubFin = str_replace (',', '',number_format($rowD['valorItem'],2));
	$precProducto = number_format($rowD['valorUnitario']+$rowD['igvUnitario'],2);
	$cantidadProd = $rowD['cantidadItem'];

	$pdf->Ln(); $pdf->setX(2);
	$queSerie = $rowD['serie']==''? '': "(SN: {$rowD['serie']}) ";
	$pdf->MultiCell(0, 3, utf8_decode("{$rowD['cantidadItem']} Und.  {$rowD['descripcionItem']} " . $queSerie ) , 0);
	$pdf->setX(5);
	$pdf->MultiCell(0, 3, 'S/ '. number_format( floatval($precProducto) * floatval($cantidadProd) ,2), 0, 'R');
	$i++;
}
$pdf->Cell( 0, $textypos, '-------------' , 0, 0, 'C'); $pdf->Ln();
$pdf->SetFont('Arial','B', 9);
$pdf->Cell( 0, $textypos, 'SubTotal: S/ ' . number_format($rowC['costoFinal'],2) , 0, 0, ); $pdf->Ln();
$pdf->Cell( 0, $textypos, 'IGV: S/ ' . number_format($rowC['IGVFinal'],2) , 0, 0, ); $pdf->Ln();
$pdf->Cell( 0, $textypos, 'Total: S/ ' . number_format($rowC['totalFinal'],2) , 0, 0, ); 
$pdf->Ln(); $pdf->setX(2);

$pdf->SetFont('Arial','B', 9);
$c=1;
if( $respCreditos->num_rows>0 ) $pdf->Cell( 0, $textypos, utf8_decode('Créditos: ') , 0, 0 , 'B'); $pdf->Ln();
$pdf->SetFont('Arial','', 9);

while( $rowCreditos = $respCreditos->fetch_assoc()){
	$pdf->Cell( 0, $textypos, utf8_decode("{$c}° {$rowCreditos['fechaLatam']}. Monto: S/ " . number_format($rowCreditos['monto'],2)) , 0, 0 ); $pdf->Ln();
	$c++;
}
$pdf->setX(2);
$pdf->Cell( 0, $textypos, 'Son: ' . $letras); $pdf->Ln();
$posX = $pdf->GetX();
$posY = $pdf->GetY();
$pdf->Image('qrtemp.png', $posX+10, $posY, -150);
$pdf->SetY($posY+25);
$pdf->Ln(); $pdf->setX(2);
$pdf->SetFont('Arial','', 7);
$pdf->MultiCell(0,3, utf8_decode('Visible en Sunat a partir de las 24 horas de la emisión mediante Resolución de Superintendencia N° 0150-2021/SUNAT. '), 0);



$pdf->output();