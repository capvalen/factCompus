<?php 
include "conexion.php";

$sql="UPDATE usuario set usuPass = MD5('{$_POST['clave']}') where idUsuario = {$_POST['id']};";
//echo $sql;

if($cadena->query($sql)){
   echo "todo ok";
}else{
   echo "fallo algo";
}
?>