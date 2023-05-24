<table class="table table-hover" id="tblProductosResultados">
<thead>
<tr>
	<th>Cod.</th>
	<th>Descripción</th>
	<th>Precio</th>
	<th>Stock</th>
	<th>Und.</th>
	<th>Afecto</th>
	<th>@</th>
</tr>
</thead>
<tbody>

<?php 

include 'conexion.php';

$sql="SELECT p.`idProductos`, lower(`prodDescripcion`) as prodDescripcion, p.`idUnidad`, `prodPrecio`, `prodStock`, p.`idGravado`, lower(u.undCorto) as undCorto, g.gravDescripcion, undSunat FROM `productos`  p
inner join unidades u on u.idUnidad = p.idUnidad
inner join gravados g on g.idGravado = p.idGravado
/*inner join codigobarras cb on p.idProductos = cb.idProducto*/
where  p.prodDescripcion like concat('%','{$_POST['texto']}', '%') /*or cb.codCodigo=''*/ and  `prodActivo`=1
group by p.idProductos
order by p.prodDescripcion asc";
$resultado=$cadena->query($sql);
$i=0;
if( $resultado->num_rows>=1 ){
while($row=$resultado->fetch_assoc()){ ?>

<tr data-id='<?= $row['idProductos'];?>'>
	<td> #<?=$row['idProductos']; ?> </td>
	<td class="text-capitalize tdNombreProd"> <?=$row['prodDescripcion']; ?> </td>
	<td class="tdPrecioProd"> <?=$row['prodPrecio']; ?> </td>
	<td> <?=$row['prodStock']; ?> </td>
	<td class="text-capitalize tdUnidad" data-und="<?= $row['undSunat'];?>"> <?=$row['undCorto']; ?> </td>
	<td class="tdGravado" data-gravado="<?= $row['idGravado'];?>"> <?=$row['gravDescripcion']; ?> </td>	
	<td> <button class="btn btn-sm btn-outline-primary border-0 btnAgregarProdCesta" data-toggle="tooltip" title="Agregar a la lista"><i class="icofont-rounded-down"></i></button> </td>
</tr>

<?php $i++; } } else{ ?>
<tr>
<td colspan="7">No se encontraron coincidencias</td>
</tr>
<?php }
?>

</tbody>
</table>