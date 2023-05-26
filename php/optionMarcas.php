<?php 

include "conexion.php";

$sqlUnd="SELECT * FROM `marcas` where activo=1 order by marca ;";
$resultadoUnd=$esclavo->query($sqlUnd);

while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
	<option value="<?= $rowUnd['id'];?>" ><?= $rowUnd['marca'];?></option>
<?php 
}		


?>