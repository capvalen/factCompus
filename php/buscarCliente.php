<?php 

include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 

$sql="SELECT * FROM `clientes`
where cliRuc ='{$_POST['texto']}' or cliRazonSocial like concat('%', '{$_POST['texto']}', '%') and cliActivo=1";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>