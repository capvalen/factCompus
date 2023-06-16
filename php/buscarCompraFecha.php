<?php 

include "conexion.php";


$sql="SELECT c.*, razonsocial, serie as correlativo
FROM compras c
inner join proveedores pro on pro.id = c.idProveedor
WHERE c.fecha between '{$_POST['inicio']}' and '{$_POST['fin']}'";
//echo $sql;
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>