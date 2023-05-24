<?php 

include '../generales.php';

if(is_dir($directorio)){
	//echo "es directorio";
	if ($dir = opendir($directorio)) {
		echo '<ul>'; //Abrimos una lista HTML para mostrar los archivos

		while (($archivoSinFirma = readdir($dir)) !== false){ //Comenzamos a leer archivo por archivo, validamos si es un archivo real

			if ($archivoSinFirma != '.' && $archivoSinFirma != '..' && strpos($archivoSinFirma, '.cab')){//Omitimos los archivos del sistema . y ..

				echo '<li>';
				
				echo 'Archivo: '.$archivoSinFirma; //simplemente imprimimos el nombre del archivo actual
				
				
					$archivoConFirma = str_replace('.cab','',$archivoSinFirma);
					
					$respuestaFirmada = $dirRespuestas."R". $archivoConFirma.".zip";
					//echo $respuestaFirmada;
					if( file_exists($respuestaFirmada) ){
						//echo ' - Existe firma';
						//Actualizar estado en la DB
						actualizarDB($archivoConFirma, $dirBaseSunat);
					}
				
				'</li>'; 
			}

		}//finaliza While
		echo '</ul>';//Se cierra la lista			
	}
}else{
	echo "La ruta del Facturador es erronea";
}


function actualizarDB($archivoConFirma, $dirBase){

	$explotado = explode('-',$archivoConFirma);
	
	$serieTemp = $explotado[2];
	$correlativoTemp = intval($explotado[3]);
	$_POST['serieTemp']=$serieTemp;
	$_POST['correlativoTemp']=$correlativoTemp;
	if(require 'actualizarEstadoSunat.php'){
		echo "empieza el borrado";
		$tempCabecera = $dirBase . '\DATA/'.$archivoConFirma.'.cab';
		$tempDetalle = $dirBase . '\DATA/'.$archivoConFirma.'.det';
		$tempLeyenda = $dirBase . '\DATA/'.$archivoConFirma.'.ley';
		$tempTri = $dirBase . '\DATA/'.$archivoConFirma.'.tri';

		if(file_exists($tempCabecera)){ unlink($tempCabecera); }
		if(file_exists($tempDetalle)){ unlink($tempDetalle); }
		if(file_exists($tempLeyenda)){ unlink($tempLeyenda); }
		if(file_exists($tempTri)){ unlink($tempTri); }
		
	}
}

?>