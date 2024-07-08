<?php
include('phpqrcode/qrlib.php'); 
include "generales.php";


require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage; //librería de imagen


include "vendor/autoload.php";

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;


$separador ='|';
$tempDir = './';
$filename = "qrtemp";
$body =  $_POST['rucEmisor'] .$separador. $_POST['tipoComprobante'] .$separador. $_POST['serie'] .$separador. $_POST['correlativo'] .$separador. $_POST['igvFinal'] .$separador. $_POST['totalFinal'] . $separador. $_POST['fecha'] . $separador. $_POST['tipoCliente'] . $separador. $_POST['docClient']. $separador ;

$qrCode = new QrCode($body);
$qrCode->setSize(260);
$qrCode->setWriterByName('png');
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW));
$qrCode->writeFile(__DIR__.'/qrcode.png');


$productos=$_POST['productos'];
$todoProd= '';
foreach ($productos as $variable) {
    $todoProd = $todoProd .  ucwords($variable['descripcion']) . ( $variable['serie']!='' ) ? " SN: {$variable['serie']}" : ''."\n              ".$variable['cantidad']." ".$variable['undCorto']."   ". "S/ ". number_format($variable['preProducto'],2).'   S/ '. number_format($variable['precio'],2)."\n";
}
//echo $todoProd;
if($_POST['queEs']!='RECETA MÉDICA' && $_POST['queEs']!="VENTA INTERNA"){ $queEs = $_POST['queEs'] . " ELECTRÓNICA"; }else{ $queEs = $_POST['queEs']; }
if (isset($_POST['ticketera'])){ $nombrePrint= $_POST['ticketera'];}

$connectorV31 = new WindowsPrintConnector("smb://127.0.0.1/".$nombrePrint);
try {

    $tux = EscposImage::load(".\images\empresa.jpg", false);
    $tuxQR = EscposImage::load("qrcode.png", false);

    $printer = new Printer($connectorV31);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> bitImage($tux);
    $printer -> setEmphasis(true);
    $printer -> text($nombreEmisor."\n");
    $printer -> text("RUC: ".$rucEmisor."\n");
    $printer -> setEmphasis(false);
    $printer -> text("".$direccionEmisor."\n");
    $printer -> setEmphasis(true);
    $printer -> text("{$queEs}\n");
    $printer -> text("{$_POST['serie']}-{$_POST['correlativo']}\n"); //Cambniar Codigooooooooo
    $printer -> setEmphasis(false);
    $printer -> text("--------------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer -> text("Fecha de emisión: ". $_POST['fechaLat'] ."\n");
    $printer -> text("Doc. Identidad: {$_POST['docClient']}\n");
    $printer -> text("Señor(es): ".strtoupper($_POST['cliente'])."\n");
    if($_POST['direccion']==''){
        $printer -> text("Dirección: ---\n");
    }else{
        $printer -> text("Dirección: ".strtoupper($_POST['direccion'])."\n");}
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("--------------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
	$printer -> text("DESCRIPCION  | CANT |  P.UNIT.  |  SUBTOTAL  |\n");
    $printer -> text("{$todoProd}\n");
    $printer -> text("--------------------------------\n");
    $printer -> text("Exonerado: S/ {$_POST['exonerado']} \n");
    $printer -> text("Sub Total: S/ {$_POST['costoFinal']} \n");
    $printer -> text("IGV (18%): S/ {$_POST['igvFinal']} \n");
    $printer -> text("Total: S/ {$_POST['totalFinal']} \n");
    $printer -> text("SON: {$_POST['monedas']} \n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("--------------------------------\n");
	if($_POST['serie']==''){
    $printer -> text("Contacto: ".$celularEmisor."\n");
	
		$printer -> text("No olvide reclamar su comprobante\n");
	}else{
		$printer -> bitImage($tuxQR);
		$printer -> text("--------------------------------\n");
    $printer -> text("Contacto: ".$celularEmisor."\n");
    $printer -> setTextSize(1, 1);
    $printer -> text("Esta es una representación impresa de la factura electrónica, generada en el Sistema de SUNAT. Puede verificarla utilizando su Clave SOL.\n");
	}	
    $printer -> text("Gracias por tu preferencia\n");
    
    $printer -> cut();
    // Close printer 
    $printer -> close();
} catch (Exception $e) {
    echo "No se pudo imprimir en la impresora: " . $e -> getMessage() . "\n";
}