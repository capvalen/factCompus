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
	<title>Compras - Facturador electrónico</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="icofont.min.css">
	<link rel="stylesheet" href="css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/anksunamun.css">
	<link rel="shortcut icon" href="images/VirtualCorto.png" type="image/png">
	<link rel="stylesheet" href="css/colorsmaterial.css">


</head>
<body class="pb-5">
<style>
.bg-dark {
	background-color: #7531d4!important;
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
.bootstrap-select .dropdown-toggle .filter-option, .iconPlaceholder{font-family:'Icofont', 'Segoe UI';}
.close{color: #ff0202}
.close:hover, .close:not(:disabled):not(.disabled):hover{color: #fd0000;opacity:1;}
#imgLogo{max-width:250px;}
.bootstrap-select .btn-light{background-color: #ffffff;}
.bootstrap-select .dropdown-toggle .filter-option{    border: 1px solid #ced4da;
    border-radius: .25rem;}
thead tr th{cursor: pointer;}
.dropdown-item .text, .bootstrap-select button{text-transform: capitalize;}
#tblProductosResultados tbody tr:hover{background-color: #a3deff4f;
		color: #007bff;}
/* #tblProductosResultados {color: #0073e6;}
 */
#tblProductosResultados th{border-top-color: transparent!important;     border-bottom: 2px solid #1f8fff;}
#tblProductosResultados td{border-top: 1px solid #d2e9ff;}
.inputValido{border-color: #45c763!important;}
.inputInvalido{border-color: #ff1d1d!important;}
#overlay {
    position: fixed; /* Sit on top of the page content */
    display: none; /* Hidden by default */
    width: 100%; /* Full width (cover the whole page) */
    height: 100%; /* Full height (cover the whole page) */
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.75); /* Black background with opacity */
    z-index: 1051; /* Specify a stack order in case you're using a different order for other elements */
   /* Add a pointer on hover */
}
#overlay .text{position: absolute;
    top: 50%;
    left: 50%;
    font-size: 18px;
    color: white;
    user-select: none;
    transform: translate(-50%,-50%);
}
#hojita{font-size: 36px; display: inline; animation: cargaData 6s ease infinite;}
#pFrase{ display: inline; }
#pFrase span{ font-size: 13px;}
@keyframes cargaData {
    0%  {color: #96f368;}
    25%  {color: #f3dd68;}
    50% {color: #f54239;}
    75% {color: #c173ce;}
    100% {color: #33dbdb;}
}
</style>

<?php include 'menu-wrapper.php'; ?>

<section>
	<div class="container-fluid mt-5 px-5">
		<div class="row">
		<div class="col-md-3">
			<img src="bitmap.jpg?version=1.0.3" class='img-fluid mt-3'>
		</div>
		<div class="col ml-4">
			<h3 class="display-4">Gestión de compras</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		<?php if(!isset($_GET['nuevaCompra'])): ?>
		<div class="d-flex justify-content-between">
		<div>
			<span><i class="icofont-filter"></i> Filtro: </span>
			<select class="selectpicker" data-live-search="false" id="sltFiltroMes" title="&#xed12; Mes">
					<option value="01">Enero</option>
					<option value="02">Febrero</option>
					<option value="03">Marzo</option>
					<option value="04">Abril</option>
					<option value="05">Mayo</option>
					<option value="06">Junio</option>
					<option value="07">Julio</option>
					<option value="08">Agosto</option>
					<option value="09">Septiembre</option>
					<option value="10">Octubre</option>
					<option value="11">Noviembre</option>
					<option value="12">Diciembre</option>
				</select>
				<select class="selectpicker" data-live-search="false" id="sltFiltroAnio" title="&#xed12; Año">
				<?php for($i=2019; $i<= date('Y'); $i++ ){ ?>
					<option value="<?= $i; ?>"><?= $i; ?></option>
				<?php } ?>
				</select>
				<button class="btn btn-outline-primary " id="buscarCompraFecha" ><i class="icofont-search-2"></i> </button>
		</div>
			<a class="btn btn-outline-primary " href="compras.php?nuevaCompra"><i class="icofont-shopping-cart"></i> Generar nueva compra</a>
		</div>
		<?php endif; ?>

		<?php if(isset($_GET['nuevaCompra'])){ ?>
		<h3>Nueva compra</h3>
		<div class="card">
			<div class="card-body">
				<h6 class="card-subtitle mb-2 text-muted">Datos de Proveedor</h6>
		
				<div class="row">
					<div class="col-sm-6 form-group row">
						<label for="sltFiltroDocumento" class="col-lg-3 col-form-label">Documento:</label>
						<div class="col-lg-4"> 
						<select class="selectpicker" data-live-search="false" id="sltFiltroDocumento" title="&#xed12; Documentos">
							<option value="1">Factura</option>
							<option value="3">Boleta de venta</option>
							<option value="7">Nota de crédito</option>
							<option value="12">Ticket de máquina registradora</option>
						</select>
						</div>
					</div>
					<div class="col-sm-6 form-group row">
						<label for="txtCompraSerie" class="col-lg-3 col-form-label">Serie:</label>
						<div class="col-lg-6"><input type="text" class="form-control" id="txtCompraSerie" > </div>
					</div>
					<div class="col-sm-6 form-group row">
						<label for="txtCompraFecha" class="col-lg-3 col-form-label">Fecha de compra:</label>
						<div class="col-lg-6"><input type="text" class="form-control soloNumeros" id="txtCompraFecha" > </div>
					</div>
					<div class="col-sm-6 form-group row">
						<label for="sltFiltroMoneda" class="col-lg-3 col-form-label">Tipo de Moneda:</label>
						<div class="col-lg-4"> 
						<select class="selectpicker" data-live-search="false" id="sltFiltroMoneda" title="&#xed12; Moneda">
							<option value="1">Soles</option>
							<option value="2">Dólares</option>
						</select>
						</div>
					</div>
					<div class="col-sm-6 form-group row d-none">
						<label for="txtCompraValorDolar" class="col-lg-3 col-form-label">Tipo de cambio:</label>
						<div class="col-lg-3"><input type="text" class="form-control esMoneda" id="txtCompraValorDolar" value='0.00' > </div>
					</div>
				</div>

				<div class="row">
					<div class="col-sm-6 form-group row">
						<label for="txtProviderRuc" class="col-lg-3 col-form-label">R.U.C.:</label>
						<div class="col-lg-6"> <input type="text" class="form-control soloNumeros" id="txtProviderRuc" autocomplete='nope'> </div>
					</div>
					<div class="col-sm-6 form-group row">
						<label for="txtProviderRazon" class="col-lg-3 col-form-label">Razon Social:</label>
						<div class="col-lg-9"> <input type="text" class="form-control" id="txtProviderRazon" autocomplete='nope'> </div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6 form-group row">
						<label for="txtProviderDireccion" class="col-lg-3 col-form-label">Dirección:</label>
						<div class="col-lg-6"> <input type="text" class="form-control " id="txtProviderDireccion" autocomplete='nope'> </div>
					</div>
					<div class="col-sm-6 form-group row">
						<label for="txtProviderRuc" class="col-lg-3 col-form-label">Observaciones:</label>
						<div class="col-lg-9"> <input type="text" class="form-control " id="txtProviderObs" autocomplete='nope'> </div>
					</div>					
				</div>

			</div>
		</div>

		<div class="card mt-3">
		<div class="card-body">
			<h6 class="card-subtitle mb-2 text-muted">Detalle de la compra</h6>
			<div class="form-group row">
				<label for="txtFiltroProducto" class="col-lg-2 col-form-label"><i class="icofont-filter"></i> Filtro de producto:</label>
				<div class="col-lg-9"> 
					<div class="input-group">
						<input type="text" class="form-control iconPlaceholder" id="txtFiltroProducto" autocomplete='nope' placeholder="&#xed11; Nombre de producto, código">
						<div class="input-group-append">
							<button class="btn btn-outline-primary" data-toggle="tooltip" title="Realizar búsqueda" id="btnRealizarBusqueda"><i class="icofont-search-1"></i></button>
						</div>
					</div>
				</div>
			</div>
			<div class="">
			<table class="table table-hover">
			<thead>
			<tr>
				<th>N°</th>
				<th>Cant.</th>
				<th>Unidad</th>
				<th>Descripción</th>
				<th>Afecto</th>
				<th>Precio Unit.</th>
				<th>Valor de venta</th>
			</tr>
			</thead>
			<tbody id="tbodyCesta">
				
			</tbody>
			</table>
			<p >Utilice el filtro de búsqueda para agregar sus productos.</p>
			<p >Ingrese sus productos ya con IGV incluido en cada producto.</p>
			</div>
			
		</div>
		</div>
		<div class="card my-3">
		<div class="card-body">
			<div class="ml-5 pl-5">
				<p>Exoneradas: <strong><span class="spanTMonedaR"></span> <span id="spExoneradasFin">0.00</span></strong></p>
				<p>Gravadas: <strong><span class="spanTMonedaR"></span> <span id="spGravadasFin">0.00</span></strong></p>
				<p>Sub Total: <strong><span class="spanTMonedaR"></span> <span id="spSubTotalFin">0.00</span></strong></p>
				<p>I.G.V.: <strong><span class="spanTMonedaR"></span> <span id="spIGVFin">0.00</span></strong></p>
				<p>Total: <strong><span class="spanTMonedaR"></span> <span id="spTotalFin">0.00</span></strong></p>
				
			</div>
		
			<div class="text-center">
				<button class="btn btn-outline-primary " id="btnValidarCesta"><i class="icofont-save"></i> Validar cesta</button>
				<button class="btn btn-outline-primary d-none" id="btnGuardarCompraTodo"><i class="icofont-save"></i> Guardar compra</button>
			</div>
		</div>
		</div>

		<?php } else{ //se muestra cuando no hay ningun parámetro ?>
		<table class="table table-hover mt-3">
			<thead>
				<tr>
					<th data-sort="int"><i class="icofont-expand-alt"></i> N°</th>
					<th data-sort="int"><i class="icofont-expand-alt"></i> Proveedor</th>
					<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo</th>
					<th data-sort="float"><i class="icofont-expand-alt"></i> Serie</th>
					<th data-sort="float"><i class="icofont-expand-alt"></i> Fecha</th>
					<th data-sort="float"><i class="icofont-expand-alt"></i> Exonerado</th>
					<th data-sort="float"><i class="icofont-expand-alt"></i> SubTotal</th>
					<th data-sort="int"><i class="icofont-expand-alt"></i> I.G.V.</th>
					<th data-sort="string"><i class="icofont-expand-alt"></i> Total</th>
					
				</tr>
			</thead>
			<tbody>
			<?php 
			if(!isset($_GET['fecha'])){$fecha = "date_format(now(), '%Y-%m')";}else{ $fecha = "'".$_GET['fecha']."'";}
				$i=1;
				$sqlCompr="SELECT `idCompra`, cli.cliRazonSocial, co.compDescripcion, date_format(`compFecha`, '%d/%m/%Y') as compFecha, `compSerie`, `compFechaRegistro`, c.`idMoneda`, `compCambioMoneda`, `idProveedor`, `comObs`, `idUsuario`, `compActivo`,
				concat(m.monSimbolo, ' ', round(`compExonerado`,2)) as compExonerado, concat(m.monSimbolo, ' ',round(`compSubTotal`,2)) as compSubTotal, concat(m.monSimbolo, ' ', round(`compIgv`,2)) as compIgv, concat(m.monSimbolo, ' ', round(`compTotal`,2)) as compTotal
				FROM `compras` c inner join moneda m on m.idMoneda = c.`idMoneda`
				inner join comprobante co on co.idComprobante = c.idComprobante
				inner join clientes cli on cli.idCliente = c.idProveedor
				where date_format(compFecha, '%Y-%m') = $fecha
				order by compFechaRegistro; ";
				$resultadoCompr=$cadena->query($sqlCompr);
				while($rowCompr=$resultadoCompr->fetch_assoc()){ ?>
					<tr>
						<td><?= $i;?></td>
						<td class="text-capitalize"><?= $rowCompr['cliRazonSocial']; ?></td>
						<td><?= $rowCompr['compDescripcion']; ?></td>
						<td><?= $rowCompr['compSerie']; ?></td>
						<td><?= $rowCompr['compFecha']; ?></td>
						<td><?= $rowCompr['compExonerado']; ?></td>
						<td><?= $rowCompr['compSubTotal']; ?></td>
						<td><?= $rowCompr['compIgv']; ?></td>
						<td><?= $rowCompr['compTotal']; ?></td>
					</tr>
				<?php $i++; }
			?>
			</tbody>
		</table>
		<?php } //fin de else parametros ?>
	</div>
</section>


<!-- Modal para: mostrar productos resultado-->
<div class='modal fade ' id="modalProductosEncontrados" tabindex='-1' role='dialog' aria-hidden='true'>
	<div class='modal-dialog modal-lg' >
	<div class='modal-content '>
		<div class='modal-body'>
		<button type='button' class='close' data-dismiss='modal' aria-label='Close' ><span aria-hidden='true'>&times;</span></button>
		<h4 class='modal-title blue-text text-accent-2'>Productos</h4>
			<div class="table-responsive" id="divResultadoProd"></div>
		</div>
		
		</div>
	</div>
</div>



<div id="overlay">
	<div class="text"><span id="hojita"><i class="icofont icofont-leaf"></i></span> <p id="pFrase"> Solicitando los datos a Sunat... <br> <span>«Pregúntate si lo que estás haciendo hoy <br> te acerca al lugar en el que quieres estar mañana» <br> Walt Disney</span></p></div>
</div>

<?php include "php/modal.php"; ?>

<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/impotem.js?version=1.0.15"></script>
<script src="js/moment.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/stupidtable.js"></script>

<script>
$(document).ready(function(){
	$('.selectpicker').selectpicker('render');
	$('.selectpicker').selectpicker('val', -1);
	$('#txtCompraFecha').val(moment().format('YYYY-MM-DD'));
	//$('table').stupidtable();
	/* $.ajax({url: 'php/getPreciosProductos.php', type: 'POST' }).done(function(resp) {
		//console.log(resp)
		$.precios = JSON.parse(resp);
		console.log( $.precios );
	}); */
	//$("table").stupidtable();
	$('[data-toggle="tooltip"]').tooltip();
	$.jsonProductos = [];

});
$('#btnAgregarProducto').click(function() {
	$('.modalProductosEncontrados').modal('show');
});
$('#txtFiltroProducto').keypress(function (e) { 
	if(e.keyCode == 13){ 
		$('#btnRealizarBusqueda').click();
	}
});
$('#sltFiltroMoneda').on('changed.bs.select', function (e) {
	if( $('#sltFiltroMoneda').selectpicker('val')!=null ){
		switch ($('#sltFiltroMoneda').selectpicker('val')) {
			case "1": $('.spanTMonedaR').text('S/'); $('#txtCompraValorDolar').parent().parent().addClass('d-none'); break;
			case "2": $('.spanTMonedaR').text('$'); $('#txtCompraValorDolar').parent().parent().removeClass('d-none'); $('#txtCompraValorDolar').focus(); break;
			default: break;
		}
	}
});
$('#tbodyCesta').on('changed.bs.select', '.sltFiltroAfectoCesta .selectpicker', function (e) {
	var padre = $(this).parent().parent().parent();
	if( $(this).selectpicker('val')==3 || $(this).selectpicker('val')==4 ){
		padre.find('.txtPrecUnitCesta').attr('readonly', true).val('0.00');
		padre.find('.txtValorUnitCesta').attr('readonly', true).val('0.00');
	}else{
		padre.find('.txtPrecUnitCesta').attr('readonly', false);
		padre.find('.txtValorUnitCesta').attr('readonly', false);
	}
	$('#btnGuardarCompraTodo').addClass('d-none');
	calcularFinales();
});

$('#btnRealizarBusqueda').click(function() {
	if( $('#txtFiltroProducto').val().length>=3 ){
	$.ajax({url: 'php/filtroProductos.php', type: 'POST', data: { texto: $('#txtFiltroProducto').val() }}).done(function(resp) {
		//console.log(resp)
		$('#divResultadoProd').html(resp);
		$('[data-toggle="tooltip"]').tooltip();
	});
	$('#modalProductosEncontrados').modal('show');
	}
});
$('#tbodyCesta').on('click', '.btnBorrarFila', function (e) {
	$(this).parent().parent().remove();
});
$('#divResultadoProd').on('click', '.btnAgregarProdCesta', function (e) {
	var padre = $(this).parent().parent();

	$('#tbodyCesta').append(`<tr class="cardHijoProducto" data-id="${padre.attr('data-id')}">
					<td class="px-0"><button class="btn btn-outline-danger border-0 btn-sm p-1 float-left btnBorrarFila"><i class="icofont-close"></i></button> ${$('#tbodyCesta tr').length+1}.</td>
					<td><input type="number" class="form-control txtCantidadCesta text-center" value="1"></td>
					<td><select class="selectpicker" data-live-search="false" id="sltFiltroUnidadCesta" title="&#xed12; Unidades">
							<?php include 'php/listarUnidadesOPT.php';?>
						</select></td>
					<td class="text-capitalize">${padre.find('.tdNombreProd').text()}</td>
					<td><select class="selectpicker sltFiltroAfectoCesta" data-live-search="false" id="sltFiltroAfectoCesta" title="&#xed12; Afecto">
							<?php include 'php/optAfectos.php';?>
						</select></td>
					<td><input type="number" class="form-control txtPrecUnitCesta esMoneda text-center" value="0.00"></td>
					<td><input type="number" class="form-control txtValorUnitCesta esMoneda text-center" value="0.00"></td>
				</tr>`);
		$('.selectpicker').selectpicker('render');
		
		$(`tr[data-id="${padre.attr('data-id')}"]`).find('#sltFiltroUnidadCesta').selectpicker('val', padre.find('.tdUnidad').attr('data-und')).selectpicker('refresh');
		$(`tr[data-id="${padre.attr('data-id')}"]`).find('#sltFiltroAfectoCesta').selectpicker('val', padre.find('.tdGravado').attr('data-gravado')).selectpicker('refresh');
		$('#modalProductosEncontrados').modal('hide');
});
$('#tbodyCesta').on('keyup', '.txtCantidadCesta', function (e) { 
	var padre = $(this).parent().parent();
	var precUnit = padre.find('.txtPrecUnitCesta').val();
	var cant =  $(this).val();
	var gravado = padre.find('#sltFiltroAfectoCesta').selectpicker('val');
	
	if(gravado ==1 || gravado ==2 ){
		padre.find('.txtValorUnitCesta').val( parseFloat(precUnit*cant).toFixed(2) );
	}else{ //bonifaciones y gratis
		padre.find('.txtPrecUnitCesta').val( parseFloat(0).toFixed(2) );
		padre.find('.txtValorUnitCesta').val( parseFloat(0).toFixed(2) );
	}
	$('#btnGuardarCompraTodo').addClass('d-none');
	calcularFinales();
});
$('#tbodyCesta').on('keyup', '.txtPrecUnitCesta', function (e) { 
	var padre = $(this).parent().parent();
	var precUnit = $(this).val();
	var cant = padre.find('.txtCantidadCesta').val();
	var gravado = padre.find('#sltFiltroAfectoCesta').selectpicker('val');
	
	padre.find('.txtValorUnitCesta').val( parseFloat(precUnit*cant).toFixed(2) );
	
	$('#btnGuardarCompraTodo').addClass('d-none');
	calcularFinales();
});
$('#tbodyCesta').on('keyup', '.txtValorUnitCesta', function (e) { 
	var padre = $(this).parent().parent();
	var precValor = $(this).val();
	var cant = padre.find('.txtCantidadCesta').val();
	var gravado = padre.find('#sltFiltroAfectoCesta').selectpicker('val');
	
	padre.find('.txtPrecUnitCesta').val( parseFloat(precValor/cant).toFixed(2) );
	
	$('#btnGuardarCompraTodo').addClass('d-none');
	calcularFinales();
});
function calcularFinales(){
	var sumSubs=0, sumExonerado=0, sumGravado=0, sumIgv=0, sumTotal=0 ;
	$.each( $('.cardHijoProducto') , function(i, objeto){

		if( $(objeto).find('#sltFiltroAfectoCesta').selectpicker('val')==1 ){
			sumGravado+= parseFloat( $(objeto).find('.txtValorUnitCesta').val() );
		}else if( $(objeto).find('#sltFiltroAfectoCesta').selectpicker('val')==2 ){
			sumExonerado+= parseFloat( $(objeto).find('.txtValorUnitCesta').val() );
		}

	});
	sumTotal = sumGravado + sumExonerado;
	sumSubs = sumGravado/1.18;
	sumIgv = sumGravado-sumSubs;
	$('#spExoneradasFin').text(sumExonerado.toFixed(2));
	$('#spGravadasFin').text(sumGravado.toFixed(2));
	$('#spSubTotalFin').text(sumSubs.toFixed(2));
	$('#spIGVFin').text(sumIgv.toFixed(2));
	$('#spTotalFin').text(sumTotal.toFixed(2));
}

$('#btnGuardarCompraTodo').click(function() {
	pantallaOver(true);
	if( $('#sltFiltroDocumento').selectpicker('val')==null ){
		$('#h5DetalleFaltan').text('Debe seleccionar un tipo de documento');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#txtCompraFecha').val()=='' ){
		$('#h5DetalleFaltan').text('La fecha de compra no puede estar vacío');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#txtCompraSerie').val()=='' ){
		$('#h5DetalleFaltan').text('La ingresar la serie del comprobante de compra');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#sltFiltroMoneda').selectpicker('val')==null ){
		$('#h5DetalleFaltan').text('Debe seleccionar un tipo de moneda');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#sltFiltroMoneda').selectpicker('val')=='2' && $('#txtCompraValorDolar').val()=='0.00' ){
		$('#h5DetalleFaltan').text('El tipo de moneda debe ser mayor que cero');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#txtProviderRuc').val()=='' ){
		$('#h5DetalleFaltan').text('Se olvidó ingresar el R.U.C. del proveedor');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#txtProviderRazon').val()=='' ){
		$('#h5DetalleFaltan').text('Se olvidó ingresar la razón social del proveedor');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#tbodyCesta tr').length==0 ){
		$('#h5DetalleFaltan').text('No se puede guardar una compra con una lista vacía');
		$('#modalFaltaDatos').modal('show');
	}else if( $('#tbodyCesta tr').length == $('#tbodyCesta .tieneDefectos').length ){
		$('#h5DetalleFaltan').text('Su lista tiene defectos, como mínimo debe haber un producto bien registrado');
		$('#modalFaltaDatos').modal('show');
	}else{

		$.ajax({url: 'php/insertarCompra.php', type: 'POST', data: { ruc: $('#txtProviderRuc').val(), razonSocial: $('#txtProviderRazon').val(), domicilio: $('#txtProviderDireccion').val(), 
			idComprobante: $('#sltFiltroDocumento').selectpicker('val'), compFecha: $('#txtCompraFecha').val(), serie: $('#txtCompraSerie').val(), idMoneda: $('#sltFiltroMoneda').selectpicker('val'), 
			monedaCambio: $('#txtCompraValorDolar').val(), sumExonerado: $('#spExoneradasFin').text(), sumSubtotal: $('#spSubTotalFin').text(), sumIgv: $('#spIGVFin').text(), sumTotal: $('#spTotalFin').text(), compObs: $('#txtProviderObs').val(), jsonProductos: $.jsonProductos
 }}).done(function(resp) {
			if(resp=='ok'){
				$('#h5Detalle').text('su compra se guardó correctamente');
				$('#modalGuardadoExitoso').modal('show');
				$('#myModal').on('hidden.bs.modal', function () { 
					window.location.href = 'compras.php';
				});
			}
		});
	}
	pantallaOver(false);

});
$('#btnValidarCesta').click(function() {
	var tieneDefectos = false;
	$.jsonProductos=[];
		$.each( $('.cardHijoProducto'), function (i, elem) {
			$(elem).removeClass('tieneDefectos');
			$(elem).find('input').removeClass('inputInvalido').removeClass('inputValido');
			$(elem).find('.filter-option').removeClass('inputInvalido').removeClass('inputValido');
			if($(elem).find('.txtCantidadCesta').val()==0 || $(elem).find('.txtCantidadCesta').val()=='' ){
				$(elem).find('.txtCantidadCesta').addClass('inputInvalido'); 	$(elem).addClass('tieneDefectos'); tieneDefectos =true;
			}else{
				$(elem).find('.txtCantidadCesta').addClass('inputValido');
			}
			if($(elem).find('#sltFiltroUnidadCesta').selectpicker('val')==null ){
				$(elem).find('#sltFiltroUnidadCesta ').parent().find('.filter-option').addClass('inputInvalido'); $(elem).addClass('tieneDefectos'); tieneDefectos =true;
			}else{
				$(elem).find('#sltFiltroUnidadCesta ').parent().find('.filter-option').addClass('inputValido');
			}
			if($(elem).find('#sltFiltroAfectoCesta').selectpicker('val')==null ){
				$(elem).find('#sltFiltroAfectoCesta ').parent().find('.filter-option').addClass('inputInvalido'); $(elem).addClass('tieneDefectos'); tieneDefectos =true;
			}else{
				$(elem).find('#sltFiltroAfectoCesta ').parent().find('.filter-option').addClass('inputValido');
			}
			if( parseFloat($(elem).find('.txtPrecUnitCesta').val())==0 && $.inArray( $(elem).find('#sltFiltroAfectoCesta').selectpicker('val') , ["3","4"])==-1  ){
				$(elem).find('.txtPrecUnitCesta').addClass('inputInvalido'); $(elem).addClass('tieneDefectos'); tieneDefectos =true;
			}else{
				$(elem).find('.txtPrecUnitCesta').addClass('inputValido');
			}
			if( parseFloat($(elem).find('.txtValorUnitCesta').val())==0 && $.inArray( $(elem).find('#sltFiltroAfectoCesta').selectpicker('val') , ["3","4"])==-1 ){
				$(elem).find('.txtValorUnitCesta').addClass('inputInvalido'); $(elem).addClass('tieneDefectos'); tieneDefectos =true;
			}else{
				$(elem).find('.txtValorUnitCesta').addClass('inputValido');
				
				$.jsonProductos.push({ idProd: $(elem).attr('data-id') , cantidad: $(elem).find('.txtCantidadCesta').val(), precUnit: $(elem).find('.txtPrecUnitCesta').val(), afecto: $(elem).find('#sltFiltroAfectoCesta').selectpicker('val'), unidad: $(elem).find('#sltFiltroUnidadCesta').selectpicker('val') });
			}
		});
		if(tieneDefectos==true){
			$('#h5DetalleFaltan').text('Tiene registros que no tienen los datos completos, si desea guardar, éstos no se considerarán. Por favor corríjalos. ');
			$('#modalFaltaDatos').modal('show');
		}
		//console.log($.jsonProductos)
		$('#btnGuardarCompraTodo').removeClass('d-none');


});
$('#buscarCompraFecha').click(()=>{
	var mes = $('#sltFiltroMes').selectpicker('val');
	var anio = $('#sltFiltroAnio').selectpicker('val');
	window.location.href = 'compras.php?fecha='+anio+'-'+mes;
})

</script>
<!-- BEGIN JIVOSITE CODE {literal} -->
<!-- <script type='text/javascript'>
(function(){ var widget_id = 'ucFX66lIdV';var d=document;var w=window;function l(){
  var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
  s.src = '//code.jivosite.com/script/widget/'+widget_id
    ; var ss = document.getElementsByTagName('script')[0]; ss.parentNode.insertBefore(s, ss);}
  if(d.readyState=='complete'){l();}else{if(w.attachEvent){w.attachEvent('onload',l);}
  else{w.addEventListener('load',l,false);}}})();
</script> -->
<!-- {/literal} END JIVOSITE CODE -->
</body>
</html>