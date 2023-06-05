<?php 
include "conexion.php";

$provider = json_decode($_POST['proveedor'], true);
//var_dump($provider); die();

$sql="INSERT INTO `proveedores`(`razonsocial`, `ruc`, `direccion`, `celular`, `contacto`) 
VALUES ('{$provider['razon']}','{$provider['ruc']}','{$provider['direccion']}', '{$provider['celular']}','{$provider['contacto']}');";
//echo $sql;

if($cadena->query($sql)){
	$id = $cadena->insert_id;
   echo json_encode(array('msg'=> $id));
}else{
   echo "fallo algo";
}
?>