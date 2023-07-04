<?php 
include "conexion.php";



$sqlDuplicado="SELECT b.*, p.prodDescripcion FROM `barras` b
inner join productos p on b.idProducto = p.idProductos
where barra = '{$_POST['barra']}' and activo =1;";
$resultadoDuplicado=$esclavo->query($sqlDuplicado);
if($resultadoDuplicado->num_rows>=1){
	$rowDuplicado=$resultadoDuplicado->fetch_assoc();
	echo "duplicado";
}else{
	$sql="INSERT INTO `barras` (`idBarra`, `idProducto`, `barra`, `activo`) VALUES (NULL, '{$_POST['idProducto']}', '{$_POST['barra']}', '1');";
	if($resultado=$cadena->query($sql)){

		$_POST['idProd']=$_POST['idProducto'];
		$_POST['proceso']='1';
		$_POST['cantidad']=1;
		$_POST['obs']='';
		require __DIR__. '/updateStock.php';
		
		echo "ok";
	}else{
		echo "error";
	}
}

?>