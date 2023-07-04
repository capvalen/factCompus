<?php
include 'php/conexion.php';
include "generales.php";


if( !isset($_COOKIE['ckidUsuario']) ){ header("Location: index.html");
	die(); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Inventario de series - Facturador electrónico</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" integrity="" crossorigin="anonymous">
	<link rel="stylesheet" href="icofont.min.css">
	<link rel="stylesheet" href="css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/anksunamun.css">
	<link rel="shortcut icon" href="images/VirtualCorto.png" type="image/png">
	<link rel="stylesheet" href="css/colorsmaterial.css">
	<link rel="stylesheet" href="css/alertify.min.css">


</head>
<body>
<?php include 'menu-wrapper.php'; ?>

<div class="div" id="app">
	<section>
		<div class="container-fluid mt-5 px-5">
			<div class="row">
				<div class="col-md-3 text-center">
					<img src="<?= $_COOKIE['logo']?>" style='max-width: 30%'>
				</div>
				<div class="col ml-4">
					<h3 class="display-4">Inventario de productos y Series activas</h3>
					<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
				</div>
			</div>

			<div class="table-responsive">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>Producto</th>
							<th>Pre. Público</th>
							<th>Pre. Mayor</th>
							<th>Pre. Dscto.</th>
							<th>Pre. Compra</th>
							<th>Cod. barras.</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(inventario, index) in inventarios">
							<td>{{index+1}}</td>
							<td>{{inventario.prodDescripcion}}</td>
							<td>{{parseFloat(inventario.prodPrecio).toFixed(2)}}</td>
							<td>{{parseFloat(inventario.prodPrecioMayor).toFixed(2)}}</td>
							<td>{{parseFloat(inventario.prodPrecioDescto).toFixed(2)}}</td>
							<td>{{parseFloat(inventario.precioCompra).toFixed(2)}}</td>
							<td>{{inventario.barra}}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>



<?php include "php/modal.php"; ?>

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js" integrity="" crossorigin="anonymous"></script>
<script src="js/moment.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="js/alertify.min.js"></script>

<script>
	const { createApp } = Vue

	createApp({
		data() {
			return {
				inventarios:[]
			}
		},
		mounted(){
			this.cargarDatos();
		},
		methods:{
			async cargarDatos(){
				let datos = new FormData();
				datos.append('accion', 'inventarioSeries')
				await fetch('php/familias.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					 this.inventarios = resp;
				})
			},
		}
	}).mount('#app')
</script>
<style>
	.bg-dark {
		background-color: #7030a0!important;
	}
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
			/* display: none; <- Crashes Chrome on hover */
			-webkit-appearance: none;
			margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
	}
	input[type=number] {
			-moz-appearance:textfield; /* Firefox */
	}
	.bootstrap-select .dropdown-toggle .filter-option{font-family:'Icofont', 'Segoe UI';}
	.close{color: #ff0202}
	.close:hover, .close:not(:disabled):not(.disabled):hover{color: #fd0000;opacity:1;}
	#imgLogo{max-width:250px;}
	.bootstrap-select .btn-light{background-color: #ffffff;}
	.bootstrap-select .dropdown-toggle .filter-option{    border: 1px solid #ced4da;
			border-radius: .25rem;}
	thead tr th{cursor: pointer;}
	.dropdown-item .text, .bootstrap-select button{text-transform: capitalize;}
	.alertify-notifier .ajs-message.ajs-error {
		background: rgb(239 4 4 / 95%);
		color: white;
		border-radius: 2rem;
	}
	.alertify-notifier .ajs-message.ajs-warning {
		background: rgb(255 143 29 / 95%);
		color: white;
		border-radius: 2rem;
	}
	.alertify-notifier .ajs-message {
			background: rgb(29 57 255 / 95%);
			color: white;
			border-radius: 2rem;
	}
	.alertify-notifier.ajs-right{
	}
	.alertify-notifier .ajs-message{
		width: 360px!important;
		right: 390px!important;
	}
</style>
</body>
</html>