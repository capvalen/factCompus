<?php
$sql="SELECT * FROM `clientes` where cliActivo=1;";
$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ ?>
	<option value="<?= $row['idCliente'];?>" data-direccion="<?= $row['cliDomicilio'];?>" data-ruc="<?= $row['cliRuc'];?>" data-razon="<?= $row['cliRazonSocial'];?>"><?= $row['cliRuc']." - ". $row['cliRazonSocial']; ?></option>
<?php }
?>