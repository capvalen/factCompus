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
	<title>Proveedores - Facturador electrónico</title>
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
				<h3 class="display-4">Gestión de proveedores</h3>
				<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
			</div></div>
			<div class="card mt-3">
				<div class="card-body row">
					<div class="col-12 col-md-6 form-inline my-2">
						<label class="my-1 mr-2" for="">Buscar Producto: </label>
						<input type="search" class="form-control" id="txtProductoBuscar" placeholder='Buscar Producto' autocomplete="nope">
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2">
						<button class="btn btn-outline-primary " data-toggle="modal" data-target="#modalCrear"><i class="icofont-ui-rate-add"></i> Agregar nuevo proveedor</button>
					</div>
					
				</div>
			</div>
	
			<div class="table-responsive">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>RUC</th>
							<th>Nombre de proveedor</th>
							<th>Dirección</th>
							<th>Celular</th>
							<th>Contacto</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(provider, index) in proveedores">
							<td>{{ index+1 }}</td>
							<td>{{ provider.ruc}}</td>
							<td>{{ provider.razon}}</td>
							<td>{{ provider.direccion}}</td>
							<td>{{ provider.celular}}</td>
							<td>{{ provider.contacto}}</td>
							<td><button class="btn btn-outline-danger btn-sm" @click="borrar(index, provider.id)"><i class="icofont-ui-delete"></i></button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	<section>
	<!-- Modal para: -->
		<div class='modal fade' id='modalCrear' tabindex='-1'>
			<div class='modal-dialog modal-dialog-centered'>
				<div class='modal-content'>
					<div class='modal-body'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
						<h5 class='modal-title'>Nuevo proveedor</h5>
						<label for="">Razón social</label>
						<input type="text" class="form-control" v-model="proveedor.razon">
						<label for="">RUC</label>
						<input type="text" class="form-control" v-model="proveedor.ruc">
						<label for="">Dirección</label>
						<input type="text" class="form-control" v-model="proveedor.direccion">
						<label for="">Celular</label>
						<input type="text" class="form-control" v-model="proveedor.celular">
						<label for="">Contacto</label>
						<input type="text" class="form-control" v-model="proveedor.contacto">
					</div>
					<div class="modal-footer border-0">
						<button type="button" class="btn btn-outline-success" @click="crear()"><i class="icofont-save"></i> Crear proveedor</button>
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
				proveedor: {razon: '',ruc: '',direccion: '',celular: '',contacto: '', id:-1 }, proveedores:[]
			}
    },
		mounted(){
			this.cargar();
		},
		methods:{
			async cargar(){
				await fetch('php/listarTodosProveedores.php',{ method:'POST'})
				.then(res=> res.json())
				.then(dato=> this.proveedores = dato)
			},
			async crear(){
				if(this.proveedor.ruc=='') alertify.error('<i class="icofont-warning-alt"></i> El RUC debe estar rellenado.').delay(15);
				else if(this.proveedor.razon=='') alertify.error('<i class="icofont-warning-alt"></i> La razón social debe estar rellenado.').delay(15);
				else{
					let datos = new FormData();
					datos.append('proveedor', JSON.stringify(this.proveedor));
					await fetch('php/crearProveedor.php', {
						method: 'POST', body:datos
					}).then(res => res.json())
					.then(texto => { console.log(texto);
						if(texto.msg>0){
							this.proveedor.id= texto.msg
							this.proveedores.push(this.proveedor)
							alertify.message('<i class="icofont-warning-alt"></i> Guardado con éxito.');
						}else{
							alertify.error('<i class="icofont-warning-alt"></i> Hubo un error inesperado.').delay(15);
						}
					})
				}
			},
			async borrar(index, id){
				let datos = new FormData();
				datos.append('id', id);
				await fetch('php/borrarProveedor.php', {
					method: 'POST', body:datos
				}).then(res => res.text())
				.then(dato =>{
					if(dato == 'ok'){
						this.proveedores.splice(index, 1)
						alertify.message('<i class="icofont-warning-alt"></i> Borrado con éxito.');	
					}else{
						alertify.error('<i class="icofont-warning-alt"></i> Hubo un error inesperado.').delay(15);
					}
				})
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