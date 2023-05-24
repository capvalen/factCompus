<?php 

include "conexion.php";

$sqlUnd="SELECT * FROM `gravados` order by gravDescripcion;";
$resultadoUnd=$esclavo->query($sqlUnd);

while($rowUnd=$resultadoUnd->fetch_assoc()){  ?>
	<option value="<?= $rowUnd['idGravado'];?>" ><?= $rowUnd['gravDescripcion'];?></option>
<?php 
}		


?>