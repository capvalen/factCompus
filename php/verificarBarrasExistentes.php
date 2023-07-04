<?php 
include "conexion.php";

$barras = json_decode($_POST['barras'], true);

$noExiste = [];
for($i=0; $i< count($barras); $i++){
	$sql = "SELECT * FROM `barras` where barra = ? and activo = 1";
	$sent = $datab-> prepare( $sql );
	$sent -> execute([ $barras[$i] ]);
	$rows = $sent->fetch(PDO::FETCH_ASSOC);
	
	if($sent -> rowCount() == 0){
		array_push( $noExiste, $barras[$i] );
	}

}
echo json_encode( array('noExiste' => $noExiste) );