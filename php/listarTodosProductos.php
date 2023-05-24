<?php
date_default_timezone_set('America/Lima');
include "conexion.php";

$sql="SELECT p.*, case `prodActivo` when 1 then 'Activo' else 'Inactivo'end as estActivo, g.gravDescripcion, u.undDescipcion, u.undSunat  FROM `productos` p
inner join gravados g on p.idGravado = g.idGravado
inner join unidades u on u.idUnidad = p.idUnidad
WHERE `prodActivo`=1
order by p.prodDescripcion asc;"; // 

$resultado=$cadena->query($sql);
$numero = $resultado ->num_rows;
if($numero==0){ ?>
<tr>
	<td colspan="6">No existen productos.</td>
</tr>
<?php }
$i=1;
while($row=$resultado->fetch_assoc()){ 
	?> 
	<tr data-id="<?= $row['idProductos']; ?>" data-und="<?= $row['undSunat']; ?>">
		<td class='text-center'><?= $i; ?></td>
		<td class='text-capitalize tdProdNombre'><?php if($_COOKIE['ckPower']==1){echo "<button class='btn btn-outline-danger btn-sm border-0 mr-1 btnBorrarProducto'><i class='icofont-close'></i></button>";} ?><?= $row['prodDescripcion'];?></td>
		<td class='text-center tdPublico' data-value="<?= round($row['prodPrecio'],2); ?>">S/ <?= number_format($row['prodPrecio'],2); ?></td>
		<td class='text-center tdMayor' data-value="<?= round($row['prodPrecioMayor'],2); ?>">S/ <?= number_format($row['prodPrecioMayor'],2); ?></td>
		<td class='text-center tdDescuento' data-value="<?= round($row['prodPrecioDescto'],2); ?>">S/ <?= number_format($row['prodPrecioDescto'],2); ?></td>
		<td><span class="tdStock"><?= $row['prodStock'];?></span> Unds.</td>
		<td class="tdGrabado <?= ($_COOKIE['facCambiarGravado']==0 ? 'd-none': '') ?>" data-value="<?= $row['idGravado'];?>"><?= $row['gravDescripcion']; ?></td>
		<td class="<?= ($_COOKIE['facCambiarUnidad']==0 ? 'd-none': '') ?>"><?= $row['undDescipcion']; ?></td>
		<td><?= $row['estActivo']; ?></td>
		<td>
			<button class="btn btn-outline-primary btn-sm border border-light btnEditProducto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar Producto"><i class="icofont-flag"></i></button>
			<button class="btn btn-outline-dark btn-sm border border-light btnStockProducto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Modificar Stock"><i class="icofont-magic"></i></button>
			<button class="btn btn-outline-success btn-sm border border-light btnBarras" onclick="verBarrasDe(<?= $row['idProductos']; ?>)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Código de barras"><i class="icofont-bar-code"></i></button>
		</td>
	</tr>
	
<?php  $i++; }

?>