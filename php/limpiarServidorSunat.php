<?php 
//sleep(15);
$current_dir = dirname(__FILE__);

include "../generales.php";

$directorio= $current_dir. "/../comprobantes/";

/* $fi = new FilesystemIterator(__DIR__.$directorio, FilesystemIterator::SKIP_DOTS);
printf("There were %d Files", iterator_count($fi)); */

if(is_dir($directorio)){
	//echo "es directorio";
	if ($dir = opendir($directorio)) {
		
		echo '<ul>'; //Abrimos una lista HTML para mostrar los archivos

		while (($archivoSinFirma = readdir($dir)) !== false){ //Comenzamos a leer archivo por archivo, validamos si es un archivo real
			if ($archivoSinFirma != '.' && $archivoSinFirma != '..'){//Omitimos los archivos del sistema . y ..
				if( is_dir($directorio.$archivoSinFirma) ){
					//echo 'es directorio '. $archivoSinFirma;
				}else{
					//echo "es archivo ". $archivoSinFirma." tiene ".strpos($archivoSinFirma, '.zip')."<br>";
					if( strpos($archivoSinFirma, '.zip') ){ unlink($directorio . $archivoSinFirma); }
					if( strpos($archivoSinFirma, '.cba') ){ unlink($directorio . $archivoSinFirma); }
				}
				if( strpos($archivoSinFirma, '.cab') ){
					echo '<li>';
					echo 'Archivo: '.$archivoSinFirma; //simplemente imprimimos el nombre del archivo actual
					$archivoConFirma = str_replace('.cab','',$archivoSinFirma);
					echo "BASE: ". $archivoConFirma;
					
					//Actualizar estado en la DB
					actualizarDB($archivoConFirma, $directorio);
					'</li>'; 
				}
				

			}
			/* echo "<li>". $archivoSinFirma . ' es ' . strpos($archivoSinFirma, 'datosSunat')."</li>";
			if( strpos($archivoSinFirma, 'datosSunat')){
				unlink($archivoSinFirma);
			} */
		}//finaliza While
		echo '</ul>';//Se cierra la lista
		
	}
	/* $arZip =glob(opendir($directorio."datosSunat*.zip"));
	echo 'totalArchivos zip '.count($arZip);
	for($i=0; $i<=count($arZip); $i++){
		if(file_exists($arZip[$i])){ unlink($arZip[$i]); }
	} */
	/* $arCba =glob(opendir($directorio."*.cba"));
	for($i=0; $i<=count($arCba); $i++){
		if(file_exists($arCba[$i])){ unlink($arCba[$i]); }
	} */
}else{
	echo "La ruta del Facturador es erronea";
}

function actualizarDB($archivoConFirma, $dirBase){
	$tempCabecera = $dirBase . $archivoConFirma.'.cab';
	$tempDetalle = $dirBase . $archivoConFirma.'.det';
	$tempLeyenda = $dirBase . $archivoConFirma.'.ley';
	$tempTri = $dirBase . $archivoConFirma.'.tri';
	$tempPag = $dirBase . $archivoConFirma.'.pag';

	if(file_exists($tempCabecera)){ unlink($tempCabecera); }
	if(file_exists($tempDetalle)){ unlink($tempDetalle); }
	if(file_exists($tempLeyenda)){ unlink($tempLeyenda); }
	if(file_exists($tempTri)){ unlink($tempTri); }
	if(file_exists($tempPag)){ unlink($tempPag); }
		
}


?>