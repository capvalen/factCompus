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
						<button class="btn btn-outline-primary ml-2" @click="buscarCajas()"><i class="icofont-search-1"></i></button>
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2" v-if="caja.abierto == 0">
						<button class="btn btn-outline-primary " data-toggle="modal" data-target="#modalAbrirCaja"><i class="icofont-box"></i> Abrir Caja</button>
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2" v-else>
						<button class="btn btn-outline-warning " data-toggle="modal" data-target="#modalAbrirCaja"><i class="icofont-box"></i> Cerrar Caja</button>
					</div>
					
				</div>
			</div>
			<div class="card my-3" v-if="caja.abierto == 1">
				<div class="card-body row">
					<div class="col-12 col-md-3 my-2" >
						<p class="mb-0 font-weight-bold">Usuario:</p>
						<p class="mb-0">{{caja.usuNombres}}</p>
					</div>
					<div class="col-12 col-md-3 my-2" >
						<p class="mb-0 font-weight-bold">Apertura:</p>
						<p class="mb-0">S/ {{parseFloat(caja.apertura).toFixed(2)}}</p>
					</div>
					<div class="col-12 col-md-3 my-2" >
						<p class="mb-0 font-weight-bold">Cierre:</p>
						<p class="mb-0">S/ {{parseFloat(caja.cierre).toFixed(2)}}</p>
					</div>
					<div class="col-12 col-md-3 my-2" >
						<p class="mb-0 font-weight-bold">Estado:</p>
						<p class="mb-0">
							<span v-if="caja.abierto=='1'">Abierto</span>
							<span v-if="caja.abierto=='0'">Cerrado</span>
						</p>
					</div>
					
					
				</div>
			</div>

	
			<span class="badge badge-pill badge-secondary px-3 py-2">Entradas de dinero</span>
			<div class="table-responsive mb-4">
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
					<tfoot>
						<tr>
							<th class="text-right" colspan=3></th>
							<th>S/ {{sumaVentas}}</th>
						</tr>
					</tfoot>
				</table>
			</div>

			
			<span v-if="caja.abierto==1" class="badge badge-pill badge-primary px-3 py-2">Entradas extras de dinero</span> <a href="#!" @click="entrada.idProceso = 9" class="badge badge-pill badge-success px-3 py-2" data-toggle="modal" data-target="#entradaCaja"><i class="icofont-ui-add"></i>  Agregar nuevo registro</a> 
			<div class="table-responsive mb-4">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>Detalle</th>
							<th>Monto</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(ingreso, index) in registros.ingresos">
							<td>{{ index+1 }}</td>
							<td class="text-capitalize">{{ ingreso.descripcion }}</td>
							<td>{{ parseFloat(ingreso.monto).toFixed(2) }}</td>
							<td><button class="btn btn-outline-danger" @click="borrarEntrada(ingreso.id, 'ingreso', index)"><i class="icofont-ui-delete"></i></button></td>
						</tr>
						<tr v-if="registros.ingresos.length == 0">
							<td colspan="4">No hay datos registrados en ingresos de dinero</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right" colspan=2></th>
							<th>S/ {{sumaIngresos}}</th>
						</tr>
					</tfoot>
				</table>
			</div>

			<span v-if="caja.abierto==1" class="badge badge-pill badge-danger px-3 py-2">Salidas extras de dinero</span> <a href="#!" @click="entrada.idProceso = 10" class="badge badge-pill badge-success px-3 py-2" data-toggle="modal" data-target="#salidaCaja"><i class="icofont-ui-add"></i>  Agregar nuevo registro</a> 
			<div class="table-responsive mb-4">
				<table class="table table-hover mt-3" id="tlbProductosTodos">
					<thead>
						<tr>
							<th>N°</th>
							<th>Detalle</th>
							<th>Monto</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(salida, index) in registros.salidas">
							<td>{{ index+1 }}</td>
							<td class="text-capitalize">{{ salida.descripcion }}</td>
							<td>{{ parseFloat(salida.monto).toFixed(2) }}</td>
							<td><button class="btn btn-outline-danger" @click="borrarEntrada(salida.id, 'salida', index)"><i class="icofont-ui-delete"></i></button></td>
						</tr>
						<tr v-if="registros.salidas.length == 0">
							<td colspan="4">No hay datos registrados en salidas de dinero</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right" colspan=2></th>
							<th>S/ {{sumaSalidas}}</th>
						</tr>
					</tfoot>
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
		<!-- Modal -->
		<div class="modal fade" id="entradaCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Ingreso de dinero</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Monto S/</label>
						<input type="number" class="form-control" v-model="entrada.monto">
						<label for="">Observaciones</label>
						<input type="text" class="form-control" v-model="entrada.descripcion">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" @click="guardarEntrada();" data-dismiss="modal">Registrar entrada</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal -->
		<div class="modal fade" id="salidaCaja" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Salida de dinero</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Monto S/</label>
						<input type="number" class="form-control" v-model="entrada.monto">
						<label for="">Observaciones</label>
						<input type="text" class="form-control" v-model="entrada.descripcion">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" @click="guardarSalida();" data-dismiss="modal">Registrar salida</button>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="verCajas" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Cajas encontradas</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Para la fecha {{fechaLatam(fecha)}} escogida, se encontró</label>
						
						<table class="table table-sm">
							<thead>
								<tr>
									<th>N°</th>
									<th>Fecha y hora</th>
									<th>Usuario</th>
									<th>Apertura</th>
									<th>Cierre</th>
									<th>Estado</th>
									<th>@</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(cuadre, index) in cuadres">
									<td>{{index+1}}</td>
									<td>{{menos1Hora(cuadre.fechaApertura)}}</td>
									<td>{{cuadre.usuNombres}}</td>

									<td>{{parseFloat(cuadre.apertura).toFixed(2)}}</td>
									<td>{{parseFloat(cuadre.cierre).toFixed(2)}}</td>
									<td>
										<span v-if="cuadre.abierto ==0">Cerrado</span>
										<span v-if="cuadre.abierto ==1">Abierto ahora</span>
									</td>
									<td><button class="btn btn-sm btn-outline-primary" @click="pedir1Caja(cuadre.id)" data-dismiss="modal">Ver</button></td>
								</tr>
							</tbody>
						</table>
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
				fecha: moment().format('YYYY-MM-DD'), caja:{abierto:0}, apertura:0, observacion:'', registros:{ingresos:{}, salidas:{}, ventas:{}}, entrada: { idProceso: -1, monto: 0, descripcion:''},
				ventas:[], cuadres:[]
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
			async pedir1Caja(id){
				let datos = new FormData();
				datos.append('accion', 'pedir1Caja')
				datos.append('id', id)
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
				.then(resp => {
					this.registros.ventas = resp.ventas;
					this.registros.ingresos = resp.ingresos;
					this.registros.salidas = resp.salidas;
					console.log(resp)} )
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
				.then(resp => {this.caja = resp; location.reload()} )
			},
			async guardarEntrada(){
				let datos = new FormData();
				datos.append('accion', 'entradaEnCaja')
				datos.append('idCaja', this.caja.id);
				datos.append('entrada', JSON.stringify(this.entrada));
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					console.log(resp)
					if(resp.idEntrada) this.registros.ingresos.push({idEntrada: resp.idProceso,
						monto: this.entrada.monto,
						descripcion: this.entrada.descripcion,
						idProceso: this.entrada.idProceso
					})
				})
			},
			async guardarSalida(){
				let datos = new FormData();
				datos.append('accion', 'salidaEnCaja')
				datos.append('idCaja', this.caja.id);
				datos.append('entrada', JSON.stringify(this.entrada));
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					console.log(resp)
					if(resp.idEntrada) this.registros.salidas.push({idEntrada: resp.idProceso,
						monto: this.entrada.monto,
						descripcion: this.entrada.descripcion,
						idProceso: this.entrada.idProceso
					})
				})
			},
			async borrarEntrada(id, tipo, index){
				if(confirm('¿Está seguro que desea borrar este registro?')){
					let datos = new FormData();
					datos.append('accion', 'borrarRegistro')
					datos.append('id', id);
					await fetch('php/caja.php',{
						method:'POST', body:datos
					})
					.then(serv => serv.text())
					.then(resp => {
						console.log(resp)
						if(resp =='ok') 
							if(tipo =='ingreso') this.registros.ingresos.splice(index, 1)
							if(tipo =='salida') this.registros.salidas.splice(index, 1)
					})
				}
			},
			async buscarCajas(){
				let datos = new FormData();
				datos.append('accion', 'buscarCajas')
				datos.append('fecha', this.fecha);
				await fetch('php/caja.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(resp => {
					console.log(resp)
					this.cuadres = resp
					$('#verCajas').modal('show')
					
				})
			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
			},
			menos1Hora(fechita){
				return moment(fechita).subtract(1, 'hour').format('DD/MM/YYYY hh:mm a')
			}
		},
		computed:{
			sumaVentas(){
				let suma = 0;
				if( Array.isArray(this.registros.ventas) )
					this.registros.ventas.forEach(elemento => {
						suma += parseFloat(elemento.totalFinal)
					});
				return suma.toFixed(2)
			},
			sumaSalidas(){
				let suma = 0;
				if( Array.isArray(this.registros.salidas) )
					this.registros.salidas.forEach(elemento => {
						suma += parseFloat(elemento.monto)
					});
				return suma.toFixed(2)
			},
			sumaIngresos(){
				let suma = 0;
				if( Array.isArray(this.registros.ingresos) )
					this.registros.ingresos.forEach(elemento => {
						suma += parseFloat(elemento.monto)
					});
				return suma.toFixed(2)
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