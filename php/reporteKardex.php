<table class="table table-hover pb-5 mb-5">
<thead>
	<th>N°</th>
	<th>Motivo</th>
	<th>Cantidad</th>
	<th>Fecha</th>
	<th>Usuario</th>
	<th>Obs.</th>
</thead>
<tbody>
<?php 
$_POST['fecha1']= '2019-05-28';
$_POST['fecha2']= '2019-06-07'; 
$_POST['idProd']= 1;

date_default_timezone_set('America/Lima');
include "conexion.php";

$sqlSum="SELECT sum(stoCantidad) as sumaPositiva FROM `stock`
where date_format(stoFechaMovimiento, '%Y-%m-%d') < '{$_POST['fecha1']}' AND idProceso in (1,4,5,6)
and idProducto = {$_POST['idProd']} and `stoActivo` =1; ";

$resultadoSum=$cadena->query($sqlSum);
$rowSum=$resultadoSum->fetch_assoc();
$sumaPositiva= $rowSum['sumaPositiva'];
	


$sqlRes="SELECT sum(stoCantidad) as sumaNegativa FROM `stock`
where date_format(stoFechaMovimiento, '%Y-%m-%d') < '{$_POST['fecha1']}' AND idProceso in (2,3)
and idProducto = {$_POST['idProd']} and `stoActivo` =1; ";
$resultadoRes=$cadena->query($sqlRes);
$rowRes=$resultadoRes->fetch_assoc();
$sumaNegativa= $rowRes['sumaNegativa'];

$sumResumenAnt=$sumaPositiva-$sumaNegativa;
//echo $sumaPositiva;
$i=1; $sumActPosi=0; $sumActNega=0;
$sqlMov="SELECT  `idStock`, `stoCantidad`, lower(date_format(`stoFechaMovimiento`, '%d/%m/%Y %h:%m %p')) as stoFechaMovimiento, u.usuNombres, `stoObservaciones` , prc.procDescripcion, s.idProceso FROM stock s 
inner join procesos prc on prc.idProceso = s.idProceso
inner join usuario u on u.idUsuario = s.idUsuario
where date_format(stoFechaMovimiento, '%Y-%m-%d') between '{$_POST['fecha1']}' and '{$_POST['fecha2']}' AND s.idProceso in (1,2,3,4,5,6) and idProducto = {$_POST['idProd']}
and `stoActivo` =1";
$resultadoMov=$esclavo->query($sqlMov);
while($rowMov=$resultadoMov->fetch_assoc()){
	if( in_array($rowMov['idProceso'], [1,4,5,6] )){
		$sumActPosi+=$rowMov['stoCantidad'];
	}
	if( in_array($rowMov['idProceso'], [2,3] )){
		$sumActNega+=$rowMov['stoCantidad'];
	}
	if($i==1){?>
	<tr>
		<td>0</td>
		<td>Conteo anterior</td>
		<td><?= $sumResumenAnt; ?></td>
		<td>-</td>
		<td>Sistema</td>
		<td></td>
	</tr>
<?php	}
	?>
	<tr>
		<td><?= $i;?></td>
		<td><?= $rowMov['procDescripcion']; ?></td>
		<td><?= $rowMov['stoCantidad']; ?></td>
		<td><?= $rowMov['stoFechaMovimiento']; ?></td>
		<td class="text-capitalize"><?= $rowMov['usuNombres']; ?></td>
		<td><?= $rowMov['stoObservaciones']; ?></td>
	</tr>
<?php $i++; }

$sumaGlobal = $sumResumenAnt+$sumActPosi-$sumActNega;
?>
</tbody>
<tfoot>
	<tr>
		<td></td>
		<td></td>
		<th><?php echo $sumaGlobal ?></th>
		<td></td>
		<td></td>
		<td></td>
	</tr>
</tfoot>
</table>
<div class="ml-5">
	<p><strong>Conteo anterior:</strong> <span><?= $sumResumenAnt; ?></span></p>
	<p><strong>Suma Ingresos:</strong> <span><?= $sumActPosi; ?></span></p>
	<p><strong>Suma Egresos:</strong> <span><?= $sumActNega; ?></span></p>
	<p><strong>Movimientos en el rango:</strong> <span><?= $sumActPosi-$sumActNega; ?></span></p>
	<p><strong>Saldo final:</strong> <span><?= $sumaGlobal; ?></span></p>
</div>
