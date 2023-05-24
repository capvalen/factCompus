<?php
include('phpqrcode/qrlib.php'); 

$tempDir = './';
$filename = "qrtemp";
$body =  "contenido generado";
$codeContents = $body; 
QRcode::png($codeContents, $tempDir.''.$filename.'.png', QR_ECLEVEL_L, 5);
