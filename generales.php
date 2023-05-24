<?php
//Rellenar por cada Cliente:
$dirBaseSunat = 'D:\Fac_Casabarro_1.3.4.4\sunat_archivos\sfs';
$directorio = $dirBaseSunat. '\DATA/';
$dirRespuestas = $dirBaseSunat. '\RPTA/';

$rucEmisor ='20568390629';
$nombreEmisor = "GRUPO EURO ANDINO S.A.C.";
$direccionEmisor = "Cal. Independencia N° 415 - El Tambo - Huancayo - Junín";
$celularEmisor = "947614293";
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
$porcentajeIGV = $_COOKIE['igvGlobal'];
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

?>