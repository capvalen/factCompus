<?php 

include "conexion.php";


$sql="SELECT c.* , cd.*, prodDescripcion, razonsocial, c.serie as correlativo
FROM `compras_detalle` cd
inner join compras c on c.idCompra = cd.idCompra
inner join productos p on p.idProductos = cd.idProducto
inner join proveedores pro on pro.id = c.idProveedor
WHERE cd.serie = '{$_POST['serie']}';";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>