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
						<button class="btn btn-outline-primary " id="btnAgregarProducto"><i class="icofont-ui-rate-add"></i> Agregar nuevo producto</button>
					</div>
					<div class="col-12 col-md-3 d-flex justify-content-end my-2">
						<button class="btn btn-outline-success " id="btnExportarProductos"><i class="icofont-file-excel"></i> Exportar productos</button>
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
							<th>Celular.</th>
							<th>Contacto</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody id="tbodyRespuestaProductos">
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>



<!-- Modal para un nuevo producto -->
<div class="modal fade" id="modalNuevoProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" >Nuevo Producto <span class="text-capitalize" ></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="txtDescripcionNuevo" class="col-sm-4 col-form-label"><span class="text-danger">*</span> Descripción:</label>
					<div class="col-sm-8"> <input type="text" class="form-control text-capitalize" id="txtDescripcionNuevo" autocomplete="off" > </div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">Código Sunat:</label>
					<div class="col-sm-8"> <input type="text" class="form-control text-capitalize" id="txtCodeSunat" autocomplete="off" > </div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">¿Maneja series?:</label>
					<div class="col-sm-8"> 
						<select class="form-control" id="sltSeries">
							<option value="2" selected>No</option>
							<option value="1">Si</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">Marca:</label>
					<div class="col-sm-8"> 
						<select class="form-control" id="sltMarcas">
							<?php include('php/optionMarcas.php'); ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">Linea:</label>
					<div class="col-sm-8"> 
						<select class="form-control" id="sltLineas">
							<?php include('php/optionLineas.php'); ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">Familia:</label>
					<div class="col-sm-8"> 
						<select class="form-control" id="sltFamilias" onchange="cambiarFamilia()">
							<?php include('php/optionFamilias.php'); ?>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="txtCodeSunat" class="col-sm-4 col-form-label">Sub-Familia:</label>
					<div class="col-sm-8"> 
						<select class="form-control" id="sltSubFamilias">
						</select>
					</div>
				</div>

				<div class="form-group row <?= ( $_COOKIE['precioPublico']==1 ? '': 'd-none' )?>">
					<label for="txtPrecioNuevo" class="col-sm-4 col-form-label"><span class="text-danger">*</span> Precio al Público:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioNuevo" value="0.00"> </div>
				</div>
				<div class="form-group row <?= ( $_COOKIE['precioMayorista']==1 ? '': 'd-none' )?>">
					<label for="txtPrecioMayorNuevo" class="col-sm-4 col-form-label">Precio al Mayor:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioMayorNuevo" value="0.00"> </div>
				</div>
				<div class="form-group row <?= ( $_COOKIE['precioDescuento']==1 ? '': 'd-none' )?>">
					<label for="txtPrecioDescuentoNuevo" class="col-sm-4 col-form-label">Precio Mínimo:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioDescuentoNuevo" value="0.00"> </div>
				</div>
				<div class="form-group row">
					<label for="sltFiltroGravadoNuevo" class="col-sm-4 col-form-label"><span class="text-danger">*</span> Impuesto:</label>
					<div class="col-sm-6">
						<select class="selectpicker" data-live-search="false" id="sltFiltroGravadoNuevo" title="&#xed12; Imposición">
							<option value="1">Afecto</option>
							<option value="2">Exonerado</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="sltFiltroUnidadesNuevo" class="col-sm-4 col-form-label"><span class="text-danger">*</span> Und. Medida:</label>
					<div class="col-sm-6">
						<select class="selectpicker" data-live-search="false" id="sltFiltroUnidadesNuevo" title="&#xed12; Unidades">
							<?php include "php/listarUnidadesOPT.php"; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex flex-column">
				<label class="text-danger	d-none" for=""></label>
				<button type="button" class="btn btn-outline-success" id="btnNuevoProduct"><i class="icofont-ui-add"></i> Crear nuevo producto</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal para editar producto -->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Editar Producto: <span class="text-capitalize" id="spanNomProducto"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="txtPrecioPublico" class="col-sm-4 col-form-label">Descripción:</label>
					<div class="col-sm-8"> <input type="text" class="form-control text-capitalize" id="txtDescripcionPub" > </div>
				</div>
				<div class="form-group row">
					<label for="txtPrecioPublico" class="col-sm-4 col-form-label">Precio al Público:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioPublico" val="0.00"> </div>
				</div>
				<div class="form-group row">
					<label for="txtPrecioMayor" class="col-sm-4 col-form-label">Precio al Mayor:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioMayor" val="0.00"> </div>
				</div>
				<div class="form-group row">
					<label for="txtPrecioDescuento" class="col-sm-4 col-form-label">Precio Mínimo:</label>
					<div class="col-sm-6"> <input type="number" class="form-control esMoneda text-center" id="txtPrecioDescuento" val="0.00"> </div>
				</div>
				<div class="form-group row">
					<label for="sltFiltroGravado" class="col-sm-4 col-form-label">Impuesto:</label>
					<div class="col-sm-6">
						<select class="selectpicker" data-live-search="false" id="sltFiltroGravado" title="&#xed12; Imposición">
							<option value="1">Afecto</option>
							<option value="2">Exonerado</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="sltFiltroUnidades" class="col-sm-4 col-form-label">Und. Medida:</label>
					<div class="col-sm-6">
						<select class="selectpicker" data-live-search="false" id="sltFiltroUnidades" title="&#xed12; Unidades">
							<?php include "php/listarUnidadesOPT.php"; ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success" id="btnUpdateProduct"><i class="icofont-refresh"></i> Actualizar datos</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal para modificar stock-->
<div class="modal fade" id="modalModificarStock" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Modificar stock: <span class="text-capitalize" id="spanNomProducto"></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label for="sltFiltroGravado" class="col-sm-4 col-form-label">Proceso:</label>
					<div class="col-sm-6">
						<select class="selectpicker" data-live-search="false" id="sltTipoModStock" title="&#xed12; ¿Qué proceso es?">
							<option value="1">Aumento directo</option>
							<option value="2">Disminución directa</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="txtPrecioPublico" class="col-sm-4 col-form-label">Cantidad:</label>
					<div class="col-sm-4"> <input type="number" class="form-control " min="0" id="txtCantidadStock" > </div>
				</div>
				<div class="form-group row">
					<label for="txtPrecioPublico" class="col-sm-4 col-form-label">Observaciones:</label>
					<div class="col-sm-8"> <input type="text" class="form-control text-capitalize" id="txtObservacionStock" > </div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-success" id="btnUpdateStock"><i class="icofont-refresh"></i> Actualizar stock</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal para: -->
<div class='modal fade' id='modalBarritas' tabindex='-1'>
	<div class='modal-dialog modal-dialog-centered'>
		<div class='modal-content'>
			<div class='modal-body'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
				<h5 class='modal-title'>Código Barras</h5>
				<div class="card mt-2">
					<div class="card-body p-3">
						<div class="input-group mb-3">
							<input type="text" class="form-control" placeholder="Escanee el código" id="txtCodigoBarrita" autocomplete="off">
							<div class="input-group-append">
								<button class="btn btn-outline-primary" id="btnAddBarrita" type="button" id="button-addon2"><i class="icofont-plus"></i></button>
							</div>
						</div>
					</div>
				</div>
				<p class="mt-3"><strong>Códigos de barras asociados:</strong></p>
				<table class="table table-hover table-sm">
					<thead>
						<tr>
							<th>N°</th>
							<th>Código</th>
							<th>@</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
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
				message: 'Hello Vue!'
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