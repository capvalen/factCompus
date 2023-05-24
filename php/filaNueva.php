<?php
include "conexion.php";
$sql="SELECT `idProductos`, `prodDescripcion`, p.`idUnidad`, `prodPrecio`, `prodActivo`, u.undDescipcion
FROM `productos` p inner join unidades u on u.idUnidad = p.idUnidad 
WHERE `prodActivo`=1";
$resultado=$cadena->query($sql);

$sqlUnd="SELECT `idUnidad`, `undDescipcion`, `undSunat`, `undCorto` FROM `unidades` WHERE undActivo =1;";
$resultadoUnd=$esclavo->query($sqlUnd);

?>

<div class="row my-2 m-md-0 mb-md-1 cardHijoProducto" data-producto="1">

	<div class="col-12 col-md-4 divNombProducto d-flex">
		<span class="pr-2 pt-1 borrarFila" style="color: #ff0202; cursor:pointer"><i class="icofont-close"></i></span>
		<select class="selectpicker sltFiltroProductos flex-grow-1" id="sltTemporal" data-live-search="true" title="&#xed12; Concepto" data-width="100%">
		<?php 
		while($row=$resultado->fetch_assoc()){ ?>
			<option value="<?= $row['idProductos'];?>"><?= $row['prodDescripcion'];?></option>
		<?php 
		}		
		?>
		</select>
		<input type="text" class="d-none form-control campoTextoLibre text-capitalize" value="" placeholder='Concepto de venta' >
	</div>
	<?php if($_COOKIE['verCantidad']==0):?>
		<div class="col-6 col-md-1 d-none"><input type="number" class="form-control text-center esMoneda campoCantidad" value="1" step="1" min="0"></div>
	<?php else: ?>
		<div class="col-6 col-md-1 "><input type="number" class="form-control text-center esMoneda campoCantidad" value="0" step="1" min="0"></div>
		<?php endif;?>
	<div class="col-6 col-md-1  divUnidadProducto <?= ($_COOKIE['facCambiarUnidad']=='1' ? 'pasa' : 'd-none') ?>">
		<select class="selectpicker sltFiltroUnidad" id="sltfiltroTemporal" data-live-search="true" title="&#xed12; Unds." data-width="100%">
			<?php 
			while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
			<option value="<?= $rowUnd['undSunat'];?>" data-unidad="<?= $rowUnd['undCorto']?>"><?= $rowUnd['undDescipcion'];?></option>
			<?php 
			}		
			?>
			</select>
	</div>
	
	<div class="col-6 col-md-2 divGrabados <?= ($_COOKIE['facCambiarGravado']=='1' ? 'pasa' : 'd-none') ?>">
		<select class="selectpicker" data-live-search="false" id="sltFiltroGravado" title="&#xed12; Imposición" data-width="100%" disabled>
			<option value="1">Afecto</option>
			<option value="2">Exonerado</option>
		</select>
	</div>
	<div class="col-6 col-md-2">
		<select class="selectpicker sltFiltroPrecios" data-live-search="false" id="sltFiltroPrecios" title="&#xed12; Precios" data-width="100%" >
			<?php if($_COOKIE['precioLibre']==1): ?><option class="optPrecios" value="0">Libre</option><?php endif; ?>
			<?php if($_COOKIE['precioPublico']==1): ?><option class="optPrecios" value="1">Público</option><?php endif; ?>
			<?php if($_COOKIE['precioMayorista']==1): ?><option class="optPrecios" value="2">Mayorista</option><?php endif; ?>
			<?php if($_COOKIE['precioDescuento']==1): ?><option class="optPrecios" value="3">Descuento</option><?php endif; ?>
		</select>
	</div>
	<div class="col-6 col-md-2"><input type="number" class="form-control text-center esMoneda campoPrecioUnit" id="txtPrecioPetroleo" step='0.1' min="0" value="0.00" ></div>
	<div class="col-6 col-md-2 d-none"><input type="number" class="form-control text-center esMoneda campoSubTotal" readonly id="txtCampoPrecioPetroleo" value="0.00" data-Exonerado="1"></div>
</div>