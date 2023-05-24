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
//para Boletas:
//$body =  "20602337147" .$separador."03" .$separador."EB01" .$separador. "21" .$separador. "15.54" .$separador."101.90" . $separador. "12/04/2019" . $separador. "0" . $separador. "000000000". $separador ;
//para Facturas:
$body =  "20602337147" .$separador."01" .$separador."E001" .$separador. "19" .$separador. "91.52" .$separador."599.99" . $separador. "22/04/2019" . $separador. "6" . $separador. "20604144575". $separador ;

$qrCode = new QrCode($body);
$qrCode->setSize(260);
$qrCode->setWriterByName('png');
$qrCode->setMargin(10);
$qrCode->setEncoding('UTF-8');
$qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW));
$qrCode->writeFile(__DIR__.'/qrcode.png');


/* $productos=$_POST['productos'];
$todoProd= '';
foreach ($productos as $variable) {
    $todoProd = $todoProd.$variable['cantidad']." ".$variable['undCorto']." | ". "S/ ". $variable['preProducto']." | ". ucwords($variable['descripcion']) .'  S/ '. number_format($variable['precio'],2)."\n";
} */
//echo $todoProd;


$connectorV31 = new WindowsPrintConnector("smb://127.0.0.1/".$nombrePrint);
try {

    $tux = EscposImage::load("infbitmap.png", false);
    $tuxQR = EscposImage::load("qrcode.png", false);

    $printer = new Printer($connectorV31);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> bitImage($tux);
    $printer -> setEmphasis(true);
    $printer -> text("\nINFOCAT SOLUCIONES S.A.C.\n");
    $printer -> text("RUC: 20602337147\n");
    $printer -> setEmphasis(false);
    $printer -> text("Av. Huancavelica 435 - El Tambo - Huancayo\n");
    $printer -> setEmphasis(true);
    $printer -> text("FACTURA ELECTRÓNICA\n");
    $printer -> text("E001 - 19\n"); //Cambniar Codigooooooooo
    $printer -> setEmphasis(false);
    $printer -> text("--------------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $printer -> text("Fecha de emisión: 22/04/2019 \n");//".date('d/m/Y')."
    $printer -> text("RUC: 20604144575\n");
    $printer -> text("Señores: INSTITUTO NACIONAL DE CAPACITACION PARA EL ASCENSO PROFESIONAL SAC\n");
    $printer -> text("Dirección: CAL. CALLE REAL 860 - EL TAMBO SEGUNDO PISO JUNIN-HUANCAYO\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("--------------------------------\n");
    $printer->setJustification(Printer::JUSTIFY_LEFT);
	$printer -> text("PRODUCTO  |  SUBTOTAL  |\n");
	$printer -> text("DISEÑO DE PÁGINA WEB \nY AULA VIRTUAL       S/ 599.99\n");
    $printer -> text("--------------------------------\n");
    $printer -> text("Sub Total: S/ 508.47 \n");
    $printer -> text("IGV (18%): S/ 91.52 \n");
    $printer -> text("Total: S/ 599.99 \n");
    $printer -> text("SON: QUINIENTOS NOVENTA Y NUEVE Y 99/100 SOLES\n");
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer -> text("--------------------------------\n");
    $printer -> bitImage($tuxQR);
    $printer -> text("--------------------------------\n");
    $printer -> text("Contacto: 977692108\n");
    $printer -> text("Gracias por tu preferencia\n\n");
    $printer -> text("Esta es una representación impresa de la factura electrónica, generada en el Sistema de SUNAT.  Puede verificarla utilizando su Clave SOL.\n");
    $printer -> cut();
    // Close printer 
    $printer -> close();
} catch (Exception $e) {
    echo "No se pudo imprimir en la impresora: " . $e -> getMessage() . "\n";
}