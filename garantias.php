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
	<title>Garantías - Facturador electrónico</title>
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

<section class="mt-3" id="app">
	<div class="container-fluid  px-5">
		<div class="row">
		<div class="col-md-3 text-center">
			<img src="<?= $_COOKIE['logo']?>" style='max-width: 30%'>
		</div>
		<div class="col ml-4">
			<h3 class="display-4">Garantías</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		
		<div class="card mt-2">
			<div class="card-body">
				<div class="row row-cols-12 row-cols-md-4">
					<div class="col">
						<label for="">Serie</label>
						<input type="text" class="form-control" v-model="serie" @keyup.enter="buscarSerie()">
					</div>
					<div class="col d-flex align-items-end">
						<button class="btn btn-outline-primary" @click="buscarSerie()"><i class="icofont-search-1"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>
		

		<div class="table-responsive">
			<table class="table table-hover mt-3" id="tablaCabeceras" >
				<thead>
					<tr>
						<th>N°</th>
						<th>Tipo</th>
						<th>Correlativo</th>
						<th>Cliente</th>
						<th>Fecha</th>
						<th>Producto</th>
						<th>Cantidad</th>
						<th>P. Unit.</th>
						<th>Sub Total</th>
						<th>Serie</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(garantia , index) in garantias">
						<td>{{ index+1 }}</td>
						<td>
							<span v-if="garantia.factTipoDocumento==1">Factura</span>
							<span v-if="garantia.factTipoDocumento==3">Boleta de Venta</span>
							<span v-if="garantia.factTipoDocumento==0">Nota Interna</span>
							<span v-if="garantia.factTipoDocumento==-1">Proforma</span>
						</td>
						<td>
							<a :href="`printComprobantePDF.php?serie=${garantia.factSerie??''}&correlativo=${garantia.factCorrelativo}`" target="_blank">
								<span v-if="garantia.factTipoDocumento ==1 || garantia.factTipoDocumento ==3 ">{{ garantia.facSerieCorre }}</span>
								<span v-if="garantia.factTipoDocumento ==0">Int{{ garantia.facSerieCorre }}</span>
							</a>
						</td>
						<td>{{ garantia.razonSocial }} </td>
						<td>{{ fechaLatam(garantia.fechaEmision) }}</td>
						<td class="text-capitalize">{{ garantia.descripcionItem }} </td>
						<td>{{ garantia.cantidadItem }} </td>
						<td>{{ garantia.valorUnitario }} </td>
						<td>{{ garantia.mtoPrecioVenta }} </td>
						<td>{{ garantia.serie }} </td>
						<td> @ </td>
					</tr>
			
				</tbody>
			</table>
		</div>
		<div class="d-none table-responsive" id="divTablaSysCont"></div>

	</div>
</section>












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
				serie:'', garantias:[]
			}
		},
		methods:{
			async buscarSerie(){
				let datos = new FormData()
				datos.append('serie', this.serie)
				await fetch('php/buscarSerie.php',{
					method:'POST', body: datos
				})
				.then(serv=> serv.json())
				.then(res=> this.garantias = res)

			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
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