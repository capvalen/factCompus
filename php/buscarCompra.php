<?php 

include "conexion.php";


$sql="SELECT c.*, razonsocial
FROM compras c
inner join proveedores pro on pro.id = c.idProveedor
WHERE c.idCompra = '{$_POST['id']}';";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
$sql="SELECT cd.*, prodDescripcion
FROM `compras_detalle` cd
inner join productos p on p.idProductos = cd.idProducto
WHERE cd.idCompra = '{$_POST['id']}';";
$resultado=$cadena->query($sql);
//echo $sql;
$detalles=array();
while($row=$resultado->fetch_assoc()){ 
	$detalles[]=$row;
}
echo json_encode(array( 'cabecera'=> $filas[0], 'detalles'=>$detalles));
?>