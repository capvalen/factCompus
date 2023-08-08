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
	<title>Compras - Facturador electrónico</title>
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

<div id="app">
	<div class="container-fluid mt-5 px-5">
		<div class="row">
		<div class="col-md-3 text-center">
			<img src="<?= $_COOKIE['logo']?>" style='max-width: 30%'>
		</div>
		<div class="col ml-4">
			<h3 class="display-4">Nueva compra</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		

		<div class="card mt-2">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-3">
						<label for="">Origen</label>
						<select class="form-control" v-model="cabecera.origen">
							<option value="1">Tiendas / Almacenes</option>
							<option value="2">Clientes</option>
							<option value="3">Proveedores</option>
						</select>
					</div>
					<div class="col-12 col-md-3">
						<label for="">Fecha</label>
						<input type="date" class="form-control" v-model="cabecera.fecha">
					</div>
					<div class="col-12 col-md-3">
						<label for="">Comprobante</label>
						<select class="form-control" v-model="cabecera.comprobante">
							<option v-for="(comprobante, index) in comprobantes" :value="index">{{comprobante}}</option>
						</select>
					</div>
					<div class="col-12 col-md-3">
						<label for="">Serie - Correlativo</label>
						<input type="text" class="form-control" v-model="cabecera.correlativo">
					</div>

				</div>
				<div class="row mt-2">
					<div class="col-12 col-md-3">
						<label for="">Proveedor</label>
						<select class="form-control" v-model="cabecera.proveedor">
							<option v-for="provider in proveedores" :value="provider.id">{{provider.razon}}</option>
						</select>
					</div>
					<div class="col-12 col-md-3">
						<label for="">N° Bultos</label>
						<input type="number" class="form-control" v-model="cabecera.bultos">
					</div>
					<div class="col-12 col-md-3">
						<label for="">Observaciones</label>
						<input type="text" class="form-control" v-model="cabecera.observaciones">
					</div>
					<div class="d-flex align-items-end">
					<button class="btn btn-outline-primary " id="btnCrearCompra" @click="checkVacios()"><i class="icofont-box"></i> Generar compra</button>
					</div>
				</div>
			</div>
		</div>

		<div class="table-responsive">
			<table class="table table-hover mt-3" id="tlbProductosTodos">
				<thead>
					<tr>
						<th>N°</th>
						<th>Nombre de producto</th>
						<th>Cantidad</th>
						<th>Precio Unit.</th>
						<th>Stock Actual</th>
						<th>¿Series?</th>
						<th>Marca</th>
						<th>Línea</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(producto, index) in cesta" :key="producto.id">
						<td>{{ index+1 }}</td>
						<td class="text-capitalize">{{ producto.nombre }}</td>
						<td><input type="number" class="form-control" v-model="producto.cantidad"></td>
						<td><input type="number" class="form-control" v-model="producto.precioCompra"></td>
						<td>{{ producto.stock }}</td>
						<td>{{ (producto.series==1)? 'Sí': 'No' }}</td>
						<td>{{ producto.marca }}</td>
						<td>{{ producto.linea }}</td>
						<td><button class="btn btn-outline-danger btn-sm" @click="quitarCesta(index)"><i class="icofont-trash"></i></button></td>
					</tr>
					<tr>
						<td>-</td>
						<td><input type="text" class="form-control" placeholder="Buscar producto" v-model="texto" @keypress.enter="buscarProducto()"></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<section>
		<!-- Modal para: -->
		<div class='modal fade' id='modalCoincidencias' tabindex='-1'>
			<div class='modal-dialog modal-lg modal-dialog-centered'>
				<div class='modal-content'>
					<div class='modal-body'>
						<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
						<h5 class='modal-title'>Coincidencias de búsqueda</h5>
						<table class="table table-hover table-sm">
							<thead>
								<tr>
									<th>Cod.</th>
									<th>Nombre</th>
									<th>Marca</th>
									<th>Línea</th>
									<th>@</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(producto, index) in productosNombre" @click="subirCesta('producto', index, producto.idProductos)" style="cursor:pointer;">
									<td>{{ producto.idProductos }}</td>
									<td class="text-capitalize">{{ producto.prodDescripcion }}</td>
									<td>{{ producto.marca }}</td>
									<td>{{ producto.linea }}</td>
									<td><button class="btn btn-outline-primary btn-sm" ><i class="icofont-ui-add"></i></button></td>

								</tr>
								<tr v-for="(producto, index) in productosSerie" @click="subirCesta('serie', index, producto.idProductos)" style="cursor:pointer;">
									<td>{{ producto.idProductos }}</td>
									<td class="text-capitalize">{{ producto.prodDescripcion }}</td>
									<td>{{ producto.marca }}</td>
									<td>{{ producto.linea }}</td>
									<td><button class="btn btn-outline-primary btn-sm" ><i class="icofont-ui-add"></i></button></td>
								</tr>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal para: -->
		<div class='modal fade' id='modalRellenarSeries' tabindex='-1' data-backdrop="static" data-keyboard="false" >
				<div class='modal-dialog modal-lg modal-dialog-centered'>
					<div class='modal-content'>
						<div class='modal-body'>
							<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
							
							<p class="mt-4 mb-2">Rellene las series que hacen falta:</strong></p>
							<table class="table table hover">
								<thead>
									<td>N°</td>
									<td>Cant.</td>
									<td>Producto</td>
									<td>Serie</td>
									<td>@</td>
								</thead>
								<tbody id="tLineasSerie">
									<tr v-for="(separa, index) in separados" >
										<td>{{index+1}}</td>
										<td>{{separa.cantidad}}</td>
										<td class="text-capitalize">{{ separa.nombre }} <span v-if="separa.repite!=undefined">N° {{separa.repite+1}}</span></td>
										<td v-if="separa.pideSerie=='1'"> <input class="form-control" type="text" v-model="separa.series" @keyup.enter="siguienteLinea(index)"> </td>
										<td v-else>-</td>
										<td><button class="btn btn-sm btn-outline-danger" @click="limpiarLinea(index)"><i class="icofont-eraser"></i></button></td>
									</tr>
								</tbody>
							</table>
							
							<div class='d-flex justify-content-end mt-3'>
								<button type='button' class='btn btn-outline-primary btn-sm' @click="guardar()"><i class="icofont-paper"></i> Guardar compra</button>
							</div>
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
<script src="js/alertify.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="js/axios.min.js"></script>


<script>
  const { createApp } = Vue

  createApp({
    data() {
      return {
				texto:'',
        comprobantes: ['Boleta de Venta', 'Factura', 'Cheque', 'Cotización', 'Guía de remisión', 'Letra', 'Liquidación', 'Nota de débido', 'Nota de crédito', 'Nota de pedido', 'Orden de traslado',  'Otros','Producción', 'Proforma', 'Recibo', 'Reporte diario', 'Ticket' , 'Voucher' ],
				cabecera:{correlativo: '000-0001', fecha: moment().format('YYYY-MM-DD'), bultos:1, observaciones:'', origen:1, comprobante:1, proveedor:6
				 }, productosNombre:[], productosSerie:[],
				cesta:[], separados:[], proveedores:[]
      }
    },
		mounted(){
			this.getDatos()
		},
		methods:{
			async getDatos(){
				await fetch('php/listarTodosProveedores.php', {method:'POST'})
				.then(res=> res.json())
				.then(datos => this.proveedores = datos)
			},
			buscarProducto(){
				if(this.texto){
					axios.post('php/buscarProducto.php', {texto: this.texto})
					.then((response)=>{ console.log( response.data );
						this.productosNombre = response.data.productos
						this.productosSerie = response.data.serie
						if(this.productosSerie.length == 0 && this.productosNombre.length==0)
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> No existe coincidencias en la búsqueda: <br> <small><strong class="ml-3">«'+this.texto+'»</strong></small>');
						else{
							$('#modalCoincidencias').modal('show')
						}
					})
					.catch((error)=>{ console.log( error );});
				}
			},
			subirCesta(tipo, index, id){
				$('#modalCoincidencias').modal('hide')
				this.texto='';
				if(tipo=='producto')
					this.cesta.push({cantidad: 1, nombre: this.productosNombre[index].prodDescripcion, barras:[], stock: this.productosNombre[index].prodStock, id: this.productosNombre[index].idProductos, marca: this.productosNombre[index].marca, linea: this.productosNombre[index].linea, series: this.productosNombre[index].series, precioCompra: this.productosNombre[index].precioCompra })
				if(tipo=='serie')
					this.cesta.push({cantidad: 1, nombre: this.productosSerie[index].prodDescripcion, barras:[], stock: this.productosSerie[index].prodStock, id: this.productosSerie[index].idProductos, marca: this.productosSerie[index].marca, linea: this.productosSerie[index].linea, series: this.productosSerie[index].series, precioCompra: this.productosSerie[index].precioCompra })
				
			},
			checkVacios(){

				if(this.comprobantes.fecha=='')
					alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La fecha debe estar bien rellenada');
				else if( this.comprobantes.correlativo =='' )
					alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> Debe existir un correlativo del comprobante');
				else if( this.cesta.length==0 )
					alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> No se puede guardar una lista vacía');
				else{
					let vacios = 0; this.separados=[];
					this.cesta.forEach(prod=>{ console.log(prod);
						if( prod.cantidad==1 ){
							this.separados.push(prod)
							this.separados[this.separados.length-1].pideSerie= prod.series;
							this.separados[this.separados.length-1].series= '';
							if (prod.series=='' || prod.series=='1') vacios++
						}else{
							if( prod.series=='0' ){
								this.separados.push(prod)
							}else{
								for(i=0; i<prod.cantidad; i++){
									this.separados.push({
										id: prod.id,
										nombre: prod.nombre,
										cantidad: 1,
										unidad: 'Und.',
										unidadSunat: 'NIU',
										precioCompra: prod.precioCompra,
										series: '',
										repite: i,
										pideSerie: prod.series
									});
									if (i>0) vacios++
								}
							}
							vacios++
						}
					});
					//console.log('vacios',vacios);
					if(vacios>0){
						$('#modalRellenarSeries').modal('show');
					}else{
						alertify.message('<i class="bi bi-info-circle"></i> Generando el comprobante, espere').delay(15);
						this.guardar()
					}
				}
			},
			siguienteLinea(index){
				$('#tLineasSerie tr').eq(index+1).find('input').focus();
			},
			limpiarLinea(index){
				$('#tLineasSerie input').eq(index).val('').focus();
			},
			guardar(){
				if(this.verificarTodasCasillas())
					$('#modalRellenarSeries').modal('hide')
					axios.post('php/guardarCompra.php', {
						cabecera: this.cabecera, cesta: this.separados
					})
					.then(res=> { console.log(res.data)
						if(res.data=='ok'){alert('Compra guardada'); location.reload();}
					})
				else
					alertify.error('<i class="bi bi-info-circle"></i> Todas las series se deben rellenar obligatoriamente').delay(15);
			},
			quitarCesta(index){
				this.cesta.splice(index,1)
				this.cesta.splice(index,1)
			},
			verificarTodasCasillas(){
				var todos = document.querySelectorAll('#tLineasSerie input');
				let retorno = true;
				for (const input of todos) {
					if( $.trim(input.value) == '') return false
				}
				return retorno;
			}
		}
  }).mount('#app')
</script>
<style>
	.bg-dark {
		background-color: #7030a0!important;
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