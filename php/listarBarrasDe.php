<?php 

include "conexion.php";

$sql="SELECT * FROM `barras`
where idProducto = {$_POST['id']} and activo =1";
//echo $sql;
$resultado=$cadena->query($sql);
$filas=[];
while($row=$resultado->fetch_assoc()){ 
	$filas[] = $row;
}
echo json_encode($filas);
?>