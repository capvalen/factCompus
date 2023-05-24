<?php 
include "conexion.php";
$sqlStock='';
switch ($_POST['proceso']) {
	case '1':
		$sqlStock = "UPDATE `productos` SET `prodStock` = `prodStock` + {$_POST['cantidad']}
		where  `idProductos`= {$_POST['idProd']};";
		break;
	case '2':
		$sqlStock = "UPDATE `productos` SET `prodStock` = `prodStock` - {$_POST['cantidad']}
		where  `idProductos`= {$_POST['idProd']};";
		break;
	
	default:
		# code...
		break;
}

$sql="INSERT INTO `stock`(`idStock`, `idProducto`, `idProceso`, `stoCantidad`, `stoFechaMovimiento`, `idUsuario`, `stoActivo`, `stoObservaciones`) VALUES 
(null, {$_POST['idProd']}, {$_POST['proceso']},{$_POST['cantidad']},now(),{$_COOKIE['ckidUsuario']},1, '{$_POST['obs']}'); " . $sqlStock ;
$resultado=$cadena->multi_query($sql);


echo "ok";
 ?>