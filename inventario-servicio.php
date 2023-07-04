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
	<title>Reporte de servicio técnico - Facturador electrónico</title>
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
					<h3 class="display-4">Reporte de servicio técnico</h3>
					<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
				</div>
			</div>
			<div class="card mt-2">
			<div class="card-body">
				<div class="row row-cols-12 row-cols-md-4">
					<div class="col">
						<label for="">Fecha de inicio</label>
						<input type="date" class="form-control" v-model="fechas.inicio">
					</div>
					<div class="col">
						<label for="">Fecha de Fin</label>
						<input type="date" class="form-control" v-model="fechas.fin">
					</div>
					<div class="col d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="cargarDatos()"><i class="icofont-search-1"></i> Filtrar</button>
					</div>
				</div>
			</div>
		</div>

			<div class="table-responsive">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>Producto</th>
							<th>Estado inicial</th>
							<th>Diagnostico</th>
							<th>Técnico</th>
							<th>Monto</th>
							<th>Fecha</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(inventario, index) in inventarios">
							<td>{{index+1}}</td>
							<td>{{inventario.marca}} {{inventario.modelo}} {{inventario.serie}}</td>
							<td>{{inventario.estado}}</td>
							<td>{{inventario.diagnostico}}</td>
							<td>{{inventario.usuNombres}}</td>
							<td>{{parseFloat(inventario.monto).toFixed(2)}}</td>
							<td>{{fechaLatam(inventario.fechaMonto)}}</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan=5 class="text-right">Total</th>
							<th >{{sumaMontos}}</th>
						</tr>
					</tfoot>
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
				inventarios:[],
				fechas:{inicio: moment().format('YYYY-MM-DD'), fin: moment().format('YYYY-MM-DD')}
			}
		},
		mounted(){
		},
		methods:{
			async cargarDatos(){
				let datos = new FormData();
				datos.append('accion', 'inventario')
				datos.append('inicio', this.fechas.inicio)
				datos.append('fin', this.fechas.fin)
				await fetch('php/servicio_tecnico.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => { console.log(resp);
					 this.inventarios = resp;
				})
			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
			}
		},
		computed:{
			sumaMontos(){
				let suma = 0
				this.inventarios.forEach(elemento => {
					suma += parseFloat(elemento.monto)
				});
				return suma.toFixed(2);
			}
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