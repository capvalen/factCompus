<?php 
require("conexion.php");

$filas=array();
$i=0;

$sql="SELECT p.*, u.undSunat FROM `productos` p inner join unidades u on u.idUnidad = p.idUnidad where prodActivo=1";
$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ 
	$filas[$i]= $row;
	$i++;
}

echo json_encode($filas);

?>