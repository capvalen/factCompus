<?php include '../generales.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pos Ventas - Infocat</title>
	<link rel="shortcut icon" href="../images/VirtualCorto.png" type="image/png">
	<link rel="stylesheet" href="../css/bootstrap.css">
	<link rel="stylesheet" href="css/bootstrap-icons.css">
	<link rel="stylesheet" href="../css/alertify.min.css">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark pl-5" id="menuInfocat" style="background-color: #7030a0!important;">
		<a class="navbar-brand" href="#">
			<img src="../images/VirtualCorto.png" width="60" height="60" alt="">
		</a>
		<a class="navbar-brand" href="#">Facturador Infocat </a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
			<div class="navbar-nav">
				<a class="nav-item nav-link " href="../facturador.php" id=""><i class="icofont-group"></i> Facturador</a>
				<a class="nav-item nav-link " href="../reportes.php" id=""><i class="icofont-group"></i> Reportes</a>
			
				<a class="nav-item nav-link " href="../desconectar.php"><i class="icofont-addons"></i> <i class="bi bi-plug"></i> Desconetarse</a>
			</div>
		</div>
	</nav>

	<div id="app" class="container-fluid">
		<div class="row">
			<div class="col-md-3 p-3 container-fluid" id="cuadroCliente">
				<div id="divBasicoCliente">
					<div class="row my-3 text-secondary">
						<div class="col-2 p-0 d-flex align-items-center justify-content-center "> 
							<i class="bi bi-person-badge" style="font-size: 1.5rem;" ></i> 
						</div>
						<div class="col p-0"> <p class="m-0 text-uppercase"> <span>{{clienteActual.razon}}</span></p> <p class="m-0"><small>{{clienteActual.dni}}</small></p> <p class="m-0 text-capitalize"><small>{{clienteActual.direccion}}</small></p> </div>
					</div>
					
					<input type="search" class="form-control" placeholder='Buscar Clientes' v-model="cliBuscar" v-on:keyup.enter="buscarCliente()">
					<div class="row p-2">
						<div class="col">
							<button class="btn btn-outline-primary btn-block py-2" id="btnNuevo" @click="activarCreacion('persona')"> Persona </button>
						</div>
						<div class="col">
							<button class="btn btn-outline-primary btn-block py-2" id="btnNuevo" @click="activarCreacion('empresa')"> Empresa </button>
						</div>
					</div>
					<div id="listadoClientes" class="pt-3">
						<div class="row border-bottom p-2" v-for="(cliente, index) in clientes" @click="seleccionarCliente(index)">
							<div class="col-1 d-flex align-items-center d-justify-content-center" >
								<i class="bi bi-building" style="font-size: 1.5rem;" v-if="cliente.cliRuc.indexOf('20')==0"></i>
								<i class="bi bi-person-circle" style="font-size: 1.5rem;" v-else></i>
							</div>
							<div class="col">
								<p class="mb-0 razon"><strong>{{cliente.cliRazonSocial}}</strong></p> <p class="mb-0 ruc">DNI: {{cliente.cliRuc}}</p>
							</div>
							<div class="col-1 d-flex align-items-center d-justify-content-center" @click.prevent="activarEditar(index)"> <i class="bi bi-pen-fill"></i> </div>
						</div>
					</div>
				</div>
				<div class="pt-3 d-none" id="divRegistroCliente">
					<p><strong>Registro de cliente</strong></p>
					<input type="text" class="form-control" placeholder='Nombres y apellidos' autocomplete="nope" id="txtNombres" v-model="clienteActual.razon">
					<div class="row">
						<div class="col">
							<input type="text" class="form-control" placeholder='DNI' autocomplete="nope" id="txtDni" v-model="clienteActual.dni">
						</div>
						<div class="col">
							<input type="text" class="form-control" placeholder='Celular' autocomplete="nope" id="txtCelular" v-model="clienteActual.celular">
						</div>
					</div>
					<input type="text" class="form-control" placeholder='Dirección' autocomplete="nope" id="txtDireccion" v-model="clienteActual.direccion">
					<input type="text" class="form-control" placeholder='Correo electrónico' autocomplete="nope" id="txtCorreo" v-model="clienteActual.correo">
					<div class="row">
						<div class="col">
							<button class="btn btn-light btn-block" @click="mostrarPanelCliente()">Cancelar</button>
						</div>
						<div class="col">
							<button class="btn btn-primary btn-block" @click="guardarDatos()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-5 p-3 border-left">
				<h5 class="text-secondary"><i class="bi bi-cart"></i> Canasta</h5>
				<div class="row">
					<div class="col-8">
						<div class="form-group">
							<label for="sltTipoVenta">¿Qué deseas generar?</label>
							<select id="sltTipoVenta" class="form-control" name="" v-model="tipoVenta" @change="cambiarTipoVenta">
								<option value="0">Ticket interno</option>
								<option value="3">Boleta de venta</option>
								<option value="1">Factura</option>
								<option value="-1">Proforma</option>
							</select>
						</div>
						<div class="d-flex justify-content-between">
							<label><input type="checkbox" id="chkImprimir" v-model="impresionTicket"> <span v-if="impresionTicket">Imprimir ticket</span> <span v-else>Sin impresión</span></label>
							<label><input type="checkbox" id="chkImprimir" v-model="pagarTotal"> <span v-if="pagarTotal">Pago total</span> <span v-else>No dar crédito</span></label>
						</div>
						<br>
					</div>
					<div class="col ">
						<?php if($cajaAbierta['abierto']==1): ?>
						<button class="btn btn-outline-success float-right mt-4" @click="emitir()"><i class="bi bi-bookmark-star"></i> Emitir </button>
						<?php endif; ?>
					</div>
				</div>
				<div class="row mb-3" v-if="!pagarTotal">
					<div class="col">
						<button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalAddCredito"> <i class="bi bi-bar-chart-steps"></i> Agregar fecha de crédito</button>
					</div>
				</div>
				<section class="mb-3 row" v-if="creditos.length>0 && !pagarTotal">
					<div class="col-8 mx-auto">
						<p class="mb-0 text-muted">Créditos</p>
						<ul class="list-group ">
							<li class="list-group-item d-flex justify-content-between align-items-center p-2" v-for="(nCredito, nIndex) in creditos">
								<span><strong>Fecha {{nIndex+1}}:</strong> {{fechaLatam(nCredito.fecha)}} con el  monto: S/ {{parseFloat(nCredito.monto).toFixed(2)}}</span>
								<button class="btn btn-sm border-0 btn-outline-danger" @click="creditos.splice(nIndex, 1)"><i class="bi bi-eraser"></i></button>
							</li>
						</ul>
					</div>
				</section>
				<?php if($cajaAbierta['abierto']==0): ?>
				<div class="row">
					<div class="col">
						<div class="alert alert-danger mt-2 alert-dismissible fade show" role="alert">
							<i class="bi bi-exclamation-circle"></i> No hay ninguna caja abierta, debe abrir una caja para hacer ventas
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				<?php endif; ?>
				
				
			
				<p class="mb-1"><i class="bi bi-upc-scan"></i> Escanee o haga una búsqueda:</p>

				<input type="text" class="form-control" placeholder="Descripción o código de barras" id="txtBusquedaProducto" v-on:keyup.enter="buscarProducto()" v-model="prodBuscar">

				<div class="row" v-if="productosNombre.length >0 || productosSerie.length>0">
					<div class="col"><p class="my-2"><small><strong>Coincidencias</strong></small></p></div>
					<div class="col mt-2"><button class="btn btn-outline-success btn-sm float-right border-0" @click="productosNombre=[]; productosSerie=[]; prodBuscar='';"><i class="bi bi-eraser"></i> Limpiar</button></div>
				</div>
				<div class="divProductosVarios" v-if="productosNombre.length >0">
					<div class="row py-2 border-bottom noselect" v-for="(busqueda, item) in productosNombre" @click="llenarProductos(item)">
						<div class="col text-capitalize">
							<small>{{item+1}}. {{busqueda.prodDescripcion}}</small>
							<p class="mb-0" v-if="busqueda.similares!=''"><small><strong>Similares:</strong> {{busqueda.similares}}</small></p>
						</div> 
						<div class="col-2 text-secondary"><small><strong>{{busqueda.prodStock}} unds.</strong></small></div> 
						<div class="col-2 text-secondary"><small><strong>S/ {{parseFloat(busqueda.prodPrecio).toFixed(2)}}</strong></small></div> 
						<div class="col-1 d-flex justify-content-center"><i class="bi bi-box-arrow-right"></i></div>
					</div>
					</div>
				<div class="divProductosVarios" v-if="productosSerie.length >0">
					<div class="row py-2 border-bottom noselect" v-for="(busqueda2, item2) in productosSerie" @click="llenarProductosSerie(item2)">
						<div class="col text-capitalize">
							<small>{{item2+1}}. {{busqueda2.prodDescripcion}}</small>
							<p class="mb-0" v-if="busqueda2.similares!=''"><small><strong>Similares:</strong> {{busqueda2.similares}}</small></p>
						</div> 
						<div class="col-2 text-secondary"><small><strong>{{busqueda2.prodStock}} unds.</strong></small></div> 
						<div class="col-2 text-secondary"><small><strong>S/ {{parseFloat(busqueda2.prodPrecio).toFixed(2)}}</strong></small></div> 
						<div class="col-1 d-flex justify-content-center"><i class="bi bi-box-arrow-right"></i></div>
					</div>

				</div>

			</div>
			<div class="col-md-4 p-3">
				
				<center><img :src="'../'+empresa.logo" alt="" style="max-width: 30%;"></center>
				<div id="canastaProductos">
					<p class="text-center mb-1"><strong>{{venta}}</strong></p>

					<div id="datosEmpresa">
						<p class="text-center mb-0"><strong>{{empresa.ruc}}</strong></p>
						<p class="text-center mb-0" v-if="empresa.nomComercial!=''"><strong>{{empresa.nomComercial}}</strong></p>
						<p class="text-center mb-0" v-if="empresa.ruc.substring(0,2)=='10'"><small><span v-if="empresa.nomComercial!=''">De:</span> {{empresa.razonSocial}}</small></p>
						<p class="text-center mb-0"><small>{{empresa.direccion}}</small></p>
						<p class="text-center mb-0" v-if="empresa.celular!=''"><small>{{empresa.celular}}</small></p>
					</div>
					<div class="row mt-3">
						<div class="col"><p class="mb-0"><small>Ud. lleva a cuenta:</small></p></div>
						<div class="col-3 text-center"><p class="mb-0"><small><strong>S/ <span>{{sumaTotal}}</span></strong></small></p></div>
					</div>
					<div class="border-bottom"></div>
					<div id="divCanastaProductos">
						<div class="row py-2 border-bottom" v-for="(producto, item) in canasta" @click="editarProducto(item)">
							<div class="col-1 cuadroMouse noselect d-flex justify-content-center" @click.stop="restarItem(item)"><i class="bi bi-dash-square"></i></div>
							<div class="col noselect text-capitalize"><small>{{producto.cantidad}} {{producto.unidad}} {{producto.nombre}} <span v-if="producto.serie!=''">({{producto.serie}})</span></small></div>
							<div class="col-2 noselect text-secondary"><small><strong>{{parseFloat(producto.subTotal).toFixed(2)}}</strong></small> </div>
							<div class="col-1 cuadroMouse noselect d-flex justify-content-center" @click.stop="sumarItem(item)"> <i class="bi bi-plus-square"></i></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para: -->
		<div class='modal fade' id='modalEditarProducto' tabindex='-1' data-backdrop="static" data-keyboard="false" >
			<div class='modal-dialog modal-sm modal-dialog-centered'>
				<div class='modal-content'>
					<div class='modal-body'>
						<!-- <button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button> -->
						
						<p class="mt-4 mb-2">Estas editando: <strong class="text-capitalize" id="queEdito"></strong></p>
						<p>Precio: S/ <span id="txtPrecioProducto"></span></p>
						<label for="">Cantidad</label>
						<input type="number" class="form-control mb-2" v-on:keyup.enter="actualizarProducto()" v-if="canasta.length>0 && idProducto>=0" v-model="canasta[idProducto].cantidad" min="1" >
						<div >
							<label for="">Precios disponibles</label>
							<table>
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Monto</th>
										<th>@</th>
									</tr>
								</thead>
								<tbody>
									<tr >
										<td>Libre</td>
										<td><input type="number" min=0 step=1 value=0 v-model="librePrecio" class="form-control"></td>
										<td><button class="btn btn-outline-success btn-sm" title="Aplicar" @click="precioDe(librePrecio)" data-dismiss="modal"><i class="bi bi-caret-right"></i></button></td>
									</tr>
									<tr v-if="preciosEspeciales.normal>0">
										<td>Normal</td>
										<td>{{parseFloat(preciosEspeciales.normal).toFixed(2)}}</td>
										<td><button class="btn btn-outline-success btn-sm" title="Aplicar" @click="precioDe(preciosEspeciales.normal)" data-dismiss="modal"><i class="bi bi-caret-right"></i></button></td>
									</tr>
									<tr v-if="preciosEspeciales.descuento>0">
										<td>Con descuento</td>
										<td>{{parseFloat(preciosEspeciales.descuento).toFixed(2)}}</td>
										<td><button class="btn btn-outline-success btn-sm" title="Aplicar" @click="precioDe(preciosEspeciales.descuento)" data-dismiss="modal"><i class="bi bi-caret-right"></i></button></td>
									</tr>
									<tr v-if="preciosEspeciales.mayor>0">
										<td>Por mayor</td>
										<td>{{parseFloat(preciosEspeciales.mayor).toFixed(2)}}</td>
										<td><button class="btn btn-outline-success btn-sm" title="Aplicar" @click="precioDe(preciosEspeciales.mayor)" data-dismiss="modal"><i class="bi bi-caret-right"></i></button></td>
									</tr>
								</tbody>
							</table>
							<!-- <select name="" id="sltPreciosEspeciales" class="form-control" @change="precioDe">
								<option :value="preciosEspeciales.normal" v-if="preciosEspeciales.normal>0">Normal</option>
								<option :value="preciosEspeciales.descuento" v-if="preciosEspeciales.descuento>0">Con descuento</option>
								<option :value="preciosEspeciales.mayor" v-if="preciosEspeciales.mayor>0">Por mayor</option>
							</select> -->
						</div>
						<div class='d-flex justify-content-between mt-3'>
							<button type='button' class='btn btn-outline-danger btn-sm' data-dismiss="modal" @click="retirarProducto()"><i class="bi bi-arrow-90deg-left"></i> Retirar</button>
							<button type='button' class='btn btn-outline-primary btn-sm' data-dismiss="modal" @click="actualizarProducto()"><i class="bi bi-arrow-down-up"></i> Actualizar</button>
						</div>
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
								<td>Producto</td>
								<td>Serie</td>
								<td>@</td>
							</thead>
							<tbody id="tLineasSerie">
								<tr v-for="(separa, index) in separados" >
									<td>{{index+1}}</td>
									<td class="text-capitalize">{{ separa.nombre }} <span v-if="separa.repite!=undefined">N° {{separa.repite+1}}</span></td>
									<td v-if="separa.pideSerie=='1'"> <input class="form-control" type="text" v-model="separa.serie" @keyup.enter="siguienteLinea(index)"> </td>
									<td v-else>-</td>
									<td><button class="btn btn-sm btn-outline-danger" @click="limpiarLinea(index)"><i class="bi bi-eraser"></i></button></td>
								</tr>
							</tbody>
						</table>
						
						<div class='d-flex justify-content-end mt-3'>
							<button type='button' class='btn btn-outline-primary btn-sm' data-dismiss="modal" @click="preGuardar()"><i class="bi bi-paper"></i> Emitir Comprobante</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal para ingresar creditos-->
		<div class="modal fade" id="modalAddCredito" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Nueva fecha de crédito</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<label for="">Monto</label>
						<input type="number" step="0.1" min=0 class="form-control" v-model="credito.monto">
						<label for="">Fecha de vencimiento</label>
						<input type="date" class="form-control" v-model="credito.fecha">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-primary" @click="agregarFechaCredito()" data-dismiss="modal"><i class="bi bi-bar-chart-steps"></i> Ingresar fecha</button>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- fin de app -->

<script src="../js/jquery.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/vue_dev.js?v=2.7"></script>
<script src="../js/axios.min.js"></script>
<script src="../js/alertify.min.js"></script>
<script src="../js/moment.js"></script>
<script>
	var app = new Vue({
		el: '#app',
		data: {
			empresa:{ruc: ''},
			cliBuscar: '', prodBuscar:'', tipoVenta:0, venta: 'Ticket interno', serie: null, pagarTotal:true, credito:{monto:0, fecha: moment().add(1,'day').format('YYYY-MM-DD')}, creditos:[],
			clientes: [{
				idCliente: 1,
				cliRazonSocial: 'Cliente simple',
				cliRuc: '00000000'
			}],
			clienteActual:{dni: '00000000', razon: 'Cliente simple', idCliente:1, tipo:'persona', direccion:''},
			modoCliente:null, idProducto:-1, preciosEspeciales:[], librePrecio:0, queMonto:0,
			canasta:[], impresionTicket:true, separados:[],
			productos:[],productosNombre:[],productosSerie:[],
		},
		mounted(){
			this.datosEmpresa();
		},
		methods:{
			datosEmpresa(){
				axios.post('../php/datosEmpresa.php')
				.then((response)=>{ app.empresa= response.data})
				.catch((error)=>{ console.log( error );});
			},
			buscarCliente(){
				if(this.cliBuscar!=''){
					axios.post('../php/buscarCliente.php', { texto: this.cliBuscar })
					.then((response)=>{ console.log( response.data );
						app.clientes= response.data;
					})
					.catch((error)=>{ console.log( error ); });
				}else{
					this.clientes=[];
				}
			},
			activarRegistro(queTipo){
				if(queTipo=='persona'){
					$('#txtNombres').attr('placeholder', 'Nombres y apellidos' );
					$('#txtDni').attr('placeholder', 'DNI / CE' );
					$('#txtCelular').attr('placeholder', 'Celular');
					$('#txtDirección').attr('placeholder', 'Dirección');
					$('#txtCorreo').attr('placeholder', 'Correo elecrtónico');
				}else{
					$('#txtNombres').attr('placeholder', 'Razón social' );
					$('#txtDni').attr('placeholder', 'RUC' );

				}
				$('#divBasicoCliente').addClass('d-none');
				$('#divRegistroCliente').removeClass('d-none');
			},
			activarCreacion(queTipo){
				this.modoCliente='nuevo';
				this.borrarDataCliente(queTipo);
				this.activarRegistro(queTipo);
			},
			borrarDataCliente(queTipo){
				this.clienteActual.dni='';
				this.clienteActual.razon='';
				this.clienteActual.direccion='';
				this.clienteActual.id='';
				this.clienteActual.correo='';
				this.clienteActual.celular='';
				this.clienteActual.tipo=queTipo;
			},
			mostrarPanelCliente(){
				this.clienteActual.dni= '00000000';
				this.clienteActual.razon=  'Cliente simple';
				this.clienteActual.id=1;
				this.clienteActual.direccion='';
				this.clienteActual.celular='';
				this.clienteActual.correo='';
				this.clienteActual.tipo='persona';

				$('#divRegistroCliente').addClass('d-none');
				$('#divBasicoCliente').removeClass('d-none');	
			},
			seleccionarCliente(index){
				this.clienteActual.dni=this.clientes[index].cliRuc;
				this.clienteActual.razon=this.clientes[index].cliRazonSocial;
				this.clienteActual.direccion=this.clientes[index].cliDomicilio;
				this.clienteActual.id=this.clientes[index].idCliente;
				this.clienteActual.celular=this.clientes[index].cliTelefono;
				this.clienteActual.correo=this.clientes[index].cliCorreo;
				if( this.clienteActual.dni.indexOf('20')==0 ){
					this.clienteActual.tipo='empresa';
				}else{
					this.clienteActual.tipo='persona';
				}				
			},
			activarEditar(index){
				this.modoCliente='actualizar';
				this.activarRegistro(this.clienteActual.tipo);
			},
			refrescarDatos(index){
				this.clientes[index].cliRuc = this.clienteActual.dni;
				this.clientes[index].cliRazonSocial = this.clienteActual.razon;
				this.clientes[index].cliDomicilio = this.clienteActual.direccion;
				this.clientes[index].idCliente = this.clienteActual.id;
				this.clientes[index].cliTelefono = this.clienteActual.celular;
				this.clientes[index].cliCorreo = this.clienteActual.correo;
				this.seleccionarCliente(index);
			},
			guardarDatos(){

				if(this.clienteActual.dni.length!=8 && this.clienteActual.dni.length!=11 ){
					alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> DNI/RUC no es válido.').delay(15);
				}
				else if(this.clienteActual.razon==''){
					alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> Rellene la razón social/Nombres.').delay(15);
				}else{
					if(this.modoCliente=='nuevo'){
						axios.post('../php/crearCliente.php', { cliente: this.clienteActual })
						.then((response)=>{ 
							if( parseInt(response.data)>0 ){
								app.clienteActual.id=response.data;
								
							}else{
								app.borrarDataCliente('persona');
							}
							$('#divRegistroCliente').addClass('d-none');
							$('#divBasicoCliente').removeClass('d-none');	
						})
						.catch((error)=>{ app.borrarDataCliente('persona'); console.log( error );})
					}
					if(this.modoCliente=='actualizar'){
						axios.post('../php/actualizarCliente.php', { cliente: this.clienteActual })
						.then((response)=>{ 
							//console.log( response.data );
							if(response.data=='ok'){
								let index =  app.clientes.map(client => client.idCliente).indexOf( app.clienteActual.id );
								app.refrescarDatos(index);
							}
							
							
							$('#divRegistroCliente').addClass('d-none');
							$('#divBasicoCliente').removeClass('d-none');	
						})
						.catch((error)=>{ app.borrarDataCliente('persona'); console.log( error );})
					}
				}
			},
			llenarProductosSerie(item){
				//console.log('total productos es '+ this.productos.length );
				if(this.productosSerie.length==1){
					this.canasta.push({
						id: this.productosSerie[0].idProductos,
						nombre: this.productosSerie[0].prodDescripcion,
						cantidad: 1,
						unidad: 'Und.',
						unidadSunat: 'NIU',
						precio: this.productosSerie[0].prodPrecio,
						mayor: this.productosSerie[0].prodPrecioMayor,
						descuento: this.productosSerie[0].prodPrecioDescto,
						afecto: this.productosSerie[0].idGravado,
						subTotal: this.productosSerie[0].prodPrecio,
						serie: this.productosSerie[0].barra,
						pideSerie: this.productosSerie[0].series,
					});
					//console.log( this.canasta );
				}
				if(this.productosSerie.length>1){
					this.canasta.push({
						id: this.productosSerie[item].idProductos,
						nombre: this.productosSerie[item].prodDescripcion,
						cantidad: 1,
						unidad: 'Und.',
						unidadSunat: 'NIU',
						precio: this.productosSerie[item].prodPrecio,
						mayor: this.productosSerie[item].prodPrecioMayor,
						descuento: this.productosSerie[item].prodPrecioDescto,
						afecto: this.productosSerie[item].idGravado,
						subTotal: this.productosSerie[item].prodPrecio,
						serie: this.productosSerie[item].barra,
						pideSerie: this.productosSerie[item].series,
					});
				}
				this.prodBuscar='';
				$('#txtBusquedaProducto').focus();
			},
			llenarProductos(item){
				//console.log('total productos es '+ this.productosNombre.length );
				if(this.productosNombre.length==1){
					this.canasta.push({
						id: this.productosNombre[0].idProductos,
						nombre: this.productosNombre[0].prodDescripcion,
						cantidad: 1,
						unidad: 'Und.',
						unidadSunat: 'NIU',
						precio: this.productosNombre[0].prodPrecio,
						normal: this.productosNombre[0].prodPrecio,
						mayor: this.productosNombre[0].prodPrecioMayor,
						descuento: this.productosNombre[0].prodPrecioDescto,
						afecto: this.productosNombre[0].idGravado,
						subTotal: this.productosNombre[0].prodPrecio,
						serie:'',
						pideSerie: this.productosNombre[0].series,
					});
					//console.log( this.canasta );
				}
				if(this.productosNombre.length>1){
					console.log('entra en varios');
					this.canasta.push({
						id: this.productosNombre[item].idProductos,
						nombre: this.productosNombre[item].prodDescripcion,
						cantidad: 1,
						unidad: 'Und.',
						unidadSunat: 'NIU',
						precio: this.productosNombre[item].prodPrecio,
						normal: this.productosNombre[item].prodPrecio,
						mayor: this.productosNombre[item].prodPrecioMayor,
						descuento: this.productosNombre[item].prodPrecioDescto,
						afecto: this.productosNombre[item].idGravado,
						subTotal: this.productosNombre[item].prodPrecio,
						serie:'',
						pideSerie: this.productosNombre[item].series,
					});
				}
				this.prodBuscar='';
				$('#txtBusquedaProducto').focus();
			},
			buscarProducto(){
				if(this.prodBuscar!=''){
					this.productos=[];
					axios.post('../php/buscarProducto.php', {texto: this.prodBuscar})
					.then((response)=>{ console.log( response.data );
						this.productosNombre = response.data.productos
						this.productosSerie = response.data.serie
						//this.productos = {productos: response.data.productos, serie: response.data.serie }
						/* this.productos['productos'] = response.data.productos
						this.productos['serie'] = response.data.serie */
						/* response.data.productos.forEach(prod=> app.productos.push(prod) )
						response.data.serie.forEach(prod=> app.productos.push(prod) ) */
						if(this.productosSerie.length == 0 && this.productosNombre.length==0)
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> No existe coincidencias en la búsqueda: <br> <small><strong class="ml-3">«'+this.prodBuscar+'»</strong></small>');
						this.productosNombre.length==1 && this.productosSerie.length==0 ? this.llenarProductos(0) : false;
						this.productosSerie.length==1 && this.productosNombre.length==0 ? this.llenarProductosSerie(0) : false;
					})
					.catch((error)=>{ console.log( error );});
				}
			},
			sumarItem(item){
				this.canasta[item].cantidad += 1;
				this.canasta[item].subTotal =this.canasta[item].cantidad * this.canasta[item].precio ;
				
			},
			restarItem(item){
				event.preventDefault();
				if(this.canasta[item].cantidad>1){
					this.canasta[item].cantidad -= 1;
					this.canasta[item].subTotal =this.canasta[item].cantidad * this.canasta[item].precio ;
				}
				if(this.canasta[item].cantidad==1){
					if(confirm(`¿Desea borrar el item?`)){
						this.canasta.splice(item, 1)
					}
				}
			},
			cambiarTipoVenta(){
				switch( this.tipoVenta){
					case '3': this.venta="Boleta de venta"; break;
					case '1': this.venta="Factura"; break;
					case '0': this.venta="Ticket interno"; break;
					case '-1': this.venta="Proforma"; break;
				}
			},
			emitir(){	
				switch( this.tipoVenta ){
					case 1: case '1': //Factura
						if( this.clienteActual.dni.length!=11 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> Verifique el RUC, no es válido.').delay(15);
						}else if(this.clienteActual.razon==''){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La razón social no puede estar en blanco.').delay(15);
						}else if( this.canasta.length==0 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La factura debe tener al menos 1 producto.').delay(15);
						}else{
							//mandar a guardar e impr
							this.serie= this.empresa.serieFactura;
							this.checkVacios();
						}
					break;
					case 3: case '3': //Boleta
						if( this.clienteActual.dni.length!=8 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> Verifique el DNI, no es válido.').delay(15);
						}else if(this.clienteActual.razon==''){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> El nombre no puede estar en blanco.').delay(15);
						}else if( this.canasta.length==0 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La boleta debe tener al menos 1 producto.').delay(15);
						}else{
							this.serie= this.empresa.serieBoleta;
							//mandar a guardar e impr
							this.checkVacios();
						}
					break;
					case 0: case '0': //Otros=Ticket
						if( this.canasta.length==0 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La venta debe tener al menos 1 producto.').delay(15);
						}else{
							this.serie = '';
							//mandar a guardar e impr
							this.checkVacios();
						}
					break;
					case -1: case '-1':
					if( this.canasta.length==0 ){
							alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La venta debe tener al menos 1 producto.').delay(15);
						}else{
							this.serie = '';
							//mandar a guardar e impr
							this.checkVacios();
						}
					break;
				}
				
			},
			checkVacios(){
				let vacios = 0; this.separados=[];
				this.canasta.forEach(prod=>{ console.log(prod);
					if( prod.cantidad==1 ){
						this.separados.push(prod)
						if (prod.serie=='') vacios++
					}else{
						for(i=0; i<prod.cantidad; i++){
							this.separados.push({
								id: prod.id,
								nombre: prod.nombre,
								cantidad: 1,
								unidad: 'Und.',
								unidadSunat: 'NIU',
								precio: prod.precio,
								mayor: prod.mayor,
								descuento: prod.descuento,
								afecto: prod.afecto,
								subTotal: prod.subTotal,
								serie: i==0 ? prod.serie : '',
								repite: i,
								pideSerie: prod.pideSerie,
							});
							if (i>0) vacios++
						}
						vacios++
					}
				});
				//console.log('vacios',vacios);
				if(this.tipoVenta==-1)
					this.guardar();
				else
					if(vacios>0){
						$('#modalRellenarSeries').modal('show');
					}else{
						alertify.message('<i class="bi bi-info-circle"></i> Generando el comprobante, espere').delay(5);
						this.preGuardar()
					}
			},
			async preGuardar(){
				let seriesContenido = [];
				this.separados.forEach(separa =>{
					if(separa.pideSerie =='1')
						if (separa.serie!='' && separa.serie!='1') seriesContenido.push(separa.serie)
				})
				if(seriesContenido.length>0){
					let datos = new FormData();
					datos.append('barras', JSON.stringify(seriesContenido) )
					fetch('../php/verificarBarrasExistentes.php', {
						method:'POST', body:datos
					})
					.then(serv => serv.json() )
					.then( resp => {
						console.log(resp)
						if(resp.noExiste.length>0)
							alertify.error('<i class="bi bi-info-circle"></i> La barra no esta registrada: '+ resp.noExiste).delay(5);
						else
						this.guardar();
					})
				}else{
					this.guardar();
				}
			},
			guardar(){
				let cabecera = { tipo: this.tipoVenta, serie: this.serie, fecha: moment().format('YYYY-MM-DD') }
				axios.post('../php/insertarBoleta_v4.php', {empresa: this.empresa, cliente: this.clienteActual, cabecera: cabecera, jsonProductos: this.separados, idCaja: '<?= $cajaAbierta['id']?>',
					pagoTotal: this.pagoTotal ? 1 : 2, //2 indica que se paga en partes
					creditos: this.creditos
				})
				.then((response)=>{ console.log( response.data );
					alertify.success('<i class="bi bi-check-circle"></i> Venta guardada').delay(15);
					let jTicket = response.data;
					this.limpiarTodo();

				<?php if($_COOKIE['ticket']=='automatico'){ ?>
					if(app.impresionTicket){
						$.ajax({url: "http://127.0.0.1/"+this.empresa.carpeta+"/printComprobante.php", type: 'POST', data: {
							ticketera: this.empresa.ticketera,
							tipoComprobante: jTicket[0].tipoComprobante,
							rucEmisor: jTicket[0].rucEmisor,
							queEs: jTicket[0].queSoy,
							serie: jTicket[0].serie,
							correlativo: jTicket[0].correlativo,
							tipoCliente: jTicket[0].tipoCliente,
							fecha: jTicket[0].fechaEmision,
							fechaLat: moment(jTicket[0].fechaEmision, 'YYYY-MM-DD').format('DD/MM/YYYY'),
							cliente: jTicket[0].razonSocial,
							docClient: jTicket[0].ruc,
							monedas: jTicket[0].letras,
							descuento: parseFloat(jTicket[0].descuento).toFixed(2),
							costoFinal: parseFloat(jTicket[0].costoFinal).toFixed(2),
							igvFinal: parseFloat(jTicket[0].igvFinal).toFixed(2),
							totalFinal: parseFloat(jTicket[0].totalFinal).toFixed(2),
							productos: jTicket[1],
							direccion:jTicket[0].direccion,
							exonerado: parseFloat(jTicket[0].exonerado).toFixed(2)
							//placa: jTicket[0].placa,
						}}).done(function(resp) {
							console.log(resp)
							//location.reload();
						});

					}
				<?php }else{ ?>
					window.open('../ticket.php?serie='+jTicket[0].serie+'&correlativo='+jTicket[0].correlativo, '_blank');
				<?php } ?>
					
					app.limpiarTodo();
				})
				.catch((error)=>{ console.log( error );});
			},
			editarProducto(item){
				this.idProducto=item;
				$('#queEdito').text(this.canasta[item].nombre);
				$('#txtPrecioProducto').text(parseFloat(this.canasta[item].precio).toFixed(2));
				this.preciosEspeciales = {normal:this.canasta[item].normal, mayor: this.canasta[item].mayor, descuento:  this.canasta[item].descuento };
				$('#modalEditarProducto').modal('show');
				
			},
			retirarProducto(){
				this.canasta.splice(this.idProducto, 1)
			},
			actualizarProducto(){
				//this.canasta[this.idProducto].precio = this.queMonto;
				this.canasta[this.idProducto].subTotal = this.canasta[this.idProducto].precio * this.canasta[this.idProducto].cantidad;
				this.queMonto=0;
			},
			precioDe(monto){
				if (monto<0) monto = 0
				this.canasta[this.idProducto].precio = parseFloat(monto);
				//$('#txtPrecioProducto').text( parseFloat($('#sltPreciosEspeciales').val()).toFixed(2) );
				$('#txtPrecioProducto').text( parseFloat(monto).toFixed(2) );
				this.librePrecio =0;
				this.actualizarProducto();
			},
			limpiarTodo(){
				this.cliBuscar= '';
				this.prodBuscar=''
				this.tipoVenta=0;
				this.venta= 'Ticket interno';
				this.clientes= [{
					idCliente: 1,
					cliRazonSocial: 'Cliente simple',
					cliRuc: '00000000'
				}];
				this.clienteActual={dni: '00000000', razon: 'Cliente simple', idCliente:1, tipo:'persona', direccion:''};
				this.modoCliente=null;
				this.idProducto=-1;
				this.preciosEspeciales=[];
				this.productosNombre=[];
				this.productosSerie=[];
				this.canasta=[];
				this.separados=[];
			},
			siguienteLinea(index){
				$('#tLineasSerie tr').eq(index+1).find('input').focus();
			},
			limpiarLinea(index){
				$('#tLineasSerie input').eq(index).val('').focus();
			},
			agregarFechaCredito(){
				if( this.credito.fecha=='') alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> La fecha ingresada no es válida').delay(15);
				else if( this.credito.monto<= 0) alertify.error('<i class="bi bi-exclamation-diamond-fill"></i> El monto no puede ser 0 o menos').delay(15);
				else{
					this.creditos.push({fecha: this.credito.fecha, monto: this.credito.monto });
				}
			},
			fechaLatam(fechita){
				return moment(fechita, 'YYYY-MM-DD').format('DD/MM/YYYY')
			},
		},
		computed:{
			sumaTotal(){
				let sumaTodo=0;
				this.canasta.forEach(caso => {
					sumaTodo+=parseFloat(caso.subTotal);
				});
				this.credito.monto = sumaTodo;
				return parseFloat(sumaTodo).toFixed(2);
			}
		}
	})
</script>
<style>
	h1,h2,h3,h4,h5{ color:#43484e; }
	#listadoClientes{ color:darkgrey}
	#listadoClientes .razon{ font-size: 0.8rem; color: rgb(58 58 58 / 67%); }
	#listadoClientes .ruc{ font-size: 0.7rem; }
	#listadoClientes .row:hover, .divProductosVarios .row:hover, #divCanastaProductos .row:hover{ background:rgb(232 232 232 / 89%); cursor:pointer; }
	.cuadroMouse:hover{ background:#7e39b152; cursor:pointer; }
	#cuadroCliente .btn{font-size: 0.8rem; border-color: #cacaca;}
	.noselect {
  -webkit-touch-callout: none; /* iOS Safari */
    -webkit-user-select: none; /* Safari */
     -khtml-user-select: none; /* Konqueror HTML */
       -moz-user-select: none; /* Old versions of Firefox */
        -ms-user-select: none; /* Internet Explorer/Edge */
            user-select: none; /* Non-prefixed version, currently
                                  supported by Chrome, Edge, Opera and Firefox */
	}
	#divRegistroCliente input{
		margin: 0.5rem 0;
	}
	.alert-danger { color: #ffffff; background-color: #ff2e41; border-color: #f5c6cb; }
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
	.alertify-notifier .ajs-message.ajs-success{
			background: #0fbd0c;
			color: white;
			border-radius: 2rem;
	}
	.alertify-notifier .ajs-message{
		width: 360px!important;
		right: 390px!important;
	}
	#datosEmpresa p{
    line-height: 1.3;
	}
	.close{color: #de1212;opacity: 0.8;}
</style>
</body>
</html>