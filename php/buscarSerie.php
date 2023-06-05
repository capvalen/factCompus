<?php 

include "conexion.php";


$sql="SELECT fd.*, fc.razonSocial, fc.factTipoDocumento, factSerie, factCorrelativo FROM `fact_detalle` fd
inner join fact_cabecera fc on fc.idComprobante = fd.idCabecera WHERE serie = '{$_POST['serie']}'
and factTipoDocumento<>-1
order by codItem desc";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>