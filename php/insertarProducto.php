<?php 

date_default_timezone_set('America/Lima');
include "conexion.php";

$sql="INSERT INTO `productos`(`idProductos`, `prodDescripcion`, `idUnidad`, `prodPrecio`, `prodPrecioMayor`, `prodPrecioDescto`, `prodStock`, `idGravado`, `prodActivo`, `codeSunat`,
`series`, `idMarca`, `idLinea`, `idFamilia`, `idSubFamilia`, `similares`) 
select null, '{$_POST['nombre']}', u.idUnidad, {$_POST['precio']}, {$_POST['mayor']}, {$_POST['descuento']}, 0, {$_POST['gravado']}, 1, '{$_POST['codeSunat']}',
{$_POST['series']}, {$_POST['marca']}, {$_POST['linea']}, {$_POST['familia']}, {$_POST['subfamilia']}, '{$_POST['similares']}'
from unidades u where u.undSunat = '{$_POST['unidad']}'
";
//echo $sql;
$resultado=$cadena->query($sql);
echo "ok";
?>