<?php
include "../conexion.php";

$filas = array();
$sql="SELECT * FROM `productos`";
$resultado=$cadena->query($sql);
$i=0;
while($row=$resultado->fetch_assoc()){ 
	$filas[$i] = $row;
	$i++;
}
echo json_encode( $filas);
?>