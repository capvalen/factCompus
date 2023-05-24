<?php 
include "conexion.php";
$_POST = json_decode(file_get_contents('php://input'),true); 


$sql="INSERT INTO `clientes`(`idCliente`, `cliRuc`, `cliRazonSocial`, `cliComercial`, `cliDomicilio`, `cliTelefono`, `cliCorreo`, `cliActivo`, `esProveedor`) VALUES 
(null, '{$_POST['cliente']['dni']}', '{$_POST['cliente']['razon']}', '', '{$_POST['cliente']['direccion']}', '{$_POST['cliente']['celular']}', '{$_POST['cliente']['correo']}', 1, 0)";
if($resultado=$cadena->query($sql)){
	echo $cadena->insert_id;
}

?>