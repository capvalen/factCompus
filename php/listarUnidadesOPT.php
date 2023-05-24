<?php 

include "conexion.php";

$sqlUnd="SELECT `idUnidad`, `undDescipcion`, `undSunat`, `undCorto` FROM `unidades` WHERE undActivo =1;";
$resultadoUnd=$esclavo->query($sqlUnd);

while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
	<option value="<?= $rowUnd['undSunat'];?>" data-unidad="<?= $rowUnd['undCorto']?>"><?= $rowUnd['undDescipcion'];?></option>
<?php 
}		


?>