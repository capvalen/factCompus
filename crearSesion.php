<?php

header('Content-Type: text/html; charset=utf8');

if( $_POST['negocio']=='admin' && $_POST['local']=='nus'){
	$expira=time()+60*60*2; //sesion de 2 horas
	setcookie('ckNegocio', $_POST['negocio'], $expira, '/');
	setcookie('ckLocal', $_POST['local'], $expira, '/');

	echo 1;}
?>