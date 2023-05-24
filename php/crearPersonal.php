<?php 
include "conexion.php";

$sql="INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `usuActivo`) 
VALUES (null,'{$_POST['nombre']}','{$_POST['apellido']}','{$_POST['nick']}', md5('{$_POST['passw']}'),'{$_POST['poder']}',1)";
//echo $sql;

if($cadena->query($sql)){
   echo "todo ok";
}else{
   echo "fallo algo";
}
?>