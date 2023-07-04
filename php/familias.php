<?php 

include 'conexion.php';

switch($_POST['accion']){
	case 'cargarFamilias': cargarFamilias($datab); break;
	case 'addLinea': addLinea($datab); break;
	case 'addFamilia': addFamilia($datab); break;
	case 'eliminarLinea': eliminarLinea($datab); break;
	case 'eliminarFamilia': eliminarFamilia($datab); break;
	case 'inventarioSeries': inventarioSeries($datab); break;
}

function cargarFamilias($db){
	$categorias = [];
	$sqlCategorias = "SELECT * FROM `familias` where activo = 1";
	$resCategorias = $db->prepare($sqlCategorias);
	$resCategorias -> execute();
	while($row = $resCategorias -> fetch(PDO::FETCH_ASSOC)){
		$categorias[] = $row;
	}

	$familias = [];
	$sqlFamilias = "SELECT fs.*, f.familia FROM `familia_sub` fs inner join familias f on f.id = fs.idFamilia where fs.activo = 1";
	$resFamilias = $db->prepare($sqlFamilias);
	$resFamilias -> execute();
	while($row = $resFamilias -> fetch(PDO::FETCH_ASSOC)){
		$familias[] = $row;
	}

	if($resCategorias)
		echo json_encode(array( 'lineas' => $categorias, 'familias' => $familias ));
	else echo 'error';
}
function addLinea($db){
	
	$sqlVentas = "INSERT INTO `familias`(`familia`) VALUES ( ? )";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['linea']
	]);
	
	$idEntrada = $db->lastInsertId();
	//echo $res->debugDumpParams();
	if($resVentas)
		echo json_encode(array( 'id' => $idEntrada ));
	else echo 'error';
}
function addFamilia($db){
	
	$sqlVentas = "INSERT INTO `familia_sub`(`subfamilia`, idFamilia) VALUES ( ?, ? )";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['linea'], $_POST['idFamilia']
	]);
	
	$idEntrada = $db->lastInsertId();
	//echo $res->debugDumpParams();
	if($resVentas)
		echo json_encode(array( 'id' => $idEntrada ));
	else echo 'error';
}
function eliminarLinea($db){
	
	$sqlVentas = "UPDATE `familias` SET `activo` = '0' WHERE `familias`.`id` = ?;";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['id']
	]);
	
	//echo $res->debugDumpParams();
	if($resVentas)
		echo 'ok';
	else echo 'error';
}
function eliminarFamilia($db){
	
	$sqlVentas = "UPDATE `familia_sub` SET `activo` = '0' WHERE `id` = ?;";
	$resVentas = $db->prepare($sqlVentas);
	$resVentas -> execute([
		$_POST['id']
	]);
	
	//echo $res->debugDumpParams();
	if($resVentas)
		echo 'ok';
	else echo 'error';
}
function inventarioSeries($db){
	$inventarios = [];
	$sqlInventarios = "SELECT * FROM `barras` b
	inner join productos p on p.idProductos = b.idProducto
	where activo = 1;";
	$resInventarios = $db->prepare($sqlInventarios);
	$resInventarios -> execute();
	while($row = $resInventarios -> fetch(PDO::FETCH_ASSOC)){
		$inventarios[] = $row;
	}

	if($resInventarios)
		echo json_encode( $inventarios );
	else echo 'error';
}