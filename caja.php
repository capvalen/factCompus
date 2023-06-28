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
	<title>Caja - Facturador electrónico</title>
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
				<h3 class="display-4">Control de caja</h3>
				<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
			</div></div>
			<div class="card my-3">
				<div class="card-body row">
					<div class="col-12 col-md-6 form-inline my-2">
						<label class="my-1 mr-2" for="">Fecha: </label>
						<input type="date" class="form-control" id="txtFecha" v-model="fecha">
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2" v-if="caja.abierto == 0">
						<button class="btn btn-outline-primary " data-toggle="modal" data-target="#modalAbrirCaja"><i class="icofont-box"></i> Abrir Caja</button>
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2" v-else>
						<button class="btn btn-outline-warning " data-toggle="modal" data-target="#modalAbrirCaja"><i class="icofont-box"></i> Cerrar Caja</button>
					</div>
					
				</div>
			</div>
	
			<span class="badge badge-pill badge-primary px-3 py-2">Entradas de dinero</span>
			<div class="table-responsive">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>Cliente</th>
							<th>Comprobante</th>
							<th>Monto</th>
							<th>Obs.</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(venta, index) in registros.ventas">
							<td>{{ index+1 }}</td>
							<td>{{ venta.razonSocial }}</td>
							<td>{{ venta.factSerie }}-{{ venta.factCorrelativo }}</td>
							<td>+ S/ {{ parseFloat(venta.totalFinal).toFixed(2) }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section>
		<!-- Modal -->
		<div class="modal fade" id="modalAbrirCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">
							<span v-if="caja.abierto == 0 ">Aperturar Caja</span>
							<span v-else>Cerrar Caja</span>
						</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="" v-if="caja.abierto == 0 ">Monto de apertura: (S/)</label>
						<label for="" v-else>Monto de cierre: (S/)</label>
						<input type="number" class="form-control" v-model="apertura" min=0 step=1>
						<label for="">Observaciones:</label>
						<input type="text" class="form-control" v-model="observacion">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="abrirCaja()" v-if="caja.abierto == 0 "><i class="icofont-save"></i> Aperturar Caja</button>
						<button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="cerrarCaja()" v-else><i class="icofont-save"></i> Cerrar Caja</button>
					</div>
				</div>
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
				fecha: moment().format('YYYY-MM-DD'), caja:{abierto:0}, apertura:0, observacion:'', registros:[]
			}
		},
		mounted(){
			this.verificarCaja()
		},
		methods:{
			async verificarCaja(){
				let datos = new FormData();
				datos.append('accion', 'verificarCaja')
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					if(resp.length ==1 ){
						this.caja = resp[0]
						this.fecha = moment(this.caja.fechaApertura).format('YYYY-MM-DD')
						this.datosDeCaja();
					}
				})
			},
			async abrirCaja(){
				let datos = new FormData();
				datos.append('accion', 'abrirCaja')
				datos.append('apertura', this.apertura)
				datos.append('observacion', this.observacion)
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					this.caja = resp;
					this.apertura = 0; this.observacion=''
				} )
			},
			async datosDeCaja(){
				let datos = new FormData();
				datos.append('accion', 'datosDeCaja')
				datos.append('idCaja', this.caja.id)
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {this.registros = resp; console.log(resp)} )
			},
			async cerrarCaja(){
				let datos = new FormData();
				datos.append('accion', 'cerrarCaja')
				datos.append('id', this.caja.id)
				datos.append('cierre', this.apertura)
				datos.append('observacion', this.observacion)
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => this.caja = resp )
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