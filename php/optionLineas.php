<?php 

include "conexion.php";

$sqlUnd="SELECT * FROM `lineas` where activo=1 order by linea ;";
$resultadoUnd=$esclavo->query($sqlUnd);

while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
	<option value="<?= $rowUnd['id'];?>" ><?= $rowUnd['linea'];?></option>
<?php 
}		


?>