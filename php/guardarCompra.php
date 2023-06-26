<?php 
include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true);

$cabeza = $_POST['cabecera'];
//var_dump($_POST); die();

$sql="INSERT INTO `compras`(`idOrigen`, `idComprobante`, `fecha`, `serie`, `idProveedor`, 
`idUsuario`,`bultos`) VALUES 
({$cabeza['origen']}, {$cabeza['comprobante']}, '{$cabeza['fecha']}', '{$cabeza['correlativo']}', {$cabeza['proveedor']},
{$_COOKIE['ckidUsuario']}, {$cabeza['bultos']}
)";

$resultado=$cadena->query($sql);
$idCabecera = $cadena->insert_id;

$producto = $_POST['cesta'];
//print_r($producto); die();
$sqlProd='';
for ($i=0; $i < count($producto) ; $i++) {
	$sqlProd = "INSERT INTO `compras_detalle`(`idCompra`,`idProducto`, `cantidad`, `serie`, `precioUnitario`, `subTotal`) VALUES (?,?,?,?,?,?);";
	$sentProd = $datab->prepare($sqlProd);
	//print_r( "{$idCabecera} {$producto[$i]['id']}, {$producto[$i]['cantidad']}, {$producto[$i]['series']}, {$producto[$i]['precioCompra']}, {$producto[$i]['cantidad']}, {$producto[$i]['precioCompra']}\n");
	$sentProd -> execute([
		$idCabecera, $producto[$i]['id'], $producto[$i]['cantidad'], $producto[$i]['series'], $producto[$i]['precioCompra'], $producto[$i]['cantidad'] * $producto[$i]['precioCompra']
	]);
	$sentProd->closeCursor();
	
	//echo "Sentencia SQL prod: \n" . $sentProd->debugDumpParams();
		
	if( $producto[$i]['series']<>'' && $producto[$i]['series']<>0  ){
		$sqlSerie = "INSERT IGNORE INTO `barras`(`idProducto`, `barra`, `activo`) VALUES (?, ?, ?)";
		$sentSerie = $datab->prepare($sqlSerie);
		$sentSerie -> execute([
			$producto[$i]['id'], $producto[$i]['series'], 1
		]);
		$sentSerie->closeCursor();
		//echo "Sentencia SQL serie: \n" . $sentSerie->debugDumpParams();
	}
	

	$sqlProd= "INSERT INTO `stock`( `idProducto`, `idProceso`, `stoCantidad`, `stoFechaMovimiento`, `idUsuario`, `stoActivo`, `stoObservaciones`) VALUES (
		{$producto[$i]['id']},4, {$producto[$i]['cantidad']}, '{$cabeza['fecha']}', {$_COOKIE['ckidUsuario']}, 1, '');";
		//echo $sqlProd;
	$esclavo->query($sqlProd);
	$sqlUpd="UPDATE `productos` SET `prodStock` =  `prodStock` + {$producto[$i]['cantidad']}, precioCompra = {$producto[$i]['precioCompra']} where `idProductos`={$producto[$i]['id']};";
	$conf->query($sqlUpd);
}
//echo $sqlProd; die();
echo 'ok';

?>