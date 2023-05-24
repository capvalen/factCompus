<?php
include "../conexion.php";

$sql="UPDATE `productos` SET `prodPrecio`='{$_POST['diesel']}' WHERE `idProductos`= 1;
UPDATE `productos` SET `prodPrecio`='{$_POST['gasohol']}' WHERE `idProductos`= 2;";
$resultado=$cadena->multi_query($sql);
if( $resultado ){
	echo 'ok';
}else{
	echo "Hubo un error interno";
}
?>