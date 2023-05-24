<?php 

include "conexion.php";

$sql="SELECT *, case `prodActivo` when 1 then 'Activo' else 'Inactivo'end as estActivo FROM `productos` p
inner join gravados g on p.idGravado = g.idGravado
inner join unidades u on u.idUnidad = p.idUnidad
where prodDescripcion like concat('%' , '{$_POST['texto']}' , '%') and prodActivo=1 ;";
//echo $sql;
$i=1;

$resultado=$cadena->query($sql);
$filas=array();
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


$sqlBarras="SELECT p.*, case `prodActivo` when 1 then 'Activo' else 'Inactivo'end as estActivo FROM `productos` p
inner join barras b on b.idProducto = p.idProductos
inner join gravados g on p.idGravado = g.idGravado
inner join unidades u on u.idUnidad = p.idUnidad
where b.barra = '{$_POST['texto']}' and b.activo = 1";

$resultadoBarras=$cadena->query($sqlBarras);
while($rowBarras=$resultadoBarras->fetch_assoc()){ 
	?> 
	<tr data-id="<?= $rowBarras['idProductos']; ?>" data-und="<?= $rowBarras['undSunat']; ?>">
		<td class='text-center'><?= $i; ?></td>
		<td class='text-capitalize tdProdNombre'><?php if($_COOKIE['ckPower']==1){echo "<button class='btn btn-outline-danger btn-sm border-0 mr-1 btnBorrarProducto'><i class='icofont-close'></i></button>";} ?><?= $rowBarras['prodDescripcion'];?></td>
		<td class='text-center tdPublico' data-value="<?= round($rowBarras['prodPrecio'],2); ?>">S/ <?= number_format($rowBarras['prodPrecio'],2); ?></td>
		<td class='text-center tdMayor' data-value="<?= round($rowBarras['prodPrecioMayor'],2); ?>">S/ <?= number_format($rowBarras['prodPrecioMayor'],2); ?></td>
		<td class='text-center tdDescuento' data-value="<?= round($rowBarras['prodPrecioDescto'],2); ?>">S/ <?= number_format($rowBarras['prodPrecioDescto'],2); ?></td>
		<td><span class="tdStock"><?= $rowBarras['prodStock'];?></span> Unds.</td>
		<td class="tdGrabado <?= ($_COOKIE['facCambiarGravado']==0 ? 'd-none': '') ?>" data-value="<?= $row['idGravado'];?>"><?= $row['gravDescripcion']; ?></td>
		<td class="<?= ($_COOKIE['facCambiarUnidad']==0 ? 'd-none': '') ?>"><?= $row['undDescipcion']; ?></td>
		<td><?= $rowBarras['estActivo']; ?></td>
		<td>
			<button class="btn btn-outline-primary btn-sm border border-light btnEditProducto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Editar Producto"><i class="icofont-flag"></i></button>
			<button class="btn btn-outline-dark btn-sm border border-light btnStockProducto" data-toggle="tooltip" data-placement="top" title="" data-original-title="Modificar Stock"><i class="icofont-magic"></i></button>
			<button class="btn btn-outline-success btn-sm border border-light btnBarras" onclick="verBarrasDe(<?= $rowBarras['idProductos']; ?>)" data-toggle="tooltip" data-placement="top" title="" data-original-title="Código de barras"><i class="icofont-bar-code"></i></button>
		</td>
	</tr>
	
<?php  $i++; }




?>