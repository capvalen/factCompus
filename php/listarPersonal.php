<?php 
include "conexion.php";
$sql="SELECT `idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPoder`, `usuActivo`, p.podDescripcion FROM `usuario` u
inner join poder p on p.idPoder = u.`usuPoder`
WHERE usuActivo = 1";
$resultado=$cadena->query($sql);
$i=1;
while($row=$resultado->fetch_assoc()){ ?>
<tr>
   <td><?= $i; ?></td>
   <td class="text-capitalize" data-id="<?= $row['idUsuario'];?>"><?= $row['usuApellido'] ." ".$row['usuNombres']; ?></td>
   <td><?= $row['usuNick']; ?></td>
   <td><?= $row['podDescripcion']; ?></td>
		<?php if($_COOKIE['ckPower']==1){ ?>
			<td><button class="btn btn-outline-success btn-sm" onclick="clavePersonal(<?= $row['idUsuario'];?>)" title="Cambiar contraseña"><i class="icofont-key-hole"></i></button></td>
		<?php } //Fin de CkPower ?>
   <td><button class="btn btn-outline-danger btn-sm" onclick="removerPersonal(<?= $row['idUsuario'];?>)"><i class="icofont-trash"></i></button></td>
</tr>
   <?php $i++;
}
 ?>