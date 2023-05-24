<?php

$ruta  = "C:/SFS_v1.2/sunat_archivos/sfs/FIRMA/20602337147-01-F001-00000005.xml";

if (file_exists($ruta)) {
	$doc = new DOMDocument(); 
	$doc->load($ruta); 
	$respuesta = $doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
	
	echo $respuesta;
}else{
	echo "Sin firma";
}


?>