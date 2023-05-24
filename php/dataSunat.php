<?php
include "conexion.php";

$fila = array();
$sql="SELECT * FROM `clientes`
where cliRuc = '{$_POST['ruc']}' and cliActivo =1";
$resultado=$cadena->query($sql);
if($resultado->num_rows>=1){

	$row=$resultado->fetch_assoc();
	$fila = array(
		"razon_social" => $row['cliRazonSocial'],
		"domicilio_fiscal" => $row['cliDomicilio']
	);

}else{
	/* $json = file_get_contents("https://infocatsoluciones.com/app/consorcioSoriano/dataSunat.php?ruc={$_POST['ruc']}");
	$obj = json_decode($json);
	//echo $obj->access_token;
	//var_dump($obj->ruc);
	echo json_encode($obj); */
	$fila = array(
		"razon_social" => '',
		"domicilio_fiscal" => ''
	);
}
echo json_encode($fila);
?>