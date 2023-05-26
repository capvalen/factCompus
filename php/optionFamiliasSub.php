<?php 

include "conexion.php";

$filas = array();
$sql="SELECT * FROM `familia_sub` where activo=1 order by subfamilia ;";
$resultado=$cadena->query($sql);
$i=0;
while($row=$resultado->fetch_assoc()){ 
	$filas[$i] = $row;
	$i++;
}
echo json_encode( $filas);

?>
