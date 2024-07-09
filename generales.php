<?php
//Rellenar por cada Cliente:
$dirBaseSunat = 'D:\SFS_2.1\sunat_archivos\sfs';
$directorio = $dirBaseSunat. '\DATA/';
$dirRespuestas = $dirBaseSunat. '\RPTA/';

$rucEmisor ='20612115771';
$nombreEmisor = "Botica's Clinical Home SAC";
$direccionEmisor = "Jr. General Gamarra 1173 Chilca - Huancayo - Huancayo - Junín";
$celularEmisor = "939784647";
$nombrePrint = 'CAJA'; //TM-T20II

$casaHost = "pluginSunat";

$generarArchivo = false;



$separador ='|';

//De la cabecera:
$tipoOperacion = '0101';
$fechaVencimiento = '-';
$domicilioFiscal = '0000'; //cambiar a 1 si es sucursal
$descuento = '0.00';
$sumaCargos ='0.00';
$anticipos ='0.00';
$versionUbl ='2.1';
$customizacion ='2.0';


//Del detalle:
$codSunat = '-';
$tipoTributo='1000';
$nombreTributo = 'IGV';
$tributoExtranjero = 'VAT';
$afectacion = '10';
$porcentajeIGV = $_COOKIE["igvGlobal"] ?? 18;
$porcentajeIGV1 = 1+($porcentajeIGV)/100;
$tributoISC = '-';
$codigoISC = '0.00';
$montoISC = '0.00';
$baseISC = '';
$nombreISC = '';
$codeISC = '';
$tipoISC = '';
$porcentajeISC = '15.00';
$tributo99 = '-';
$tributoOtro = '0.00';
$tributoOtroItem = '0.00';
$baseOtroItem = '';
$codigoOtroItem = '';
$porcentajeOtroItem = '15.00';
$invoce = '11.80';
$ventaInvoce = '11.80';
$valorVentaInvoce='10.00';
$gratuito ='0.00';
$monedaC = 'PEN';

$_POST['accion'] = 'verificarCaja';
ob_start();
require_once( __DIR__ .'/php/caja.php');
$cajaAbierta = json_decode(ob_get_clean(), true);
$cajaAbierta = $cajaAbierta[0] ?? [] ;

?>
