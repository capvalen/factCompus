<?php 

include "conexion.php";


$sql="SELECT `id`, `razonsocial` as razon, `ruc`, `direccion`, `celular`, `contacto` FROM `proveedores` WHERE activo = 1";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>