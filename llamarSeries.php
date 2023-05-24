<?php
include "php/conexion.php";
include "generales.php";

$filas = array();
$sql="SELECT * FROM `fact_series`";
$resultado=$cadena->query($sql);
$i=0;
while($row=$resultado->fetch_assoc()){ 
	$filas[$i] = $row;
	$i++;
}
echo json_encode( $filas);
?>