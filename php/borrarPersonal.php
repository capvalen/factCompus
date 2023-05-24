<?php 
include "../conexion.php";
$sql="UPDATE `usuarios` SET `usuEstadoActivo`=0 WHERE `idUsuario`= {$_POST['idUser']}";
if($cadena->query($sql)){
   echo "todo ok";
}else{
   echo "fallo algo";
}
?>