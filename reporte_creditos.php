<?php
include 'php/conexion.php';
include "generales.php";

if( !isset($_COOKIE['ckidUsuario']) ){ header("Location: index.html");
	die(); }
include "generales.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Reporte de Créditos - Facturador electrónico</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="icofont.min.css">
	<link rel="stylesheet" href="css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/anksunamun.css">
	<link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="css/tableexport.min.css">
	<link rel="shortcut icon" href="images/VirtualCorto.png" type="image/png">
</head>
<body>

<?php include 'menu-wrapper.php'; ?>

<main class="mt-3" id="app">
	<div class="container-fluid  px-5">
		<div class="row">
		<div class="col-md-3 text-center">
			<img src="<?= $_COOKIE['logo']?>" style='max-width: 30%'>
		</div>
		<div class="col ml-4">
			<h3 class="display-4">Reporte de créditos</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		
		<div class="card mt-2">
			<div class="card-body">
				<div class="row row-cols-12 row-cols-md-4">
					
					<div class="col">
						<label for="">Fecha de inicio</label>
						<input type="date" class="form-control" v-model="fechas.inicio">
					</div>
					<div class="col d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="motivoBusqueda='fecha';buscarCredito()"><i class="icofont-search-1"></i> Filtrar</button>
					</div>
				</div>
			</div>
		</div>
		

		<p v-if="creditos.length>0" class="mt-3 mb-0">Todos los créditos pendientes por cancelar</p>
		<div class="table-responsive" >
			<table class="table table-hover mt-3" id="tablaCabeceras" v-if="creditos.length>0">
				<thead>
					<tr>
						<th>N°</th>
						<th>Fecha</th>
						<th>Monto</th>
						<th>Cliente</th>
						<th>Estado</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(credito , index) in creditos">
						<td>{{ index+1 }}</td>
						<td>{{ fechaLatam(credito.fecha) }}</td>
						<td>{{ parseFloat(credito.monto).toFixed(2) }}</td>
						<td>{{ credito.razonSocial }}</td>
						<td>
							<span v-if="credito.estado=='0'">Pendiente</span>
							<span v-if="credito.estado=='1'">Pagado</span>
						</td>
						<td>
							<button class="btn btn-outline-primary" @click="verCompra(credito.factSerie, credito.factCorrelativo)"><i class="icofont-ticket"></i> Ver</button>
							<button class="btn btn-outline-danger ml-3" @click="pagarCredito(index)" title="Cancelar Pago"><i class="icofont-credit-card"></i> Pagar</button>
						</td>
					</tr>
				</tbody>
			</table>
			<p class="mt-3" v-if="creditos.length==0">No hay créditos en esta fecha </p>
		</div>

	</div>

	<div>
		<!-- Modal -->
		<div class="modal fade" id="modalCompras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Compra N° {{creditoUbicada.cabecera.idCompra}}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p class=""><strong>Cabecera de la credito</strong></p>
						<p class="mb-0"><strong>Proveedor:</strong> <span>{{ creditoUbicada.cabecera.razonsocial }}</span></p>
						<p class="mb-0"><strong>Comprobante:</strong> <span>{{ comprobantes[creditoUbicada.cabecera.idComprobante] }}</span></p>
						<p class="mb-0"><strong>Serie - Correlativo:</strong> <span>{{ creditoUbicada.cabecera.serie }}</span></p>
						<p class="mb-0"><strong>Fecha:</strong> <span>{{ fechaLatam(creditoUbicada.cabecera.fecha) }}</span></p>
						<p class="mb-0"><strong>N° Bultos:</strong> <span>{{ creditoUbicada.cabecera.bultos }}</span></p>
						<p class="mb-0"><strong>Observaciones:</strong> <span>{{ creditoUbicada.cabecera.observaciones }}</span></p>
						<hr>
						<p class=""><strong>Detalles de la credito</strong></p>
						<table class="table table-sm table-hover">
							<thead>
								<tr>
									<th>N°</th>
									<th>Producto</th>
									<th>Cantidad</th>
									<th>Cod. Barras</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(producto, index) in creditoUbicada.detalles">
									<td>{{ index+1 }}</td>
									<td>{{ producto.prodDescripcion }}</td>
									<td>{{ producto.cantidad }}</td>
									<td>{{ producto.serie }}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cerrar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>



<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/moment.js"></script>


<script src="js/bootstrap-datepicker.js?version=1.0.1"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>


<script>
	const { createApp } = Vue

	createApp({
		data() {
			return {
				serie:'', creditos:[],creditoUbicada:{cabecera:{}, detalles:{}}, motivoBusqueda:'todos', caja:[],
        comprobantes: ['Boleta de Venta', 'Factura', 'Cheque', 'Cotización', 'Guía de remisión', 'Letra', 'Liquidación', 'Nota de débido', 'Nota de crédito', 'Nota de pedido', 'Orden de traslado',  'Otros','Producción', 'Proforma', 'Recibo', 'Reporte diario', 'Ticket' , 'Voucher' ], buscando:false, fechas:{inicio: moment().format('YYYY-MM-DD'), fin: moment().format('YYYY-MM-DD')}
			}
		},
		methods:{
			async buscarCredito(){
				var datos = new FormData();
				datos.append('filtro', this.motivoBusqueda)
				datos.append('inicio', this.fechas.inicio)
				const servidor = await fetch('php/buscarCreditoFecha.php',{
					method:'POST', body: datos
				})
				this.creditos = await servidor.json()
				console.log(this.creditos)

			},
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
					}
				})
			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
			},
			async verCompra(serie, correlativo){
				window.open (`ticket.php?serie=${serie}&correlativo=${correlativo}`, '_blank');
			},
			async pagarCredito(index){
				if(confirm(`¿Deseas cancelar la deduda de S/ ${parseFloat(this.creditos[index].monto).toFixed(2)} del cliente: ${this.creditos[index].razonSocial}?`)){
					var entrada = { idProceso: 7, descripcion: 'Pago de crédito de la venta '+this.creditos[index].factSerie+"-"+this.creditos[index].factCorrelativo, monto: this.creditos[index].monto };
					var datos = new FormData();
					datos.append('idCredito', this.creditos[index].id)
					datos.append('idCaja', this.caja.id)
					datos.append('entrada', JSON.stringify(entrada))
					const servidor = await fetch('php/cancelarCredito.php',{
						method:'POST', body: datos
					})
					const respuesta = await servidor.text();
					console.log(respuesta);
					if(respuesta=='ok'){
						location.reload();
					}else{
						alert('Hubo un error actualizando');
					}

				}
			}
		},
		mounted(){
			this.verificarCaja();
			this.buscarCredito();
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
</style>

</body>
</html>