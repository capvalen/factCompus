<?php 

include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `padron`
where RUC ='{$_POST['texto']}'";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>