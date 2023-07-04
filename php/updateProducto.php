<?php 
date_default_timezone_set('America/Lima');
include "conexion.php";


$sql="UPDATE `productos` p
inner join unidades u
SET 
`prodDescripcion`='{$_POST['pNombre']}',
p.`idUnidad`= u.idUnidad ,
`prodPrecio`= {$_POST['pPublico']},
`prodPrecioMayor`= {$_POST['pMayor']},
`prodPrecioDescto`= {$_POST['pDescuento']},
`idGravado`= {$_POST['pImpuesto']},
`series`= {$_POST['pSeries']},
similares = '{$_POST['similares']}'
WHERE `idProductos`= {$_POST['idProd']} and u.undSunat = '{$_POST['pUnidad']}'";
//echo $sql;
$resultado=$cadena->query($sql);
echo "ok";
?>