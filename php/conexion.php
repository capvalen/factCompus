<?php
$server="localhost";

/* Net	*/
$username="root";
$password="*123456*";
$db= "pluginsunat";

$cadena= mysqli_connect($server,$username,$password)or die("No se ha podido establecer la conexion");
$sdb= mysqli_select_db($cadena,$db)or die("La base de datos no existe");
$cadena->set_charset("utf8");
mysqli_set_charset($cadena,"utf8");

$esclavo= new mysqli($server, $username, $password, $db);
$esclavo->set_charset("utf8");


$conf= new mysqli($server, $username, $password, $db);
$conf->set_charset("utf8");

//Con Objetos:
try {
	$datab = new PDO (
		'mysql:host=localhost;
		dbname='.$db,
		$username,
		$password,
		array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
	);
} catch (Exception $e) {
	echo "Problema con la conexion: ".$e->getMessage();
}

?>