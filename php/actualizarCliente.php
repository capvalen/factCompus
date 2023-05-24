<?php 
include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 

//var_dump($_POST);

$sql="UPDATE `clientes` SET 
	`cliRuc`='{$_POST['cliente']['dni']}',
	`cliRazonSocial`='{$_POST['cliente']['razon']}',
	`cliDomicilio`='{$_POST['cliente']['direccion']}',
	`cliTelefono`='{$_POST['cliente']['celular']}',
	`cliCorreo`='{$_POST['cliente']['correo']}'
	WHERE `idCliente` = '{$_POST['cliente']['id']}'; ";
//echo $sql;
if($resultado=$cadena->query($sql)){
	echo 'ok';
}


?>