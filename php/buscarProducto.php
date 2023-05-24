<?php 

include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `productos` p
where prodDescripcion like concat('%' , '{$_POST['texto']}' , '%') and prodActivo=1 ;";

$resultado=$cadena->query($sql);
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
$sqlBarras="SELECT p.* FROM `productos` p
inner join barras b on b.idProducto = p.idProductos
where b.barra = '{$_POST['texto']}' and b.activo = 1";

$resultadoBarras=$cadena->query($sqlBarras);
while($rowBarras=$resultadoBarras->fetch_assoc()){ 
	$filas[]=$rowBarras;
}

echo json_encode($filas);
?>