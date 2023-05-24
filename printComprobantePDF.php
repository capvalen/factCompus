<?php
include('phpqrcode/qrlib.php'); 

include 'php/conexion.php';
include 'generales.php';
require "NumeroALetras.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Facturador electrónico <?= $_GET['serie']."-".$_GET['correlativo'];?> - Desarrollado por infocatsoluciones.com</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" >
</head>
<body>
<style>
.bordeGrueso{
	border: 4px solid #343a40!important;
}
.bordeDelgado{
	border: 2px solid #343a40!important;
}
.bordeAlgo{
	border-top: 2px solid #343a40!important;
}
</style>
<div class="container">
	<button class="btn btn-primary" id="btnPrimt">Imprimir Documento</button>
<?php



$sqlSeries="SELECT `idComprobante`, `factTipoDocumento`, case `factTipoDocumento` when 1 then 'FACTURA ELECTRÓNICA' when 3 then 'BOLETA ELECTRÓNICA' when -1 then 'PROFORMA' when 0 then 'TICKET INTERNO' end as 'queDoc', `factSerie`, `factCorrelativo`, `tipOperacion`, `fechaEmision`, `fechaVencimiento`, `codLocalEmisor`, `tipDocUsuario`, `dniRUC`, `razonSocial`, `tipoMoneda`, `costoFinal`, `IGVFinal`, `totalFinal`, `sumDescTotal`, `sumOtrosCargos`, `sumTotalAnticipos`, `sumImpVenta`, `ublVersionId`, `customizationId`, `ideTributo`, `nomTributo`, `codTipTributo`, `mtoBaseImponible`, `mtoTributo`, `codLeyenda`, `desLeyenda`, `comprobanteEmitido`, `comprobanteFechado` FROM `fact_cabecera` WHERE factSerie = '{$_GET['serie']}' and factCorrelativo='{$_GET['correlativo']}'; ";
$resultadoSeries=$esclavo->query($sqlSeries);
$rowSeries=$resultadoSeries->fetch_assoc();


$caso = "-0{$rowSeries['factTipoDocumento']}-"; // 01 para factura, 03 para boleta

$serie = $rowSeries['factSerie'];
$soy = $rowSeries['queDoc'];
$correlativo = $rowSeries['factCorrelativo'];

if( in_array( $rowSeries['queDoc'] , ['PROFORMA', 'TICKET INTERNO']) ){ $factura =  $correlativo; }else{$factura =  $serie.'-'.$correlativo;}
$nombreArchivo = $rucEmisor.$caso.$factura ; 


$sqlBase="select totalFinal from `fact_cabecera` where factSerie = '{$_GET['serie']}' and factCorrelativo='{$_GET['correlativo']}'; ";
$resultadoBase=$cadena->query($sqlBase);
$rowBase=$resultadoBase->fetch_assoc();
	
$parteEntera = intval($rowBase['totalFinal']);
$parteDecimal = ($rowBase['totalFinal']-$parteEntera)*100;
if($parteDecimal == '0'){
	$parteDecimal='00';
}
//Pedir las letras del monto facturado

$letras = trim(NumeroALetras::convertir($parteEntera)).' SOLES '.$parteDecimal.'/100 MN';



$sqlCabeza="select * from `fact_cabecera` where factSerie = '{$_GET['serie']}' and factCorrelativo='{$_GET['correlativo']}';";

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

?>


<div class="row">
<div class="col-sm-6 ">
	<img src="images/empresa.jpg" alt="" class="w-50" >
	
</div>
<div class="col-sm-6 mt-5 mb-2 text-center " class="">
	<div class="border border-dark bordeGrueso">
		<h3 class="text-uppercase">RUC: <?= $rucEmisor; ?></h3>
		<h2 class="text-uppercase"><?= $soy; ?></h2>
		<h2 class="text-uppercase"><?= $factura; ?></h2>
	</div>
</div>
</div>
<div class="row my-2">
	<div class="col">
		<p class="mb-0 "><strong><?= $nombreEmisor;?></strong></p>
		<p class="mb-0"><strong>Dirección:</strong> <?= $direccionEmisor; ?></p>
		<p class="mb-0"><strong>Celular:</strong> <?= $celularEmisor;?></p>
	</div>
</div>
<section>
	<div class="border bordeDelgado p-2 container-fluid">
	<div class="row p-2">
		<div class="col-8">
			<p class="text-capitalize mb-0"><strong>Señor(es):</strong> <?=  $rowC['razonSocial']; ?> </p>
			<p class="text-capitalize mb-0"><strong>Domicilio:</strong> <?= ($rowC['cliDireccion'] =='') ? '-' : $rowC['cliDireccion']; ?></p>
			<p class="mb-0"><strong>N° Documento:</strong> <?= $rowC['dniRUC']; ?></p>
			
		</div>
		<div class="col">
		<br>
		<p class="mb-0"><strong>Fecha de emisión:</strong> <?php echo $fecha->format('d/m/Y'); ?></p>
		<p class="mb-0"><strong>Tipo Moneda:</strong> Soles</p>
		</div>
	</div>
	</div>
</section>
<section class="pt-2">
<table class="table table-bordered">
<thead>
	<tr>
		<th class="p-1">Item</th>
		<th class="p-1">Descripción</th>
		<th class="p-1">Und.</th>
		<th class="p-1">Cant.</th>
		<th class="p-1">Prec. Unt.</th>
		<th class="p-1">Sub-Total</th>
	</tr>
</thead>
<tbody>


<?php

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
	$cantidadProd = $rowD['cantidadItem']

	?>
	<tr>
		<td class="p-1"><?= $i;?></td>
		<td class="p-1" class="text-capitalize"><?= $rowD['descripcionItem']; ?></td>
		<td class="p-1"><?= $rowD['undCorto']?></td>
		<td class="p-1"><?= $rowD['cantidadItem']; ?></td>
		<td class="p-1"><?= $precProducto; ?></td>
		<td class="p-1"><?= number_format( floatval($precProducto) * floatval($cantidadProd) ,2)?></td>
	</tr>
	<?php 
	$i++;
}


?>

</tbody>
</table>
</section>

<section>
<div class="row">
<div class="col-6">
	<div class="d-flex flex-column ">
		<p>Representacion Impresa de <br><?= $soy; ?> ELECTRÓNICA: <?= $factura; ?></p>
		
		<div class="text-center">
			<img src="qrtemp.png" alt="">
		</div>
		<h5 class="text-center">Son: <?= $letras; ?></h5>
	</div>

</div>
<div class="col-6">
<div class="row">
	<div class="col text-right">
		
		<p class="mb-0">Op. Gravada</p>
		<p class="mb-0">I.G.V.</p>
		<p class="mb-0">Op. Gratuita</p>
		<p class="">Op. Exonerada</p>
		<p class="d-none">Op. Inafecta</p>
		<h5 class="border-top pt-2 bordeAlgo">Importe Total</h5>
	</div>
	<div class="col">
		<p class="d-none">S/ <?= number_format($rowC['sumDescTotal'],2);?></p>
		<p class="mb-0">S/ <?= number_format($rowC['costoFinal'],2);?></p>
		<p class="mb-0">S/ <?= number_format($rowC['IGVFinal'],2);?></p>
		<p class="mb-0">S/ 0.00</p>
		<p class="">S/ <?= number_format($rowC['factExonerados'],2);?></p>
		<p class="d-none">S/ 0.00</p>
		<h5 class="border-top pt-2 bordeAlgo">S/ <?= number_format($rowC['totalFinal'],2);?></h5>
	</div>
</div>
</div>
</div>
<div class="row">
	<div class="col">
		<p class="small">Puede ser consultada en: https://grupoeuroandino.com/facturas/ <br/>Visible en Sunat a partir de las 24 horas de la emisión mediante Resolución de Superintendencia N° 0150-2021/SUNAT. </p>
	</div>
</div>
</section>


</div> <!-- Fin de contariner 1 -->
<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js" ></script>
<script src="js/bootstrap.min.js" ></script>

<script>
$(document).ready(function () {
	//Activa la impresion apenas cargo todo
});
$('#btnPrimt').click(()=>{
	window.print();
})

</script>
</body>
</html>