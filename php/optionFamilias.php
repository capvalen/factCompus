<?php 

include "conexion.php";

$sqlUnd="SELECT * FROM `familias` where activo=1 order by familia ;";
$resultadoUnd=$esclavo->query($sqlUnd);

while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
	<option value="<?= $rowUnd['id'];?>" ><?= $rowUnd['familia'];?></option>
<?php 
}		


?>

