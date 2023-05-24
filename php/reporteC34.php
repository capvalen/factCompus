<table class="table table-light" id="tablaSysCont">
	<thead class="thead-dark">
		<tr>
			<th>#</th>
			<th>Comprobante</th>
			<th>Serie-Correlativo</th>
			<th>Fecha Emis.</th>
			<th>R.U.C.</th>
			<th>Razón social</th>
			<th>Código</th>
			<th>Nombre</th>
			<th>Cantidad</th>
			<th>Valor Unit.</th>
			<th>Igv Unit.</th>
			<th>Sub Total</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
<?php
date_default_timezone_set('America/Lima');
include "conexion.php";

if( isset($_POST['fecha'])){
	$fecha = $_POST['fecha'];
	if( isset($_POST['fecha2'])){
		$fecha2 = $_POST['fecha2']; 
	}else{ 
		$fecha2 = $_POST['fecha'];
	}

	$sql="SELECT substr(descripcionItem, 1, position('-' in descripcionItem)-1) as codigo,
	substr(descripcionItem, position('-' in descripcionItem)+1, LENGTH(descripcionItem)) as nomProd, fc.dniRUC, fc.factTipoDocumento, c.compDescripcion, fc.razonSocial, fc.IGVFinal, fc.totalFinal, fc.comprobanteEmitido,
	`descripcionItem`, `facSerieCorre`, `codUnidadMedida`, `cantidadItem`, `valorUnitario`, `valorExonerado`, `igvUnitario`, `mtoIgvItem`, `valorItem`, `mtoPrecioVenta`, `mtoValorVenta`, fc.`fechaEmision`, `idGravado`, sumImpVenta
	FROM `fact_detalle`fd
	inner join fact_cabecera fc on fd.facSerieCorre = concat(fc.factSerie, '-', fc.factCorrelativo)
	inner join comprobante c on c.idComprobante = fc.factTipoDocumento
	WHERE  fc.`fechaEmision` between '{$fecha}' and '{$fecha2}';";
}
//echo $sql;



$resultado=$cadena->query($sql);
$numero = $resultado ->num_rows;
if($numero==0){ ?>
<tr>
	<td colspan="6">No hay comprobantes emitidos en ésta fecha.</td>
</tr>
<?php }
$i=1;
while($row=$resultado->fetch_assoc()){ 
	$fEmision1 = new DateTime($row['fechaEmision']);
	?>
	<tr>
		<td><?= $i; ?></td>
		
		<td><?= $row['compDescripcion']; ?></td>
		<td class="tdCorrelativo"><?= $row['facSerieCorre']; ?></td>
		<td data-sort-value="<?php echo $fEmision1->format('ymd'); ?>"><?php echo $fEmision1->format('d/m/Y')." "; ?></td>

		<td class="text-capitalize"><?= $row['dniRUC']; ?></td>
		<td class="text-capitalize"><?= $row['razonSocial']; ?></td>
		<td class="text-capitalize"><?= $row['codigo']; ?></td>
		<td class="text-capitalize"><?= $row['nomProd']; ?></td>
		<td class="text-capitalize"><?= $row['cantidadItem']; ?></td>
		<td class="text-capitalize"><?= $row['mtoValorVenta']; ?></td>
		<td class="text-capitalize"><?= $row['igvUnitario']; ?></td>
		<td class="text-capitalize"><?= $row['mtoPrecioVenta']; ?></td>
		<td class="text-capitalize"><?php if($row['comprobanteEmitido']==1){echo 'Emitido';}else if($row['comprobanteEmitido']==2){echo 'De Baja';}else{echo 'Sin emitir';}?></td>
		
		
	</tr>
<?php  $i++; } ?>
</tbody>
</table>