<?php 
$contrasena = "*wT,?stHdxju";
$usuario = "wfvrkfap_mary";
$nombre_bd = "wfvrkfap_bodegamary";

$respuesta = [];

try {
	$db = new PDO (
		'mysql:host=localhost;
		dbname='.$nombre_bd,
		$usuario,
		$contrasena,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
} catch (Exception $e) {
	echo "Problema con la conexion: ".$e->getMessage();
}

$sentencia = $db -> query("SELECT `factTipoDocumento`, `factSerie`, `factCorrelativo`, fechaEmision, DATEDIFF(curdate(), `fechaEmision`) as diferencia
 FROM `fact_cabecera` where comprobanteEmitido = 1 order by factTipoDocumento, fechaEmision asc ;");

$comprobantes = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
if( $sentencia->rowCount() ==0){
	$respuesta = array(
		'enviar' => "No hay comprobantes",
		'cantidad' => 0,
		'comprobante' => "",
		'diferencia' => ''
	);
	//'viejo' => '',
	
	//print_r("No hay comprobantes" );
}else{
	$respuesta = array(
		'enviar' => "Tienes por enviar {$sentencia->rowCount()} comprobantes",
		'cantidad' => $sentencia->rowCount(),
		'comprobante' => "{$comprobantes[0]['factSerie']}-{$comprobantes[0]['factCorrelativo']}",
		'diferencia' => $comprobantes[0]['diferencia']
	);
	//'viejo' => $comprobantes[0]['fechaEmision'],
	
	//print_r("Tienes por enviar {$sentencia->rowCount()} comprobantes" );
}
echo json_encode($respuesta);
?>