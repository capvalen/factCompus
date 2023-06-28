<?php 

include "conexion.php";

$filtro = '';
if( filter_var( $_POST['pendientes'], FILTER_VALIDATE_BOOLEAN ) ){
	$filtro = 'etapa in (1,2)';
}else{
	$filtro = "date_format(tr.fecha, '%Y-%m-%d') = '{$_POST['fecha']}'";
}

$sql="SELECT tr.`id`, `dni`, `razon_social`, `celular`, tr.`fecha`, `idSubFamilia`, `marca`, `modelo`, `color`, `serie`, `estado`, u.usuNombres, fs.subfamilia, td.diagnostico, etapa, t.usuNombres as nomTecnico
FROM `tecnico_recepcion` tr
inner join familia_sub fs on fs.id = tr.idSubFamilia
inner join usuario u on u.idUsuario = tr.idUsuario
left join tecnico_diagnostico td on td.idRecepcion = tr.id
left join usuario t on t.idUsuario = td.idUsuario
WHERE tr.activo = 1 and {$filtro};";
$resultado=$cadena->query($sql);
//echo $sql;
$filas=array();
while($row=$resultado->fetch_assoc()){ 
	$filas[]=$row;
}
echo json_encode($filas);
?>