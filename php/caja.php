<?php 

include 'conexion.php';

switch($_POST['accion']){
	case 'abrirCaja': abrirCaja($datab); break;
	case 'cerrarCaja': cerrarCaja($datab); break;
	case 'verificarCaja': verificarCaja($datab); break;
	case 'datosDeCaja': datosDeCaja($datab); break;
}

function abrirCaja($db){
	$sql = "INSERT INTO `caja`(`idUsuario`, `apertura`, `observacion`) VALUES (?, ?, ?)";
	$res = $db->prepare($sql);
	$res -> execute([
		$_COOKIE['ckidUsuario'], $_POST['apertura'], $_POST['observacion']
	]);
	//echo $res->debugDumpParams();
	if($res) echo json_encode(array( 'id' => $db->lastInsertId(), 'apertura' => $_POST['apertura'], 'observacion' => $_POST['observacion'], 'abierto'=>1 ));
	else echo 'error';
}

function cerrarCaja($db){
	$sql = "UPDATE `caja` SET `cierre` = ?, `observacion` = concat (`observacion`, ' ', ? ), `fechaCierre` = NOW(), abierto = 0 WHERE id = ?";
	$res = $db->prepare($sql);
	$res -> execute([
		$_POST['cierre'], $_POST['observacion'], $_POST['id']
	]);
	//echo $res->debugDumpParams();
	if($res) echo json_encode(array( 'id' => $db->lastInsertId(), 'cierre' => $_POST['cierre'], 'observacion' => $_POST['observacion'], 'abierto'=>0 ));
	else echo 'error';
}

function verificarCaja($db){
	$filas = [];
	$sql = "SELECT * FROM `caja` where abierto = 1 order by fechaApertura desc limit 1; ";
	$res = $db->prepare($sql);
	$res -> execute();
	while($row = $res -> fetch(PDO::FETCH_ASSOC)){
		$filas[] = $row;
	}
	//echo $res->debugDumpParams();
	if($res){
		if ( count($filas) >0 ): echo json_encode( $filas, JSON_NUMERIC_CHECK );
		else: echo json_encode(array( 'id' => -1, 'abierto'=>0 ));
		endif;
	}else echo 'error';
}
function datosDeCaja($db){
	$ventas = [];
	$sqlVentas = "SELECT * FROM `fact_cabecera` WHERE `idCaja` = {$_POST['idCaja']} and comprobanteEmitido not in (2,4);";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute();
	while($row = $resVentas -> fetch(PDO::FETCH_ASSOC)){
		$ventas[] = $row;
	}
	
	//echo $res->debugDumpParams();
	if($resVentas)
		echo json_encode(array( 'ventas' => $ventas ));
	else echo 'error';
}