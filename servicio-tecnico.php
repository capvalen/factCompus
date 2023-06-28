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
				<h3 class="display-4">Servicio técnico</h3>
				<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
			</div></div>
			<div class="card mt-3">
				<div class="card-body row">
					<div class="col-12 col-md-6 form-inline my-2">
						<label class="my-1 mr-2" for="">Buscar por fecha: </label>
						<input type="date" class="form-control" id="txtFecha" v-model="fecha" @change="cargarDatos(false)">
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2">
						<button class="btn btn-outline-primary " data-toggle="modal" data-target="#modalNuevaRecepcion"><i class="icofont-ui-rate-add"></i> Agregar nueva recepción</button>
					</div>
				</div>
			</div>
	
			<div class="table-responsive">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
						<th>N°</th>
							<th>RUC</th>
							<th>Cliente</th>
							<th>Celular</th>
							<th>Modelo</th>
							<th>Estado Inicial</th>
							<th>Diagnóstico</th>
							<th>Etapa</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody id="tbodyRespuesta">
						<tr v-for="(recepcionado, index ) in recepciones">
							<td> {{index+1}} </td>
							<td> {{recepcionado.dni}} </td>
							<td> {{recepcionado.razon_social}} </td>
							<td> {{recepcionado.celular}} </td>
							<td> {{recepcionado.modelo}} </td>
							<td> {{recepcionado.estado}} </td>
							<td> {{recepcionado.diagnostico}} </td>
							<td>
								<span v-if="recepcionado.etapa == '1'">Recepcionado</span>
								<span v-if="recepcionado.etapa == '2'">Diagnosticado</span>
								<span v-if="recepcionado.etapa == '3'">Pagado</span>
							</td>
							<td> 
								<a class="btn btn-sm btn-outline-primary mx-1" title="Ver Recepción" :href="'./php/ticket-servicio-tecnico.php?id='+ recepcionado.id" target="_blank"><i class="icofont-eye-alt"></i></a>
								<button v-if="recepcionado.etapa=='1'" class="btn btn-sm btn-outline-warning mx-1" title="Registrar Diagnóstico" data-toggle="modal" data-target="#modalNuevoDiagnostico" @click="llenarDiagnostico(index)"><i class="icofont-medical-sign"></i></button>
								<button v-if="recepcionado.etapa=='2'" class="btn btn-sm btn-outline-success mx-1" title="Registrar pago" data-toggle="modal" data-target="#modalNuevoPago" @click="llenarDiagnostico(index)"><i class="icofont-money"></i></button>
							</td>
						
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
	<section>
		<!-- Modal -->
		<div class="modal fade" id="modalNuevaRecepcion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Nueva recepción</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p><strong>Datos del cliente</strong></p>
						<label for="">DNI o RUC</label>
						<input type="text" class="form-control" v-model="cliente.dni">
						<label for="">Nombre del cliente / Razón social</label>
						<input type="text" class="form-control" v-model="cliente.razon_social">
						<label for="">Celular</label>
						<input type="text" class="form-control" v-model="cliente.celular">
						<hr>
						<p><strong>Datos del artículo</strong></p>
						<label for="">Clasificación</label>
						<select class="form-control" id="sltClasificacion" v-model="recepcion.idSubFamilia">
							<option v-for="familia in familias" :value="familia.id">{{familia.subfamilia}}</option>
						</select>
						<label for="">Marca <span class="text-danger">*</span></label>
						<input type="text" class="form-control" v-model="recepcion.marca" autocomplete="off">
						<label for="">Modelo <span class="text-danger">*</span></label>
						<input type="text" class="form-control" v-model="recepcion.modelo" autocomplete="off">
						<label for="">Serie</label>
						<input type="text" class="form-control" v-model="recepcion.serie" autocomplete="off">
						<label for="">Color</label>
						<input type="text" class="form-control" v-model="recepcion.color" autocomplete="off">
						<label for="">Estado <span class="text-danger">*</span></label>
						<input type="text" class="form-control" v-model="recepcion.estado" autocomplete="off">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="registrarRecepcion()"><i class="icofont-save"></i> Registrar recepción</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="modalNuevoDiagnostico" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Nuevo diagnóstico</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p><strong>Datos del cliente</strong></p>
						<p class="mb-1"><strong>DNI / RUC:</strong> <span>{{diagnostico.dni}}</span></p>
						<p class="mb-1"><strong>Cliente:</strong> <span>{{diagnostico.razon_social}}</span></p>
						<p class="mb-1"><strong>Celular:</strong> <span>{{diagnostico.celular}}</span></p>
						<hr>
						<p><strong>Datos del artículo</strong></p>
						<p class="mb-1"><strong>Categoría:</strong> <span>{{diagnostico.subFamilia}}</span></p>
						<p class="mb-1"><strong>Marca:</strong> <span>{{diagnostico.marca}}</span></p>
						<p class="mb-1"><strong>Modelo:</strong> <span>{{diagnostico.modelo}}</span></p>
						<p class="mb-1"><strong>Serie:</strong> <span>{{diagnostico.serie}}</span></p>
						<p class="mb-1"><strong>Color:</strong> <span>{{diagnostico.color}}</span></p>
						<p class="mb-1"><strong>Estado inicial:</strong> <span>{{diagnostico.estado}}</span></p>
						<hr>
						<p><strong>Datos del diagnóstico</strong></p>
						<label for="">Técnico</label>
						<select class="form-control text-capitalize" id="sltClasificacion" v-model="diagnostico.idUsuario">
							<option class="text-capitalize" v-for="usuario in usuarios" :value="usuario.idUsuario">{{usuario.usuNombres}}</option>
						</select>
						<label for="">Descripción de diagnóstico<span class="text-danger">*</span></label>
						<input type="text" class="form-control" v-model="diagnostico.diagnostico" autocomplete="off">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="registrarDiagnostico()"><i class="icofont-save"></i> Registrar diagnóstico</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div class="modal fade" id="modalNuevoPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Nuevo diagnóstico</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p><strong>Datos del cliente</strong></p>
						<p class="mb-1"><strong>DNI / RUC:</strong> <span>{{diagnostico.dni}}</span></p>
						<p class="mb-1"><strong>Cliente:</strong> <span>{{diagnostico.razon_social}}</span></p>
						<p class="mb-1"><strong>Celular:</strong> <span>{{diagnostico.celular}}</span></p>
						<hr>
						<p><strong>Datos del artículo</strong></p>
						<p class="mb-1"><strong>Categoría:</strong> <span>{{diagnostico.subFamilia}}</span></p>
						<p class="mb-1"><strong>Marca:</strong> <span>{{diagnostico.marca}}</span></p>
						<p class="mb-1"><strong>Modelo:</strong> <span>{{diagnostico.modelo}}</span></p>
						<p class="mb-1"><strong>Serie:</strong> <span>{{diagnostico.serie}}</span></p>
						<p class="mb-1"><strong>Color:</strong> <span>{{diagnostico.color}}</span></p>
						<p class="mb-1"><strong>Estado inicial:</strong> <span>{{diagnostico.estado}}</span></p>
						<hr>
						<p><strong>Datos del diagnóstico</strong></p>
						<p class="mb-1"><strong>Técnico:</strong> <span>{{diagnostico.nomTecnico}}</span></p>
						<p class="mb-1"><strong>Diagnóstico:</strong> <span>{{diagnostico.diagnostico}}</span></p>
						<hr>
						<label for="">Monto: (S/)</label>
						<input type="number" class="form-control" v-model="monto" min=0 step=1>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" data-dismiss="modal" @click="registrarPago()"><i class="icofont-save"></i> Registrar pago</button>
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
				familias: [], fecha: moment().format('YYYY-MM-DD'), recepciones:[], usuarios:[], queIndex:null, monto:0,
				cliente: {dni: '', razon_social:'', celular:''}, recepcion:{marca:'', modelo:'', serie:'', color:'', estado:'', idSubFamilia:14},
				diagnostico:{idUsuario:1, descripcion: '', idRecepcion:'', dni:'', razon_social:'', celular:'',}
			}
		},
		methods:{
			async cargarDatos(pendientes){
				let datos = new FormData();
				datos.append('fecha', this.fecha)
				datos.append('pendientes', pendientes)

				await fetch('php/listarRecepciones.php',{
					method:'POST', body: datos
				})
				.then(serv => serv.json())
				.then(resp => this.recepciones = resp )

				await fetch('php/listarSubFamilias.php')
				.then(serv=> serv.json())
				.then(resp=> this.familias = resp )
				await fetch('php/listarUsuarios.php')
				.then(serv=> serv.json())
				.then(resp=> this.usuarios = resp )
			},
			async registrarRecepcion(){
				if(this.recepcion.marca =='') alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> debe rellenarse la marca').delay(15);
				else if(this.recepcion.modelo =='') alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> debe rellenarse el modelo').delay(15);
				else if(this.recepcion.estado =='') alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> debe rellenarse el estado').delay(15);
				else{
					//guardar
					let datos = new FormData();
					datos.append('accion', 'registrar')
					datos.append('cliente', JSON.stringify(this.cliente))
					datos.append('recepcion', JSON.stringify(this.recepcion))
					await fetch('php/servicio_tecnico.php',{
						method:'POST', body: datos
					})
					.then(serv => serv.text() )
					.then(resp => {
						if( parseInt(resp) >0 ) { window.open('./php/ticket-servicio-tecnico.php?id='+resp, '_blank'); window.location.reload() }
						else console.log(resp)
					})
				}
			},
			llenarDiagnostico(index){
				this.queIndex = index;
				this.diagnostico.idRecepcion = this.recepciones[index].id;
				this.diagnostico.dni = this.recepciones[index].dni;
				this.diagnostico.razon_social = this.recepciones[index].razon_social;
				this.diagnostico.celular = this.recepciones[index].celular;
				this.diagnostico.subFamilia = this.recepciones[index].subfamilia;
				this.diagnostico.marca = this.recepciones[index].marca;
				this.diagnostico.modelo = this.recepciones[index].modelo;
				this.diagnostico.serie = this.recepciones[index].serie;
				this.diagnostico.color = this.recepciones[index].color;
				this.diagnostico.estado = this.recepciones[index].estado;
				this.diagnostico.nomTecnico = this.recepciones[index].nomTecnico;
				this.diagnostico.diagnostico = this.recepciones[index].diagnostico;
			},
			async registrarDiagnostico(){
				if(this.diagnostico.diagnostico =='') alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> debe rellenarse el diagnóstico').delay(15);
				else{
					//guardar
					let datos = new FormData();
					datos.append('accion', 'registrar-diagnostico')
					datos.append('diagnostico', JSON.stringify(this.diagnostico))
					await fetch('php/servicio_tecnico.php',{
						method:'POST', body: datos
					})
					.then(serv => serv.text() )
					.then(resp => {
						if(resp=='ok'){
							window.open('./php/ticket-servicio-tecnico.php?id='+ this.recepciones[this.queIndex].id, '_blank');
							this.recepciones[this.queIndex].etapa = 2;
						}
					})
				}
			},
			async registrarPago(){
				if(this.monto <= 0 ) alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> El pago no puede ser menor a 0').delay(15);
				else{
					//guardar
					let datos = new FormData();
					datos.append('accion', 'registrar-pago')
					datos.append('monto', this.monto)
					datos.append('idRecepcion', this.recepciones[this.queIndex].id )
					await fetch('php/servicio_tecnico.php',{
						method:'POST', body: datos
					})
					.then(serv => serv.text() )
					.then(resp => {
						if(resp=='ok'){
							window.open('./php/ticket-servicio-tecnico.php?id='+ this.recepciones[this.queIndex].id, '_blank');
							this.recepciones[this.queIndex].etapa = 3;
						}
					})
				}
			}
		},
		mounted(){
			this.cargarDatos(true);
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