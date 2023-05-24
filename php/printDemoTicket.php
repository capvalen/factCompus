<?php 
include "../generales.php";

$productos=$_POST['productos'];
$todoProd= '';
foreach ($productos as $variable) {
	$todoProd = $todoProd .  ucwords($variable['descripcion'])."\n              ".$variable['cantidad']." ".$variable['undCorto']."   ". "S/ ". number_format($variable['preProducto'],2).'   S/ '. number_format($variable['precio'],2)."\n";
}

if($_POST['queEs']!='PROFORMA' && $_POST['queEs']!="NOTA DE PEDIDO"){ $queEs = $_POST['queEs'] . " ELECTRÓNICA"; }else{ $queEs = $_POST['queEs']; }

echo "\n".$nombreEmisor."\n";
echo "RUC: ".$rucEmisor."\n";

echo "".$direccionEmisor."\n";

echo "{$queEs}\n";
echo "{$_POST['serie']}-{$_POST['correlativo']}\n"; //Cambniar Codigooooooooo

echo "--------------------------------\n";

echo "Fecha de emisión: ". $_POST['fechaLat'] ."\n";
echo "Doc. Identidad: {$_POST['docClient']}\n";
echo "Señor(es): ".strtoupper($_POST['cliente'])."\n";
if($_POST['direccion']==''){
		echo "Dirección: ---\n";
}else{
	echo "Dirección: ".strtoupper($_POST['direccion'])."\n";
}
echo "--------------------------------\n";
echo "DESCRIPCION  | CANT |  P.UNIT.  |  SUBTOTAL  |\n";
echo "{$todoProd}\n";
echo "--------------------------------\n";
echo "Exonerado: S/ {$_POST['exonerado']} \n";
echo "Sub Total: S/ {$_POST['costoFinal']} \n";
echo "IGV (18%): S/ {$_POST['igvFinal']} \n";
echo "Total: S/ {$_POST['totalFinal']} \n";
echo "SON: {$_POST['monedas']} \n";

echo "--------------------------------\n";
if($_POST['serie']==''){
	/*echo "Contactos: ".$celularEmisor."\n");*/

	echo "No olvide reclamar su comprobante\n";
}else{
	$printer -> bitImage($tuxQR);
	echo "--------------------------------\n";
	echo "Esta es una representación impresa de la factura electrónica, generada en el Sistema de SUNAT. Puede verificarla utilizando su Clave SOL.\n";
}	
echo "Gracias por tu preferencia\n\n";
?>