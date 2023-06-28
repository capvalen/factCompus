<?php 

include "conexion.php";


$sql="SELECT * FROM `familia_sub` order by subFamilia asc";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>