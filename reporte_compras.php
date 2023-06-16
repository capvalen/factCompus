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
	<title>Reporte Compras - Facturador electrónico</title>
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
			<h3 class="display-4">Reporte compras</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		
		<div class="card mt-2">
			<div class="card-body">
				<div class="row row-cols-12 row-cols-md-4">
					<div class="col">
						<div class="custom-control custom-radio">
							<input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" @change="motivoBusqueda='serie'; compras=[]; buscando=false" checked>
							<label class="custom-control-label" for="customRadio1">Buscar por serie</label>
						</div>
						<div class="custom-control custom-radio">
							<input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" @change="motivoBusqueda='fecha'; compras=[]; buscando=false" >
							<label class="custom-control-label" for="customRadio2">Buscar por fechas</label>
						</div>
					</div>
					<div class="col" v-if="motivoBusqueda=='serie'">
						<label for="">Ingrese la serie del producto</label>
						<input type="text" class="form-control" v-model="serie" @input="buscando=false" @keyup.enter="buscarSerie()" >
					</div>
					<div class="col" v-if="motivoBusqueda=='fecha'">
						<label for="">Fecha de inicio</label>
						<input type="date" class="form-control" v-model="fechas.inicio">
					</div>
					<div class="col" v-if="motivoBusqueda=='fecha'">
						<label for="">Fecha de Fin</label>
						<input type="date" class="form-control" v-model="fechas.fin">
					</div>
					<div class="col d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="buscarSerie()"><i class="icofont-search-1"></i> Filtrar</button>
					</div>
				</div>
			</div>
		</div>
		

		<div class="table-responsive" >
			<table class="table table-hover mt-3" id="tablaCabeceras" v-if="motivoBusqueda=='serie'">
				<thead>
					<tr>
						<th>N°</th>
						<th>Producto</th>
						<th>Cantidad</th>
						<th>Precio Unt.</th>
						<th>Proveedor</th>
						<th>Fecha</th>
						<th>Comprobante</th>
						<th>Correlativo</th>
						<th>Observaciones</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(compra , index) in compras">
						<td>{{ index+1 }}</td>
						<td>{{ compra.prodDescripcion }}</td>
						<td>{{ compra.cantidad }}</td>
						<td>{{ parseFloat(compra.subTotal).toFixed(2) }}</td>
						<td>{{ compra.razonsocial }}</td>
						<td>{{ fechaLatam(compra.fecha) }}</td>
						<td>{{ comprobantes[compra.idComprobante] }}</td>
						<td>{{ compra.correlativo }}</td>
						<td>{{ compra.observaciones }}</td>
						<td> <button class="btn btn-primary" @click="verCompra(compra.idCompra)">Ver compra</button> </td>
					</tr>
					<tr v-if="compras.length==0 && buscando" ><td colspan="8">No hay registros encontrados con «{{serie}}»</td></tr>
			
				</tbody>
			</table>
			<table class="table table-hover mt-3" v-if="motivoBusqueda=='fecha'">
				<thead>
					<tr>
						<th>N°</th>
						<th>Proveedor</th>
						<th>Fecha</th>
						<th>Comprobante</th>
						<th>Correlativo</th>
						<th>Observaciones</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(compra , index) in compras">
						<td>{{ index+1 }}</td>
						<td>{{ compra.razonsocial }}</td>
						<td>{{ fechaLatam(compra.fecha) }}</td>
						<td>{{ comprobantes[compra.idComprobante] }}</td>
						<td>{{ compra.correlativo }}</td>
						<td>{{ compra.observaciones }}</td>
						<td> <button class="btn btn-primary" @click="verCompra(compra.idCompra)">Ver compra</button> </td>
					</tr>
					<tr v-if="compras.length==0 && buscando" ><td colspan="8">No hay registros encontrados con «{{serie}}»</td></tr>
			
				</tbody>
			</table>
		</div>

	</div>

	<div>
		<!-- Modal -->
		<div class="modal fade" id="modalCompras" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-center modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Compra N° {{compraUbicada.cabecera.idCompra}}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<p class=""><strong>Cabecera de la compra</strong></p>
						<p class="mb-0"><strong>Proveedor:</strong> <span>{{ compraUbicada.cabecera.razonsocial }}</span></p>
						<p class="mb-0"><strong>Comprobante:</strong> <span>{{ comprobantes[compraUbicada.cabecera.idComprobante] }}</span></p>
						<p class="mb-0"><strong>Serie - Correlativo:</strong> <span>{{ compraUbicada.cabecera.serie }}</span></p>
						<p class="mb-0"><strong>Fecha:</strong> <span>{{ fechaLatam(compraUbicada.cabecera.fecha) }}</span></p>
						<p class="mb-0"><strong>N° Bultos:</strong> <span>{{ compraUbicada.cabecera.bultos }}</span></p>
						<p class="mb-0"><strong>Observaciones:</strong> <span>{{ compraUbicada.cabecera.observaciones }}</span></p>
						<hr>
						<p class=""><strong>Detalles de la compra</strong></p>
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
								<tr v-for="(producto, index) in compraUbicada.detalles">
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
				serie:'', compras:[],compraUbicada:{cabecera:{}, detalles:{}}, motivoBusqueda:'serie',
        comprobantes: ['Boleta de Venta', 'Factura', 'Cheque', 'Cotización', 'Guía de remisión', 'Letra', 'Liquidación', 'Nota de débido', 'Nota de crédito', 'Nota de pedido', 'Orden de traslado',  'Otros','Producción', 'Proforma', 'Recibo', 'Reporte diario', 'Ticket' , 'Voucher' ], buscando:false, fechas:{inicio: moment().format('YYYY-MM-DD'), fin: moment().format('YYYY-MM-DD')}
			}
		},
		methods:{
			async buscarSerie(){
				this.buscando=true;
				let datos = new FormData()
				if(this.motivoBusqueda=='serie' && this.serie!='' ){
					datos.append('serie', this.serie)
					await fetch('php/buscarCompraSerie.php',{
						method:'POST', body: datos
					})
					.then(serv=> serv.json())
					.then(res=> this.compras = res)
				}
				if(this.motivoBusqueda=='fecha'){
					datos.append('inicio', this.fechas.inicio)
					datos.append('fin', this.fechas.fin)
					await fetch('php/buscarCompraFecha.php',{
						method:'POST', body: datos
					})
					.then(serv=> serv.json())
					.then(res=> this.compras = res)
					console.log(this.compras);
				}

			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
			},
			async verCompra(id){
				let datos = new FormData();
				datos.append('id', id)
				await fetch('php/buscarCompra.php',{
					method:'POST', body:datos
				})
				.then(serv => serv.json())
				.then(res => {console.log(res);this.compraUbicada = res})
				$('#modalCompras').modal('show')
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
</style>

</body>
</html>