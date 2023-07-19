<?php 

include 'conexion.php';

switch($_POST['accion']){
	case 'abrirCaja': abrirCaja($datab); break;
	case 'cerrarCaja': cerrarCaja($datab); break;
	case 'verificarCaja': verificarCaja($datab); break;
	case 'pedir1Caja': pedir1Caja($datab); break;
	case 'datosDeCaja': datosDeCaja($datab); break;
	case 'entradaEnCaja': entradaEnCaja($datab); break;
	case 'salidaEnCaja': entradaEnCaja($datab); break;
	case 'borrarRegistro': borrarRegistro($datab); break;
	case 'buscarCajas': buscarCajas($datab); break;
	case 'cambiarMoneda': cambiarMoneda($datab); break;
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
	$sql = "SELECT c.*, u.usuNombres FROM `caja` c inner join usuario u on u.idUsuario = c.idUsuario where abierto = 1 order by fechaApertura desc limit 1; ";
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
function pedir1Caja($db){
	$filas = [];
	$sql = "SELECT c.*, u.usuNombres FROM `caja` c inner join usuario u on u.idUsuario = c.idUsuario where id= ?; ";
	$res = $db->prepare($sql);
	$res -> execute([ $_POST['id'] ]);
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

	$ingresos = [];
	$sqlIngresos = "SELECT * FROM `caja_registros` WHERE `idCaja` = {$_POST['idCaja']} and idProceso in (7,9) and activo = 1 order by registro asc;";
	$resIngresos = $db->prepare($sqlIngresos);
	$resIngresos -> execute();
	while($row = $resIngresos -> fetch(PDO::FETCH_ASSOC)){
		$ingresos[] = $row;
	}
	
	$salidas = [];
	$sqlSalidas = "SELECT * FROM `caja_registros` WHERE `idCaja` = {$_POST['idCaja']} and idProceso in (10) and activo = 1 order by registro asc;";
	$resSalidas = $db->prepare($sqlSalidas);
	$resSalidas -> execute();
	while($row = $resSalidas -> fetch(PDO::FETCH_ASSOC)){
		$salidas[] = $row;
	}

	$monedas = [];
	$sqlMonedas= "SELECT * FROM `moneda` where idMoneda<> 2 order by monDescripcion";
	$resMonedas = $db->prepare($sqlMonedas);
	$resMonedas-> execute();
	while($row = $resMonedas->fetch(PDO::FETCH_ASSOC)){
		$monedas[] = $row;
	}

	//echo $res->debugDumpParams();
	if($resVentas)
		echo json_encode(array( 'ventas' => $ventas, 'ingresos' => $ingresos, 'salidas' => $salidas, 'monedas' => $monedas ));
	else echo 'error';
}
function entradaEnCaja($db){
	$entrada = json_decode($_POST['entrada'], true) ;
	
	$sqlVentas = "INSERT INTO `caja_registros`(`idCaja`, `idProceso`, `descripcion`, `monto`) VALUES ( ?, ?, ?, ?)";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['idCaja'], $entrada['idProceso'], $entrada['descripcion'], $entrada['monto']
	]);
	
	$idEntrada = $db->lastInsertId();
	//echo $res->debugDumpParams();
	if($resVentas)
		echo json_encode(array( 'idEntrada' => $idEntrada ));
	else echo 'error';
}
function borrarRegistro($db){
	//$entrada = json_decode($_POST['entrada'], true) ;
	
	$sqlVentas = "UPDATE `caja_registros` SET `activo` = '0' WHERE `id` = ? ;	";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['id']
	]);
	
	//echo $res->debugDumpParams();
	if($resVentas)
		echo 'ok';
	else echo 'error';
}
function buscarCajas($db){
	$filas = [];
	$sql = "SELECT c.*, u.usuNombres FROM `caja` c
	inner join usuario u on u.idUsuario = c.idUsuario where date_format(fechaApertura, '%Y-%m-%d') = ? order by id asc; ";
	$res = $db->prepare($sql);
	$res -> execute([ $_POST['fecha'] ]);
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
function cambiarMoneda($db){
	switch($_POST['tipo']){
		case 'venta':
			$sql = "UPDATE `fact_cabecera` SET `moneda` = ? WHERE idComprobante = ?;"; break;
		case 'ingreso':
			$sql = "UPDATE `caja_registros` SET `moneda` = ? WHERE id = ?;"; break;
		case 'salida':
			$sql = "UPDATE `caja_registros` SET `moneda` = ? WHERE id = ?;"; break;
		default: break;
	}
	
	$res = $db->prepare($sql);
	$res -> execute([
		$_POST['idMoneda'], $_POST['id']
	]);
	//echo $res->debugDumpParams();
	if($res) echo 'ok';
	else echo 'error';
}