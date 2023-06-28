<?php 

include 'conexion.php';
include '../generales.php';

switch($_POST['accion']){
	case 'registrar': registrar($datab); break;
	case 'registrar-diagnostico': registrarDiagnostico($datab); break;
	case 'registrar-pago': registrarPago($datab); break;
}

function registrar($db){
	$cliente = json_decode($_POST['cliente'], true);
	$recepcion = json_decode($_POST['recepcion'], true);
	
	$sql = "INSERT INTO `tecnico_recepcion`(`dni`, `razon_social`, `idSubFamilia`, `marca`, `modelo`,
	`color`, `serie`, `estado`, `idUsuario`, `celular`) VALUES (
		?, ?, ?, ?, ?,
		?, ?, ?, ?, ?
	)";
	$res = $db->prepare($sql);
	$res -> execute([
		$cliente['dni'], $cliente['razon_social'], $recepcion['idSubFamilia'], $recepcion['marca'], $recepcion['modelo'],
		$recepcion['color'], $recepcion['serie'], $recepcion['estado'], $_COOKIE['ckidUsuario'], $cliente['celular']
	]);
	//echo $res->debugDumpParams();
	if($res) echo $db->lastInsertId();
	else echo 'error';
}

function registrarDiagnostico($db){
	$diagnostico = json_decode($_POST['diagnostico'], true);

	$sql = "INSERT INTO `tecnico_diagnostico`(`idRecepcion`, `idUsuario`, `diagnostico`) VALUES (?, ?, ?);";
	$sqlEstado = "UPDATE `tecnico_recepcion` SET `etapa` = '2' WHERE `tecnico_recepcion`.`id` = ?;";
	$res = $db->prepare($sql);
	$resEstado = $db->prepare($sqlEstado);
	$res -> execute([
		$diagnostico['idRecepcion'], $diagnostico['idUsuario'], $diagnostico['diagnostico']
	]);
	$resEstado -> execute([ $diagnostico['idRecepcion'] ]);
	//echo $res->debugDumpParams();
	
	if($res) echo 'ok';
	else echo 'error';
}
function registrarPago($db){

	$sql = "INSERT INTO `tecnico_monto`(`idRecepcion`, `monto`,`idUsuario`) VALUES (?, ?, ?);";
	$sqlEstado = "UPDATE `tecnico_recepcion` SET `etapa` = '3' WHERE `tecnico_recepcion`.`id` = ?;";
	$res = $db->prepare($sql);
	$resEstado = $db->prepare($sqlEstado);
	$res -> execute([
		 $_POST['idRecepcion'], $_POST['monto'], $_COOKIE['ckidUsuario']
	]);
	$resEstado -> execute([ $_POST['idRecepcion'] ]);
	//echo $res->debugDumpParams();
	
	if($res) echo 'ok';
	else echo 'error';
}