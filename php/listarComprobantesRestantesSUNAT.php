<?php 
include 'conexion.php';
$sql="SELECT `idFacturador`, f.`idComprobante`, case `estado` when 0 then 'Falta' else 'otro caso' end as estado, date_format(`fechaEmision`, '%d/%m/%y %h:%i %p') as factFecha, fc.factSerie, fc.factCorrelativo
FROM `facturador` f 
inner join fact_cabecera fc on fc.idComprobante = f.idComprobante
WHERE estado =0 and factTipoDocumento<>0; ";
$resultado=$cadena->query($sql); $i=1;
$respuesta = array();
while($row=$resultado->fetch_assoc()){ 
	$respuesta[] = $row;
}
echo json_encode($respuesta);	
?>