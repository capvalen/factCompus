<?php
date_default_timezone_set('America/Lima');
include "conexion.php";

$comprobantes=[1,3,12];

if( isset($_POST['fecha'])){
	$fecha = $_POST['fecha'];
	if( isset($_POST['fecha2'])){
		$fecha2 = $_POST['fecha2']; 
	}else{ 
		$fecha2 = $_POST['fecha'];
	}

	$sql="SELECT `idComprobante`, `factTipoDocumento`, case `factTipoDocumento` when 1 then 'Factura' when 3 then 'Boleta' when 0 then 'Interno' when -1 then 'Proforma' end as 'queDoc', `factSerie`, `factCorrelativo`, `tipOperacion`, `fechaEmision`, `horaEmision`, `fechaVencimiento`, `codLocalEmisor`, `tipDocUsuario`, `dniRUC`, lower(`razonSocial`) as razonSocial, `tipoMoneda`, `costoFinal`, `IGVFinal`, `totalFinal`, `sumDescTotal`, `sumOtrosCargos`, `sumTotalAnticipos`, `sumImpVenta`, `ublVersionId`, `customizationId`, `ideTributo`, `nomTributo`, `codTipTributo`, `mtoBaseImponible`, `mtoTributo`, `codLeyenda`, `desLeyenda`, comprobanteEmitido, case `comprobanteEmitido` when 1 then 'Generar XML' when 0 then 'Sin emitir' when '2' then 'De baja' when '3' then 'Enviado a SUNAT' when 4 then 'Borrado' end as comprobanteEmitidoDescr, `comprobanteFechado`, `cliDireccion`, `motivoBaja`, esContado, adelanto FROM `fact_cabecera` WHERE  `fechaEmision` between '{$fecha}' and '{$fecha2}';";
}else{
	$fecha = date('Y-m-d');

	$sql="SELECT `idComprobante`, `factTipoDocumento`, case `factTipoDocumento` when 1 then 'Factura' when 3 then 'Boleta' when 0 then 'Interno' when -1 then 'Proforma' end as 'queDoc', `factSerie`, `factCorrelativo`, `tipOperacion`, `fechaEmision`, `horaEmision`, `fechaVencimiento`, `codLocalEmisor`, `tipDocUsuario`, `dniRUC`, lower(`razonSocial`) as razonSocial, `tipoMoneda`, `costoFinal`, `IGVFinal`, `totalFinal`, `sumDescTotal`, `sumOtrosCargos`, `sumTotalAnticipos`, `sumImpVenta`, `ublVersionId`, `customizationId`, `ideTributo`, `nomTributo`, `codTipTributo`, `mtoBaseImponible`, `mtoTributo`, `codLeyenda`, `desLeyenda`, comprobanteEmitido, case `comprobanteEmitido` when 1 then 'Generar XML' when 0 then 'Sin emitir' when '2'  then 'De baja' when '3' then 'Enviado a SUNAT' when 4 then 'Borrado' end as comprobanteEmitidoDescr, `comprobanteFechado`, `cliDireccion`, `motivoBaja`, esContado, adelanto FROM `fact_cabecera` WHERE  `fechaEmision` = '{$fecha}';";
}




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
	$hora = new DateTime($row['horaEmision']);
	?>
	<tr>
		<td><?= $i; ?></td>
		
		<td><?= $row['queDoc']; ?></td>
		<td class="tdCorrelativo tableexport-string"><?= ($row['queDoc']=='Interno' || $row['queDoc']=='Proforma' )? $row['factCorrelativo'] : $row['factSerie']."-".$row['factCorrelativo']; ?></td>
	<?php if( isset($_POST['fecha2'])): ?>
		<td data-sort-value="<?php echo $hora->format('ymd'); ?>"><?php echo $fEmision1->format('d/m/Y')." ". $hora->format('h:i a'); ?></td>
	<?php else: ?>
		<td data-sort-value="<?php echo $hora->format('Hi'); ?>"><?php echo $hora->format('h:i a'); ?></td>
	<?php endif; ?>

		<td class="text-capitalize"><?= $row['razonSocial']; ?></td>

		<td class="tableexport-string">S/ <?= number_format($row['IGVFinal'],2); ?></td>
		<td class="tableexport-string">S/ <span class="spTotalPac" data-estado="<?= $row['comprobanteEmitido']; ?>"><?= number_format($row['totalFinal'],2); ?></span></td>
		<td class="text-capitalize">
			<?php if($row['comprobanteEmitido']==0){ echo "<span class='badge badge-secondary'> <i class='icofont-safety'></i> {$row['comprobanteEmitidoDescr']}</span>"; }
				else if( in_array($row['comprobanteEmitido'], [2,4]) ){ echo "<span class='badge badge-danger'> <i class='icofont-delete'></i> ".$row['comprobanteEmitidoDescr']."</span> <br><small class='text-danger'>{$row['motivoBaja']}</small>";} 
				else if($row['comprobanteEmitido']==3) { echo "<span class='badge badge-success'><i class='icofont-safety'></i> ".$row['comprobanteEmitidoDescr']. "</span>";}
				else {
					if($row['factSerie']!=''){
						echo "<span class='badge badge-secondary'><i class='icofont-spinner-alt-2'></i> ".$row['comprobanteEmitidoDescr']. "</span>";
					}
				}
			?>
		</td>
		<td><?= ( $row['esContado']=='1' )? 'Contado' : 'Crédito'; ?></td>
		<td><?= number_format(( $row['esContado']=='1' )? '0' : $row['totalFinal'] - $row['adelanto'], 2); ?> </td>
		<?php if(in_array( $row['factTipoDocumento'], $comprobantes)){ ?>
			<td data-caso="<?= $row['factTipoDocumento']; ?>" data-serie="<?= $row['factSerie']; ?>" data-correlativo="<?= $row['factCorrelativo']; ?>" style="display: flex;">
				<?php 
				if($row['comprobanteEmitido']==0 && $fecha == date('Y-m-d')){ ?>
					<button class="btn btn-outline-primary btn-sm border border-light btnGenComprobante" data-ticket="<?= $row['idTicket']; ?>" data-toggle="tooltip" data-placement="top" title="Generar comprobante"><i class="icofont-flag"></i></button>
				<?php }
				else if($row['comprobanteEmitido']==1 || $row['comprobanteEmitido']==3 ){ ?>
					<button class="btn btn-outline-secondary btn-sm border border-light imprTicketFuera" data-toggle="tooltip" data-placement="top" title="Imprimir ticket"><i class="icofont-paper"></i></button>
					<button class="btn btn-outline-secondary btn-sm border border-light imprA4Fuera d-none d-sm-block" data-toggle="tooltip" data-placement="top" title="Imprimir A4"><i class="icofont-print"></i></button>
					<button class="btn btn-outline-secondary btn-sm border border-light imprPDFFuera d-block d-sm-none" data-toggle="tooltip" data-placement="top" title="Imprimir PDF"><i class="icofont-print"></i></button>
					<!-- Boton compartir -->
					<button class="btn btn-outline-primary btn-sm border border-light  d-block d-sm-none" onclick="compartir(`<?= $row['factSerie']?>`, `<?= $row['factCorrelativo'];?>`)" data-toggle="tooltip" data-placement="top" title="Compartir"><i class="icofont-share"></i></button>
					<!-- Fin Boton compartir -->
					


				<?php if($_COOKIE['ckPower']==1){ ?>
					<button class="btn btn-outline-danger btn-sm border border-light btnDarBajas" data-toggle="tooltip" data-placement="top" title="Dar de baja" data-boleta="<?= $row['factTipoDocumento'];?>" data-baja="<?= $row['idComprobante'];?>"><i class="icofont-download-alt"></i></button>
				
				<?php }
				}else{
					echo '<button class="btn btn-outline-secondary btn-sm border border-light imprA4Fuera" data-toggle="tooltip" data-placement="top" title="Imprimir A4"><i class="icofont-print"></i></button>';
				}
				?>
			</td>
		<?php }else{ ?>
			<td data-caso="<?= $row['factTipoDocumento']; ?>" data-serie="<?= $row['factSerie']; ?>" data-correlativo="<?= $row['factCorrelativo']; ?>" style="white-space: nowrap;">
				<button class="btn btn-outline-secondary btn-sm border border-light imprTicketFuera" data-toggle="tooltip" data-placement="top" title="Imprimir ticket"><i class="icofont-paper"></i></button>
				<button class="btn btn-outline-secondary btn-sm border border-light imprA4Fuera" data-toggle="tooltip" data-placement="top" title="Imprimir A4"><i class="icofont-print"></i></button>
				<button class="btn btn-outline-warning btn-sm border border-light " onclick="borrarExtra('<?= $row['idComprobante']?>')" data-toggle="tooltip" data-placement="top" title="Borrar interno"><i class="icofont-ui-rate-remove"></i></button>
				<button class="btn btn-outline-primary btn-sm border border-light " onclick="prepararTransformacion('<?= $row['idComprobante']?>')" data-toggle="tooltip" data-placement="top" title="Convertir en..."><i class="icofont-stylish-up"></i></button>
			</td>
		<?php } ?>
	</tr>
<?php  $i++; }

?>