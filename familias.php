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
	<title>Familias - Facturador electrónico</title>
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
				<h3 class="display-4">Gestión de Familias</h3>
				<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
			</div></div>
			
			<div class="row row-cols-2">
				<div class="col">
					<div class="table-responsive">
						<p><strong>Líneas</strong></p>
						<button class="btn btn-outline-primary " data-toggle="modal" data-target="#addLinea"><i class="icofont-ui-rate-add"></i> Agregar nueva categoría</button>

						<table class="table table-hover mt-3">
							<thead>
								<tr>
									<th>N°</th>
									<th>Descripción</th>
									<th>@</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(linea, index) in familias.lineas">
									<td>{{index+1}}</td>
									<td>{{linea.familia}}</td>
									<td> <button class="btn btn-outline-danger" @click="eliminarLineas(index)"><i class="icofont-ui-delete"></i></button> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col">
					<div class="table-responsive">
						<p><strong>Familias</strong></p>
						<button class="btn btn-outline-primary "data-toggle="modal" data-target="#addFamilia"><i class="icofont-ui-rate-add"></i> Agregar nueva familia</button>

						<table class="table table-hover mt-3">
							<thead>
								<tr>
									<th>N°</th>
									<th>Descripción</th>
									<th>Pertenece a</th>
									<th>@</th>
								</tr>
							</thead>
							<tbody >
								<tr v-for="(linea, index) in familias.familias">
									<td>{{index+1}}</td>
									<td>{{linea.subfamilia}}</td>
									<td>{{linea.familia}}</td>
									<td> <button class="btn btn-outline-danger" @click="eliminarFamilias(index)"><i class="icofont-ui-delete"></i></button> </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
	
		
		</div>
	</section>
	<section>
		<!-- Modal -->
		<div class="modal fade" id="addLinea" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Agregar Familia</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Nombre de la línea nueva</label>
						<input type="text" class="form-control" v-model="linea">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" @click="guardarLinea();" data-dismiss="modal">Registrar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="addFamilia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Agregar Familia</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Nombre de la familia nueva</label>
						<input type="text" class="form-control" v-model="linea">
						<label for="">Línea a la que pertenece</label>
						<select class="form-control" id="" v-model="idFamilia">
							<option v-for="(linea, index) in familias.lineas" :value="index">{{linea.familia}}</option>
						</select>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" @click="guardarFamilia();" data-dismiss="modal">Registrar</button>
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
				familias: [], linea:'', idFamilia:1, indexGlobal:-1
			}
		},
		mounted(){
			this.cargarDatos();
		},
		methods:{
			async cargarDatos(){
				let datos = new FormData();
				datos.append('accion', 'cargarFamilias')
				await fetch('php/familias.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					 this.familias = resp;
				})
			},
			async guardarLinea(){
				let datos = new FormData();
				datos.append('accion', 'addLinea')
				datos.append('linea', this.linea);
				await fetch('php/familias.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					console.log(resp)
					if(resp.id) this.familias.lineas.push({id: resp.id,
						familia: this.linea,
					})
				})
			},
			async guardarFamilia(){
				let datos = new FormData();
				datos.append('accion', 'addFamilia')
				datos.append('linea', this.linea);
				datos.append('idFamilia', this.familias.lineas[this.idFamilia].id);
				await fetch('php/familias.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					console.log(resp)
					if(resp.id) this.familias.familias.push({id: resp.id,
						familia: this.familias.lineas[this.idFamilia].familia,
						subfamilia: this.linea
					})
				})
			},
			async eliminarLineas(index){
				if( confirm(`¿Deseas eliminar el dato: ${this.familias.lineas[index].familia}?`)){
					let datos = new FormData();
					datos.append('accion', 'eliminarLinea')
					datos.append('id', this.familias.lineas[index].id )
					await fetch('php/familias.php',{
						method:'POST', body:datos
					})
					.then(serv => serv.text())
					.then(resp => resp=='ok' ? this.familias.lineas.splice(index,1) : console.log(resp) )
				}
			},
			async eliminarFamilias(index){
				if( confirm(`¿Deseas eliminar el dato: ${this.familias.familias[index].subfamilia}?`)){
					let datos = new FormData();
					datos.append('accion', 'eliminarFamilia')
					datos.append('id', this.familias.familias[index].id )
					await fetch('php/familias.php',{
						method:'POST', body:datos
					})
					.then(serv => serv.text())
					.then(resp => resp=='ok' ? this.familias.familias.splice(index,1) : console.log(resp) )
				}
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