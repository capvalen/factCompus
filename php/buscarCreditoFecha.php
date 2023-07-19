<?php 

include "conexion.php";

if($_POST['filtro'] == 'todos') $filtro = "c.estado = 0"; //Todos los no pagados
else $filtro = 'c.fecha = "'. $_POST['inicio'].'"' ;
$sql="SELECT * FROM `creditos` c
inner join fact_cabecera fc on fc.idComprobante = c.idComprobante
where {$filtro}; ";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>