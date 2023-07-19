<?php 
include "conexion.php";
$sql="UPDATE `usuario` SET `usuActivo`=0 WHERE `idUsuario`= {$_POST['idUser']}";
if($cadena->query($sql)){
   echo "todo ok";
}else{
   echo "fallo algo";
}
?>