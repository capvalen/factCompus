<?php 

include "conexion.php";


$sql="SELECT `idUsuario`, `usuNombres`, `usuApellido`, `usuNick` FROM `usuario` WHERE `usuActivo` = 1 order by usuNombres";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>