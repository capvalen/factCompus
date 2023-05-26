<?php 

include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `productos` p
where prodDescripcion like concat('%' , '{$_POST['texto']}' , '%') and prodActivo=1 ;";

$resultado=$cadena->query($sql);
$productos=array(); $serie=array();

while($row=$resultado->fetch_assoc()){ 
	$productos[]=$row;
}
$sqlBarras="SELECT p.*, b.barra FROM `productos` p
inner join barras b on b.idProducto = p.idProductos
where b.barra = '{$_POST['texto']}' and b.activo = 1";

$resultadoBarras=$cadena->query($sqlBarras);
while($rowBarras=$resultadoBarras->fetch_assoc()){ 
	$serie[]=$rowBarras;
}

//echo json_encode( $productos );
echo json_encode( array('productos'=> $productos, 'serie'=>$serie) );
?>