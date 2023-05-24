<?php

$ruta  = "C:/SFS_v1.2/sunat_archivos/sfs/FIRMA/{$_POST['emisor']}-0{$_POST['caso']}-{$_POST['serie']}-{$_POST['correlativo']}.xml";

if (file_exists($ruta)) {
	$doc = new DOMDocument(); 
	$doc->load($ruta); 
	$respuesta = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
	
	echo $respuesta;
}else{
	echo "Sin firma";
}


?>