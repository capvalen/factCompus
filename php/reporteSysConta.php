<table class="table table-hover mt-3" id="tablaSysConta" >
	<thead>
		<tr>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Fecha Emisión</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Fecha de Vencimiento</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo Comp.</th>
			<th data-sort="int"><i class="icofont-expand-alt"></i> Serie</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Correlativo</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo Doc.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Número</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Razón Social</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Valor Exportación</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Base Imponible</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Exonerada</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Inafecta</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> ISC</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> I.G.V.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Otros Trib.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Importe total</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo de cambio</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Fecha Ref.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo Ref.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Serie Ref.</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> N° Comprobante</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Moneda</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Dólares</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Fecha Vencimiento</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Condición</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Contado</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Adelanto</th>
			<th data-sort="string"><i class="icofont-expand-alt"></i> Estado</th>
		</tr>
	</thead>
	<tbody>

<?php 

include "conexion.php";


$sql= "SELECT date_format(`fechaEmision`, '%d/%m/%Y') as fechaEmision, `factTipoDocumento`, FORMAT(totalFinal,2) as totalFinal, `factSerie`,`factCorrelativo`, `tipDocUsuario`, `dniRUC`, upper(`razonSocial`) as `razonSocial`, 
`totalFinal`, `factExonerados`, `costoFinal`, `IGVFinal`, case `comprobanteEmitido` when 1 then 'Emitido' when 0 then 'Sin emitir' when '2' then 'De baja' end as comprobanteEmitidoDescr, esContado, adelanto
FROM `fact_cabecera` WHERE `fechaEmision` BETWEEN '{$_POST['fecha1']}' and '{$_POST['fecha2']}' and factSerie<>''; ";
$resultado=$cadena->query($sql);
if($resultado->num_rows>=1){


while($row=$resultado->fetch_assoc()){ ?> 

<tr>
	<td class="tableexport-string"> <?= $row['fechaEmision'];?> </td>
	<td class="tableexport-string"> <?= $row['fechaEmision'];?> </td>
	<td class="tableexport-string"> 0<?= $row['factTipoDocumento'];?> </td>
	<td> <?= $row['factSerie'];?> </td>
	<td> <?= $row['factCorrelativo'];?> </td>
	<td> <?= $row['tipDocUsuario'];?> </td>
	<td class="tableexport-string"> <?= $row['dniRUC'];?> </td>
	<td style="white-space: nowrap;"> <?= $row['razonSocial'];?> </td>
	<td> 0.00 </td>
	<td class="tableexport-number"> <?= str_replace(',','',$row['costoFinal']);?> </td>
	<td> <?= str_replace(',','',number_format($row['factExonerados'],2)); ?> </td>
	<td> 0.00 </td>
	<td> 0.00 </td>
	<td class="tableexport-number"> <?= str_replace(',','',$row['IGVFinal']);?> </td>
	<td> 0.00 </td>
	<td class="tableexport-number"> <?= str_replace(',','',number_format($row['totalFinal'],2));?> </td>
	<td> 0.00 </td>
	<td> 00/00/0000 </td>
	<td> </td>
	<td> </td>
	<td> </td>
	<td> 1 </td>
	<td> 0.00 </td>
	<td> 00/00/0000 </td>
	<td> - </td>
	<td> <?= ( $row['esContado']=='1' )? 'Contado' : 'Crédito'; ?> </td>
	<td> <?= ( $row['esContado']=='1' )? 0 : $row['totalFinal'] - $row['adelanto']; ?> </td>
	<td> <?= $row['comprobanteEmitidoDescr']; ?> </td>
</tr>
<?php 
}
}else{ //end de if rows  ?>
<tr><td colspan="15">No se encuentra registros entre las fechas <?= $_POST['fecha1'] .' y '.$_POST['fecha2']?></td></tr>
<?php 
}
?>
	</tbody>
</table>
