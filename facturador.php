<?php
include 'php/conexion.php';
include "generales.php";

if( !isset($_COOKIE['ckidUsuario']) ){ header("Location: index.html");
	die(); }
include "generales.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Facturador electrónico - Desarrollado por: Infocat Soluciones</title>
	<?php include 'headers.php'; ?>

</head>
<body>

<style>
	.modal-xl { max-width: 95vw!important;}
	.table-responsive{min-height: 70vh;}
</style>
<?php include 'menu-wrapper.php'; ?>

<div class="container d-none">
	<section>
		<h1 class="text-center my-4">Sistema de emisión de comprobantes 1.0</h1>
	</section>
	<section id="cuerpoBotones">
		<div class="container">
			<div class="row">
			<div class="col-sm-12 mx-5 px-5">
				<button class="btn btn-outline-success btn-block btn-lg my-4">Emitir Comprobante</button>
				<button class="btn btn-outline-warning btn-block btn-lg my-4">Consultar comprobante</button>
				<button class="btn btn-outline-dark btn-block btn-lg my-4 d-none">Modificar serie de comprobante</button>
			</div>
			</div>
		</div>
	</section>
</div>
<section>
	<div class="container-fluid mt-5 px-md-5">
		<div class="row">
			<div class="col-md-3 text-center">
				<img src="<?= $_COOKIE['logo'];?>" class='img-fluid mx-auto' width="150px;">
			</div>
			<div class="col ml-4">
				<h3 class="display-4">Facturación Electrónica</h3>
				<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
				<div class="row d-flex justify-content-between">
					<div class="col-sm-3"><input type="date" class="form-control text-center" id="fechaFiltro"></div>
					<div class="col-sm-2"><button class="btn btn-outline-primary" id="btnRefresh"><i class="icofont-refresh"></i> Actualizar</button></div>
				</div>
			</div></div>
			<hr>
			<div class="container mx-auto mt-4 row" style="color: #7030a0">
				<div class="col"><strong>N° Comprobantes: <span id="strCantdad"></span></strong></div>
				<div class="col"><strong>Total S/ <span id="strTotal"></span></strong></div>
			</div>
		
		<div class="table-responsive">
			<table class="table table-hover mt-3" id="tablaPrincipal">
				<thead>
					<tr>
						<th data-sort="int"><i class="icofont-expand-alt"></i> N°</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Código</th>
						<th data-sort="int"><i class="icofont-expand-alt"></i> Hora</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Cliente</th>
						<th data-sort="float"><i class="icofont-expand-alt"></i> I.G.V.</th>
						<!-- <th data-sort="float"><i class="icofont-expand-alt"></i> Monto</th> -->
						<th data-sort="float"><i class="icofont-expand-alt"></i> Total</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> T.Pago</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Debe</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Estado</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
					<!-- <tr>
						<td>1</td>
						<td>20550-88</td>
						<td>Factura</td>
						<td>3:05 p.m.</td>
						<td>Cliente sin DNI</td>
						<td>55.00</td>
						<td>Emitido</td>
						<td>
							<button class="btn btn-outline-primary btn-sm border border-light" data-toggle="tooltip" data-placement="top" title="Generar comprobante"><i class="icofont-flag"></i></button>
							<button class="btn btn-outline-success btn-sm border border-light" data-toggle="tooltip" data-placement="top" title="Imprimir ticket"><i class="icofont-paper"></i></button>
							<button class="btn btn-outline-success btn-sm border border-light" data-toggle="tooltip" data-placement="top" title="Imprimir A4"><i class="icofont-print"></i></button>
						</td>
					</tr> -->
				</tbody>
			</table>
		</div>
	</div>
</section>

<footer class="bg-dark p-3 text-white text-center mt-4">
	<p class="mb-0"><i class="icofont-book-mark"></i>  <small><?php include 'php/version.php';?></small></p>
	<p><i class="icofont-book-mark"></i> Facturador SFS 1.5</p>
</footer>

<div class="modal fade" id="modalArchivoBien" tabindex="-1" role="dialog" data-backdrop="static" >
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Guardado exitoso</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Comprobante generado correctamente. ¿Qué deseas hacer a continuación?</p>
				<button class="btn btn-outline-primary" id="btnPrintTicketera"><i class="icofont-paper"></i> Imprimir en ticketera</button>
				<button class="btn btn-outline-success d-none d-sm-block" id="btnPrintA4"><i class="icofont-print"></i> Generar A4</button>
				<button class="btn btn-outline-success d-block d-sm-none" id="btnPrintPDF"><i class="icofont-print"></i> Generar PDF</button>

			</div>
			<div class="modal-footer d-none">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">ok</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal para Emitir Boleta -->
<div class="modal fade" id="modalEmisionBoleta" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<i class="icofont-close-circled"></i>
				</button>
				<h4 class="py-3 hTitulo"><i class="icofont-paper"></i> Generar: <span id="queGenero"></span> Electrónica</h4>
				<div class="form-inline">
				
					<div class="form-check mb-3 ml-5 d-none">
						<label for="">Placa de vehículo:</label>
						<input type="text" class='form-control text-uppercase ml-3' placeholder="N° Placa &#xee1e;" id="txtPlacaBoleta">
					</div>
					<div class="form-inline">
						<select class="selectpicker" data-live-search="true" id="sltFiltroClientes" title="&#xed12; Filtro de clientes">
							<?php include "php/listarTodosClientes.php";?>
						</select>
					</div>
					<div class="dropdown mb-3">
						<select class="form-control ml-2" id="sltContado">
							<option value="1">Contado</option>
							<option value="2">Crédito</option>
						</select>
					</div>
					<div class="d-none form-inline mb-3" id="divAdelanto">
						<label class="mx-2">Adelanto:</label>
						<input type="number" class="form-control mb-3" value="0" id="txtAdelanto">
					</div>
				
				<div class="form-inline  ml-auto">
					<label class="pr-3  text-muted mt-2" for=""><strong>Fecha:</strong></label>
					<input type="date" class="form-control  mr-2" id="txtFechaComprobante">
					<label class="pr-3 text-muted mt-2" for=""><strong>Serie:</strong></label>
					<div class="dropdown my-3">
					<?php 
						$sqlSerieBoleta="SELECT * FROM `fact_series`";
						$resultadoSerieBoleta=$cadena->query($sqlSerieBoleta);
						$rowSerieBoleta=$resultadoSerieBoleta->fetch_assoc();
					?>
					<select class="form-control" id="sltSeriesBoleta">
						<option value="series" selected>Series</option>
						<option value="<?= $rowSerieBoleta['serieBoleta']; ?>" id="optBoleta"><?= $rowSerieBoleta['serieBoleta']; ?></option>
						<option value="<?= $rowSerieBoleta['serieFactura']; ?>" id="optFactura"><?= $rowSerieBoleta['serieFactura']; ?></option>
						<option value="<?= $rowSerieBoleta['serieOpcional']; ?>" id="optOpcional" disabled><?= $rowSerieBoleta['serieOpcional']; ?></option>
						<option value="<?= $rowSerieBoleta['serieOpcional2']; ?>" id="optOpcional2" disabled><?= $rowSerieBoleta['serieOpcional2']; ?></option>
					</select>
					</div>

				</div>
				</div>
			
				
				<div id="divDatosCliente" class="card mb-3">
					<div class="card-body">
						<p class="text-muted lead"><strong>Datos del cliente:</strong></p>
						<div class="row mb-3">
							<div class="col-12 col-md-2">
								<select class="custom-select" id="sltEstadoDni">
									<option value="0">Sin documento</option>
									<option value="1">D.N.I.</option>
									<option value="6">R.U.C.</option>
									<option value="7">Pasaporte</option>
								</select>
								<!-- <div class="form-check mb-3">
									<input class="form-check-input" type="checkbox" value="" id="chkEstadoDni" >
									<label class="form-check-label" id="labelEstadoDni" for="chkEstadoDni" >Cliente anónimo</label>
								</div> -->
							</div>
							<div class="col-12 col-md-3">
								<input type="text"  class="form-control soloNumeros" id="txtDniBoleta" value="" placeholder='Dni' autocomplete="off">
							</div>
							<div class="col-12 col-md-7">
								<input type="text"  class="form-control text-capitalize" id="txtRazonBoleta" value="" placeholder='Razón social o Apellidos y Nombres' autocomplete="off">
							</div>
						</div>
						<div class="row">
							<div class="col">
								<input type="text"  class="form-control text-capitalize" id="txtDireccionBoleta" value="" placeholder='Dirección' autocomplete="off">
							</div>
						</div>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<p class="text-muted lead mb-0"><strong>Detalles</strong></p>
						<div class="row text-muted">
							<div class="col-6 col-md-4"><strong>Concepto</strong></div>
							<div class="col-6 col-md-2"><strong>Cant.</strong></div>
							<?php if($_COOKIE['facCambiarUnidad']==1): ?>
							<div class="col-6 col-md-1"><strong>Und</strong></div>
							<?php endif;
							if($_COOKIE['facCambiarGravado']==1): ?>
							<div class="col-6 col-md-2"><strong>Gravado.</strong></div>
							<?php endif; ?>
							<div class="col-6 col-md-2"><strong>Precio</strong></div>
							<?php if($_COOKIE['verCantidad']==1):?>
							<div class="col-6 col-md-2 "><strong>Precio Unit.</strong></div>
							<?php endif;?>
							<div class="col-6 col-md-2 d-none"><strong>Sub-Total</strong></div>
						</div>
						<div id="divProductos">
							<?php include "php/filaNueva.php";?>
						</div>
						<button class="btn btn-outline-success btn-sm mt-2" id="btnAgregarProducto"><i class="icofont-ui-add"></i> Agregar más produtos</button>
					</div>
				</div>
				<div class="card my-3">
					<div class="card-body">
						<div class="container row row-cols-2 row-cols-md-4 text-center" id="divCalculosFinales"> <!-- align-items-end flex-column -->
							<span><small>Exonerado:</small> <span>S/ <span id="spExoneradoBoleta">0.00</span></span></span>
							<span><small>Sub-Total:</small> <span>S/ <span id="spSubTotBoleta">0.00</span></span></span>
							<span><small>IGV:</small> <span>S/ <span id="spIgvBoleta">0.00</span></span></span>
							<span><small>Total:</small> <strong>S/ <span id="spTotalBoleta">0.00</span></strong></span>
						</div>
					</div>
				</div>
				
				<div class="container-fluid row mt-3 d-flex justify-content-end">
					<span id="spanErrorFinal" for="" class=" d-none"> <span class="lblError"></span></span>
				</div>
				
				
			
			<div class="container-fluid row d-flex justify-content-end mb-3">
				<div class="row">
					<label for="" class="col-sm-5 col-form-label text-right"><i class="icofont-diamond"></i> <small>Paga con:</small></label>
					<input type="number" class="form-control col-sm-3" id="txtPagaCuanto">
					<label for="" class="col-sm-4 col-form-label d-none"><small>Vuelto: S/<span id="spanVuelto"></span></small></label>
				</div>
				<div class="col mt-2 mt-md-0">
					<button type="button" class="btn btn-outline-success float-right d-none" id="btnEmitirFacturav2" ><i class="icofont-paper"></i> Emitir Factura</button>
					<button type="button" class="btn btn-outline-primary float-right" id="btnEmitirBoletav2" ><i class="icofont-paper"></i> Emitir Boleta</button>
				</div>
				<div class="container-fluid row mt-3 d-flex justify-content-end d-none" id="">
					<span id="spanLimiteSobrepasado" style="background: #e6330a!important;"><span class=""><i class="icofont-chat"></i> Se sobrepasó el límite máximo en comprobantes.</span></span>
				</div>
			</div>
		</div>
	</div>
</div>
</div>


<!-- Modal para ingresar el N° ticket -->
<div class="modal fade" id="modalIngresoTicket" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h5>Generar comprobante</h5>
				<p>N° de Ticket:</p>
				<input type="text" class="form-control text-uppercase text-center my-3 d-none" id="txtNCodNegocio" placeholder='Código de negocio' value="<?= $_COOKIE['ckNegocio'];?>" readonly>
				<input type="text" class="form-control text-uppercase text-center my-3 d-none" id="txtCodLocal" placeholder='Código de local' value="<?= $_COOKIE['ckLocal'];?>" readonly>
				<input type="text" class="form-control text-uppercase text-center my-3" id="txtNumTicket" placeholder='N° de Ticket' value="103420-8">
			</div>
			<div class="modal-footer">
				<div class="container-fluid">
					<div class="row">
						<p for="" class="text-danger "><small class="lblError"></small></p>
					</div>
					<button type="button" class="btn btn-secondary float-right" id="btnConsultarDisponibilidad" >Consultar disponibilidad</button>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal para consultar el N° ticket -->
<div class="modal fade" id="modalConsultaTicket" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<p>Ingrese los datos de negocio y el N° de Ticket:</p>
				<input type="text" class="form-control text-uppercase text-center my-3" id="txtNCodNegocio2" placeholder='Código de negocio' value="113">
				<input type="text" class="form-control text-uppercase text-center my-3" id="txtCodLocal2" placeholder='Código de local' value="12300">
				<input type="text" class="form-control text-uppercase text-center my-3" id="txtNumTicket2" placeholder='N° de Ticket' value="103420-8">
			</div>
			<div class="modal-footer">
				<div class="container-fluid">
					<div class="row">
						<p for="" class="text-danger "><small class="lblError"></small></p>
					</div>
					<button type="button" class="btn btn-secondary float-right" id="btnConsultarDisponibilidad2" >Consultar</button>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- Modal para empezar el proceso B/v-Fact -->
<div class="modal fade" id="modalProcesarComprobante" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<p>Procesos para el Ticket <strong class="text-uppercase"><span id="spanTicket"></span></strong>:</p>
				<button class="btn btn-block btn-outline-primary my-3" id="btnEmitirFactura">Emitir Factura</button>
				<button class="btn btn-block btn-outline-primary my-3" id="btnEmitirBoleta">Emitir Boleta de Venta</button>
				<button class="btn btn-block btn-outline-primary my-3">Generar Nota de Crédito</button>
				<button class="btn btn-block btn-outline-primary my-3">Generar Nota de Pedido</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal para empezar Modificar las series -->
<div class="modal fade" id="modalModSerie" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modificar series</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="">Serie de Boletas:</label>
				<input type="text" class="form-control text-center" id="txtSerieBoleta">
				<label for="">Serie de Facturas:</label>
				<input type="text" class="form-control text-center" id="txtSerieFactura">
				<label for="">Serie de Interna:</label>
				<input type="text" class="form-control text-center" id="txtSerieInterna">
			</div>
			<div class="modal-footer">
				<p class="text-danger d-none" id="pError2"></p>
				<button type="button" class="btn btn-primary" id="btnUpdateSeries">Actualizar series</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal para empezar Modificar las precios -->
<div class="modal fade" id="modalModPrecios" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Modificar precios</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="">Precio de Gasohol 90 Plus:</label>
				<input type="text" class="form-control text-center" id="txtPrecGasoholv2">
				<label for="">Precio de Diesel D5 S-50 UV:</label>
				<input type="text" class="form-control text-center" id="txtPrecDieselv2">
			</div>
			<div class="modal-footer">
				<p class="text-danger d-none" id="pError3"></p>
				<button type="button" class="btn btn-primary" id="btnUpdatePrecios"><i class="icofont-refresh"></i> Actualizar precios</button>
			</div>
		</div>
	</div>
</div>
<?php if($_COOKIE['ckPower']==1){ ?>
<!-- Modal para confirmar la Baja -->
<div class="modal fade" id="modalDarBajas" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Notificación de baja</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="">¿Desea realmente dar de baja el Comprobante <strong id="strComprobante"></strong>?</label>
				<label for="">Ingrese el motivo de la baja:</label>
				<input type="text" class="form-control text-capitalize" id="txtConceptoBaja">
			</div>
			<div class="modal-footer">
				<p class="text-danger d-none" id="pErrorBajas"></p>
				<!-- <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="icofont-close"></i> Cancelar operación</button> -->
				<button type="button" class="btn btn-danger" id="btnDarbaja"><i class="icofont-download-alt"></i> Dar de Baja</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modalExitoBajas" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<label for="">Comprobante dado de baja exitosamente</label>
				<h5 id="h5ComprobanteBaja"></h5>
			</div>
			<div class="modal-footer">
				<p class="text-danger d-none" id="pError3"></p>
				<button type="button" class="btn btn-success" id="btnDarbaja"  data-dismiss="modal"><i class="icofont-check"></i> Ok</button>
			</div>
		</div>
	</div>
</div>
<!-- Modal para: -->
<div class='modal fade' id='modalConfirmarBorrar' tabindex='-1'>
	<div class='modal-dialog modal-sm modal-dialog-centered'>
		<div class='modal-content'>
			<div class='modal-body'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
				<h5 class='modal-title'>Confirmar</h5>
				<p>¿Deseas borrar el comprobante extra?</p>
				<div class='d-flex justify-content-between'>
					<button type='button' class='btn btn-outline-dark' data-dismiss="modal">No</button>
					<button type='button' class='btn btn-outline-danger' onclick="borrarDefinitivamente()" data-dismiss="modal">Sí</button>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

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

	$.ajax({url: 'php/getPreciosProductos.php', type: 'POST' }).done(function(resp) {
		//console.log(resp)
		console.info( '\033[35mLista de precios:' );
		$.precios = JSON.parse(resp);
		//console.log( $.precios );
	});
	$('#fechaFiltro').val( moment().format('YYYY-MM-DD'));
	$('[data-toggle="tooltip"]').tooltip();
	$('#tablaPrincipal tbody').children().remove();

	$.ajax({url: 'php/listarTodoPorFecha.php', type: 'POST' }).done(function(resp) {
		//console.log(resp)
		$('#tablaPrincipal tbody').append(resp);
		$('[data-toggle="tooltip"]').tooltip();
		$("#tablaPrincipal").stupidtable();
		sumarGenerados()
	});
	$('#fechaFiltro').change(function() {
		//console.log( moment($('#fechaFiltro').val()).isValid() );
		$.ajax({url: 'php/listarTodoPorFecha.php', type: 'POST', data:{fecha: $('#fechaFiltro').val(), fecha2:$('#fechaFiltro').val() } }).done(function(resp) {
			$('#tablaPrincipal tbody').children().remove();
			$('#tablaPrincipal tbody').append(resp).anotherJqueryMethod;
			$('[data-toggle="tooltip"]').tooltip();
			sumarGenerados()
			
		});
	});
	$('#sltContado').change( ()=>{
		if( $('#sltContado').val()==1){
			$('#divAdelanto').addClass('d-none')
		}else{
			$('#divAdelanto').removeClass('d-none')
		}
	})
});
function sumarGenerados(){
	var sumaDia =0;
	$.each( $('#tablaPrincipal tbody tr'), function(index, obj){
		//console.log( 'cambio num: '+ $(obj).find('.spTotalPac').text() );
		let caso  = $(obj).find('.spTotalPac');
		if($(caso).attr('data-estado')!='4'){
			sumaDia+= parseFloat( caso.text().replace(',',''));
		}
	});
	$('#strCantdad').text( $('#tablaPrincipal tbody tr').length );
	$('#strTotal').text( parseFloat(sumaDia).toFixed(2) );
	$.sumaDia = sumaDia;
	limiteVentas();
}
/* $('#btnEmitirBoleta').click(function() {
	$.ajax({url: 'emision.php', type: 'POST', data: { emitir: 3, factura: $('#txtCodigoFact').val() }}).done(function(resp) {
		console.log(resp)
		if(resp=='fin'){
			$('#modalArchivoBien').modal('show');
		}
	});
}); */
function limiteVentas(){
	//console.log( 'limite' );
	$('#spanLimiteSobrepasado').parent().addClass('d-none');
	$('#spanLimiteSobrepasado').parent().removeClass('d-flex');
	if(isNaN($.sumaDia)){
		$('#strTotal').text( '0.00' );
	}else{
		if( parseFloat($.sumaDia) >= parseFloat(<?= $_COOKIE['limiteFacurado']?>)){
			$('#spanLimiteSobrepasado').parent().removeClass('d-none');	
			$('#spanLimiteSobrepasado').parent().addClass('d-flex');
		}
	}
}

$('#btnEmitirComprobante').click(function() {
	/* $('#txtNCodNegocio').val('');
	$('#txtCodLocal').val('');
	$('#txtNumTicket').val(''); */
	$('#modalIngresoTicket .lblError').text('');
	$('#modalIngresoTicket').modal('show');
});
$('#btnConsultarDisponibilidad').click(function() {
	if( $('#txtNCodNegocio').val()=='' || $('#txtCodLocal').val()=='' || $('#txtNumTicket').val()==''){
		$('#modalIngresoTicket .lblError').text('Debe rellenar todos los datos para procesar su ticket');
	}else{
		
		$('#spanTicket').text($('#txtNumTicket').val());
		$.ajax({url: 'comprobarTicket.php', type: 'POST', data: { local:$('#txtCodLocal').val() , negocio:$('#txtNCodNegocio').val() , ticket:$('#txtNumTicket').val()  }}).done(function(resp) {
			//console.log(resp)
			if( resp == $('#txtNumTicket').val() ){
				$('#modalIngresoTicket').modal('hide');
				$('#modalProcesarComprobante').modal('show');
			}else if(resp =='sin coincidencia'){
				$('#modalIngresoTicket .lblError').text('Datos errados, no coincide con ningún ticket.');
			}else{
				$('#modalIngresoTicket .lblError').text('Hubo un error interno, comuníquelo a su proveedor');
			}
			//
		});
	}
});
$('#btnConsultarDisponibilidad2').click(function() {
	if( $('#txtNCodNegocio2').val()=='' || $('#txtCodLocal2').val()=='' || $('#txtNumTicket2').val()==''){
		$('#modalConsultaTicket .lblError').text('Debe rellenar todos los datos para procesar su ticket');
	}else{
		
		$('#spanTicket2').text($('#txtNumTicket2').val());
		$.ajax({url: 'comprobarTicket.php', type: 'POST', data: { local:$('#txtCodLocal2').val() , negocio:$('#txtNCodNegocio2').val() , ticket:$('#txtNumTicket2').val() }}).done(function(resp) {
			console.log(resp)
			if( resp == $('#txtNumTicket2').val() ){
				$.ajax({url: 'reverificacion.php', type: 'POST', data: { local:$('#txtCodLocal2').val() , negocio:$('#txtNCodNegocio2').val() , ticket:$('#txtNumTicket2').val() }}).done(function(resp) {
					console.log(resp)
					$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
					if($.jTicket.length >=1){
						$('#modalProcesarComprobante').modal('hide');
						$('#modalArchivoBien').modal('show');
						imprimitEnTicketera()
						
					}
				});
				$('#modalConsultaTicket').modal('hide');
				$('#modalArchivoBien').modal('show');
				
			}else if(resp =='sin coincidencia'){
				$('#modalConsultaTicket .lblError').text('Datos errados, no coinciden con ningún local y ticket.');
			}else{
				$('#modalConsultaTicket .lblError').text('Hubo un error interno, comuníquelo a su proveedor');
			}
			//
		});
	}
});
$('#btnConsultarComprobante').click(function() {
	$('#modalConsultaTicket .lblError').text('');
	$('#modalConsultaTicket').modal('show');

});
$('#btnEmitirFactura').click(function() {
	$.ajax({url: 'emision.php', type: 'POST', data: { emitir: 1, local:$('#txtCodLocal').val() , negocio:$('#txtNCodNegocio').val() , ticket:$('#txtNumTicket').val()  }}).done(function(resp) {
		//console.log(resp)
		$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
		if($.jTicket.length >=1){
			$('#modalProcesarComprobante').modal('hide');
			$('#modalArchivoBien').modal('show');
		}
	});
});

$('#btnEmitirBoleta').click(function() {
	$.ajax({url: 'emision.php', type: 'POST', data: { emitir: 3, local:$('#txtCodLocal').val() , negocio:$('#txtNCodNegocio').val() , ticket:$('#txtNumTicket').val()  }}).done(function(resp) {
		//console.log(resp)
		$.jTicket = JSON.parse(resp); console.log( $.jTicket );
		if($.jTicket.length >=1){
			$('#modalProcesarComprobante').modal('hide');
			$('#modalArchivoBien').modal('show');
		}
		//if(resp=='fin'){	} 
	});
});
$('#modalArchivoBien').on('hidden.bs.modal', function () { 
	location.reload();
});
$('tbody').on('click', '.imprTicketFuera', function (e) {
	var padre= $(this).parent()

	var caso = padre.attr('data-caso');
	var serie = padre.attr('data-serie');
	var correlativo = padre.attr('data-correlativo');
	
	$.ajax({url: 'solicitarDataComprobante.php', type: 'POST', data: { caso:caso, serie: serie, correlativo: correlativo }}).done(function(resp) {
		console.log( resp );
		$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
		$.ajax({url: 'http://127.0.0.1/<?= $casaHost; ?>/<?= $_COOKIE['demoFacturador']=="true" ? 'php/printDemoTicket.php' : 'printComprobante.php' ?>', type: 'POST', data: {
			tipoComprobante: $.jTicket[0].tipoComprobante,
			rucEmisor: $.jTicket[0].rucEmisor,
			queEs: $.jTicket[0].queSoy,
			serie: $.jTicket[0].serie,
			correlativo: $.jTicket[0].correlativo,
			tipoCliente: $.jTicket[0].tipoCliente,
			fecha: $.jTicket[0].fechaEmision,
			fechaLat: moment($.jTicket[0].fechaEmision, 'YYYY-MM-DD').format('DD/MM/YYYY'),
			cliente: $.jTicket[0].razonSocial,
			docClient: $.jTicket[0].ruc,
			monedas: $.jTicket[0].letras,
			descuento: parseFloat($.jTicket[0].descuento).toFixed(2),
			costoFinal: parseFloat($.jTicket[0].costoFinal).toFixed(2),
			igvFinal: parseFloat($.jTicket[0].igvFinal).toFixed(2),
			totalFinal: parseFloat($.jTicket[0].totalFinal).toFixed(2),
			productos: $.jTicket[1],
			direccion:$.jTicket[0].direccion,
			exonerado: parseFloat($.jTicket[0].exonerado).toFixed(2),
			/* placa: $.jTicket[0].placa, */
		}}).done(function(resp) {
			console.log(resp)
			//location.reload();
		});
	});

});
$('#btnPrintTicketera').click(function() { console.log( 'ticketera' );
	imprimitEnTicketera()
});
function imprimitEnTicketera(){
	$.ajax({url: 'http://127.0.0.1/<?= $casaHost; ?>/printComprobante.php', type: 'POST', data: {
				tipoComprobante: $.jTicket[0].tipoComprobante,
				rucEmisor: $.jTicket[0].rucEmisor,
				queEs: $.jTicket[0].queSoy,
				serie: $.jTicket[0].serie,
				correlativo: $.jTicket[0].correlativo,
				tipoCliente: $.jTicket[0].tipoCliente,
				fecha: $.jTicket[0].fechaEmision,
				fechaLat: moment($.jTicket[0].fechaEmision, 'YYYY-MM-DD').format('DD/MM/YYYY'),
				cliente: $.jTicket[0].razonSocial,
				docClient: $.jTicket[0].ruc,
				monedas: $.jTicket[0].letras,
				descuento: parseFloat($.jTicket[0].descuento).toFixed(2),
				costoFinal: parseFloat($.jTicket[0].costoFinal).toFixed(2),
				igvFinal: parseFloat($.jTicket[0].igvFinal).toFixed(2),
				totalFinal: parseFloat($.jTicket[0].totalFinal).toFixed(2),
				productos: $.jTicket[1],
				direccion:$.jTicket[0].direccion,
				exonerado: parseFloat($.jTicket[0].exonerado).toFixed(2),
				/* placa: $.jTicket[0].placa, */
			}}).done(function(resp) {
				console.log(resp)
				//location.reload();
			});
}
$('#btnModificarSerie').click(function() {
	$.ajax({url: 'llamarSeries.php', type: 'POST', data: { }}).done(function(resp) {
		var data = JSON.parse(resp)[0];
		console.log( data );
		$('#txtSerieBoleta').val( data.serieBoleta );
		$('#txtSerieFactura').val( data.serieFactura );
		$('#txtSerieInterna').val( data.serieOpcional );
		
		$('#modalModSerie').modal('show');
	});
	
});
$('#btnUpdateSeries').click(function() {
	$('#pError2').addClass('d-none')
	if( $('#txtSerieBoleta').val()=='' || $('#txtSerieFactura').val()=='' || $('#txtSerieInterna').val()=='' ){
		$('#pError2').removeClass('d-none').text('Ambos campos deben estar rellenados');
	}else{
		$.ajax({url: 'updateSeries.php', type: 'POST', data: { serFact: $('#txtSerieFactura').val(), serBol: $('#txtSerieBoleta').val(), serInt: $('#txtSerieInterna').val() }}).done(function(resp) {
			if( resp =='ok' ){
				$('#modalModSerie').modal('hide');
			}else{
				$('#pError2').removeClass('d-none').text('Hubo un error interno al guardar los datos');
			}
		});
	}
});
$('tbody').on('click', '.imprA4Fuera', function (e) {
	var padre= $(this).parent()

	var caso = padre.attr('data-caso');
	var serie = padre.attr('data-serie');
	var correlativo = padre.attr('data-correlativo');

	$.ajax({url: 'solicitarDataComprobante.php', type: 'POST', data: { caso:caso, serie: serie, correlativo: correlativo }}).done(function(resp) {
		console.log(resp)
		$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
		window.open( 'printComprobanteA4.php?serie='+encodeURIComponent(serie)+'&correlativo='+encodeURIComponent(correlativo) ,'_blank');
	});
});
$('tbody').on('click', '.imprPDFFuera', function (e) {
	var padre= $(this).parent()

	var caso = padre.attr('data-caso');
	var serie = padre.attr('data-serie');
	var correlativo = padre.attr('data-correlativo');

	$.ajax({url: 'solicitarDataComprobante.php', type: 'POST', data: { caso:caso, serie: serie, correlativo: correlativo }}).done(function(resp) {
		console.log(resp)
		$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
		window.open( 'printComprobantePDF.php?serie='+encodeURIComponent(serie)+'&correlativo='+encodeURIComponent(correlativo) ,'_blank');
	});
});
$('#btnPrintA4').click(function() {
	window.open( 'printComprobanteA4.php?serie='+encodeURIComponent($.jTicket[0].serie)+'&correlativo='+encodeURIComponent($.jTicket[0].correlativo) ,'_blank');
	location.reload();
});
$('#btnPrintPDF').click(function() {
	window.open( 'printComprobantePDF.php?serie='+encodeURIComponent($.jTicket[0].serie)+'&correlativo='+encodeURIComponent($.jTicket[0].correlativo) ,'_blank');
	location.reload();
});
$('tbody').on('click', '.btnGenComprobante', function (e) {
	var ticket = $(this).attr('data-ticket');
	//console.log( ticket );
	
	$.ajax({url: 'emision.php', type: 'POST', data: { ticket: ticket }}).done(function(resp) {
		console.log(resp)
		$.jTicket = JSON.parse(resp); //console.log( $.jTicket );
		if($.jTicket.length >=1){
			$('#modalProcesarComprobante').modal('hide');
			$('#modalArchivoBien').modal('show');
		}
	});
});
$('#btnRefresh').click(function() {
	location.reload();
});
$('#AEmitirBoleta').click(function() {
	$.soy="boleta";
	$('#optBoleta').attr('disabled',false);
	$('#optFactura').attr('disabled',true);
	$('#optOpcional').attr('disabled',false);
	$('#optOpcional2').attr('disabled',true);
	$('#txtDniBoleta').attr('placeholder', 'D.N.I.');
	$('#txtRazonBoleta').attr('placeholder', 'Nombres y apellidos');
	$('#btnEmitirBoletav2').removeClass('d-none');
	$('#btnEmitirFacturav2').addClass('d-none');
	$('#sltSeriesBoleta option').removeAttr('selected');
	$('#optBoleta').attr('selected', true);
	//$('#chkEstadoDni').prop('checked', true).change().attr('disabled', false);
	$('#sltEstadoDni').val(0).change().attr('disabled', false);
	$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'));
	$('#queGenero').text('Boleta de venta');
	$('#modalEmisionBoleta').modal('show');
	$('#sltContado').parent().addClass('d-none')
});
$('#AEmitirFactura').click(function() {
	$.soy="factura";
	$('#optBoleta').attr('disabled',true);
	$('#optFactura').attr('disabled',false);
	$('#optOpcional').attr('disabled',true);
	$('#optOpcional2').attr('disabled',false);
	$('#txtDniBoleta').attr('placeholder', 'R.U.C.');
	$('#txtRazonBoleta').attr('placeholder', 'Razón social');
	$('#btnEmitirBoletav2').addClass('d-none');
	$('#btnEmitirFacturav2').removeClass('d-none');
	$('#optFactura').attr('selected', true);
	//$('#chkEstadoDni').prop('checked', false).change().attr('disabled', true);
	$('#sltEstadoDni').val(6).change().attr('disabled', true);
	$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'));
	$('#queGenero').text('Factura');
	$('#modalEmisionBoleta').modal('show');
	$('#sltContado').parent().removeClass('d-none')
});

$('#sltEstadoDni').change(()=>{
	$('.selectpicker').selectpicker('val', -1);
	switch($('#sltEstadoDni').val()){
		case '0':
			$('#txtDniBoleta').attr('placeholder', 'No DOC.')
			$('#txtRazonBoleta').attr('placeholder', 'Sin datos')
			$('#txtDniBoleta').val('00000000');
			$('#txtRazonBoleta').val('Cliente simple');
			$('#txtDireccionBoleta');
			break;
		case '1':
			$('#txtDniBoleta').attr('placeholder', 'D.N.I.')
			$('#txtRazonBoleta').attr('placeholder', 'Nombres y apellidos')
			$('#txtDniBoleta').val('');
			$('#txtRazonBoleta').val('');
			$('#txtDireccionBoleta').val('');
			break;
		case '7':
			$('#txtDniBoleta').attr('placeholder', 'N° Pasaporte')
			$('#txtRazonBoleta').attr('placeholder', 'Nombres y apellidos')
			$('#txtDniBoleta').val('');
			$('#txtRazonBoleta').val('');
			$('#txtDireccionBoleta').val('');
			break;
		case '6':
			$('#txtDniBoleta').attr('placeholder', 'R.U.C.')
			$('#txtRazonBoleta').attr('placeholder', 'Razón social')
			$('#txtDniBoleta').val('');
			$('#txtRazonBoleta').val('');
			$('#txtDireccionBoleta').val('');
			break;
	}
})

/* $('#chkEstadoDni').change(function() {
	if($('#chkEstadoDni').prop('checked')	){
		$('#labelEstadoDni').text('Cliente anónimo');
		//$('#divDatosCliente').addClass('d-none');
		$('#txtDniBoleta').val('00000000');
		$('#txtDniBoleta').attr('readonly', true)
		$('#txtRazonBoleta').val('Cliente simple');
		$('#txtRazonBoleta').attr('readonly', true);
		$('#txtDireccionBoleta').attr('readonly', true).val('');
	}else{
		$('#labelEstadoDni').text('Cliente con Documento');
		//$('#divDatosCliente').removeClass('d-none');
		$('#txtRazonBoleta').attr('readonly', false);
		$('#txtDireccionBoleta').attr('readonly', false);
		$('#txtDniBoleta').attr('readonly', false).focus();
	}
}); */
$('#divProductos').on('keyup','.campoSubTotal', function() {	
	var padre = $(this).parent().parent();
	var subTotal = 0;
	var precio = parseFloat(padre.find('.campoPrecioUnit').val());
	var cantidad = 0;//parseFloat(padre.find('.campoCantidad').val());
	if($(this).val()!=''){
		subTotal = parseFloat($(this).val());
	}
	cantidad = parseFloat(subTotal/precio);
	if( cantidad==Infinity ){
		cantidad=0;
	}
	padre.find('.campoCantidad').val( cantidad.toFixed(2) );
	sumaTodo();
});
$('#divProductos').on('keyup','.campoPrecioUnit', function() {
	var padre = $(this).parent().parent();
	var precio = 0;
	var cantidad = parseFloat(padre.find('.campoCantidad').val());
	var subTotal = 0;//parseFloat(padre.find('.campoPrecioUnit').val());
	if($(this).val()!=''){
		precio = parseFloat($(this).val());
	}

	subTotal = parseFloat(cantidad*precio);
	padre.find('.campoSubTotal').val( subTotal.toFixed(2) );
	sumaTodo();
});
$('#divProductos').on('keyup','.campoCantidad', function() {
	var padre = $(this).parent().parent();
	var cantidad = 0;
	if($(this).val()!=''){
		cantidad = parseFloat($(this).val());
	}
	var precio = parseFloat(padre.find('.campoPrecioUnit').val());
	var subTotal = 0;//parseFloat(padre.find('.campoPrecioUnit').val());
	subTotal = parseFloat(cantidad*precio);
	padre.find('.campoSubTotal').val( subTotal.toFixed(2) );
	sumaTodo();
});
function sumaTodo() {
	var sumaTotal=0, afectos = 0, exonerados = 0;
	
	$.each( $('.campoSubTotal'), function(i, elem){
		if( $(elem).val()!='' ){
			if( $(elem).attr('data-exonerado')=='1' ){
				afectos+=parseFloat($(elem).val());
			}else{
				exonerados+=parseFloat($(elem).val());
			}
		}
		
	});
	//console.log( sumaTotal );
	sumaTotal=afectos+exonerados;
	var costo = afectos/parseFloat(<?= $porcentajeIGV1; ?>);
	var igv=afectos-costo;
	$('#spExoneradoBoleta').text(parseFloat(exonerados).toFixed(2));
	$('#spSubTotBoleta').text(parseFloat(costo).toFixed(2));
	$('#spIgvBoleta').text(parseFloat(igv).toFixed(2));
	$('#spTotalBoleta').text(parseFloat(sumaTotal).toFixed(2));
	calcularVuelto();
}
/* $('#modalEmisionBoleta').on('shown.bs.modal', function () { 
	$('#txtPlacaBoleta').focus();
}); */
		
		
$('#btnEmitirBoletav2').click(function() {
	
	/* console.log( resuelve() ); */
	
	const promesaCompletoTodo = new Promise((resolve, reject) => {
		var truncado = false;
		$.each( $('.cardHijoProducto'), function (i, elem) { 
			
			if( $(elem).find('.sltFiltroProductos').selectpicker('val')=='1' && $(elem).find('.campoTextoLibre').val()=='' ){
				truncado = true;
			}
			if( i== $('.cardHijoProducto').length -1 && truncado ==true ){
				//console.log( 'vacio1' );
				$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Existen conceptos vacíos en uno de los items').parent().removeClass('d-none');
				reject('falta datos');
			}else if( i== $('.cardHijoProducto').length -1 && truncado ==false ){
				//console.log( 'completo todo' );
				$('#modalEmisionBoleta .lblError').parent().addClass('d-none');
				resolve('completo todo')
			}
		});
	});

	promesaCompletoTodo.then(resPromesa => {
		
		if($('#txtDniBoleta').val()=='' && $.queSoy=='boleta'){
			$('#txtDniBoleta').focus();
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> No es un DNI válido').parent().removeClass('d-none');
		}else if( $.queSoy=='factura'){
			$('#txtDniBoleta').focus();
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> No es un RUC válido').parent().removeClass('d-none');			
		}else if( $('#sltSeriesBoleta').val()=='series'){
			$('#sltSeriesBoleta').focus();
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidaste seleccionar un tipo de serie').parent().removeClass('d-none');
		}else if( $('.cardHijoProducto').first().find('#sltTemporal').selectpicker('val')==null ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidaste seleccionar un producto').parent().removeClass('d-none');
		}else if( $('.cardHijoProducto').first().find('.esMoneda').val()=='0.00' || $('.cardHijoProducto').first().find('.esMoneda').val()==0 || $('.cardHijoProducto').first().find('.campoPrecioUnit').val()=='0.00' || $('.cardHijoProducto').first().find('.campoPrecioUnit').val()==0 ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidaste ingresar una cantidad / precio').parent().removeClass('d-none');
		}else if( $('.cardHijoProducto').first().find('#sltfiltroTemporal').selectpicker('val')==null ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidaste seleccionar una unidad').parent().removeClass('d-none');
		}else if( $('#spTotalBoleta').text()=='0.00' ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Debe haber al menos un producto con precio').parent().removeClass('d-none');
		}else if( $('.cardHijoProducto').first().find('.sltFiltroProductos').selectpicker('val')== null ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Debe haber seleccionar al menos un producto').parent().removeClass('d-none');	
		}else if( parseFloat($('#spTotalBoleta').text())>700 && $('#txtDniBoleta').val().length<8 ){
			$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Ésta boleta por ser mayor a S/ 700.00 requiere DNI').parent().removeClass('d-none');
		}
		else{
			var jsonCliente= [];
			var dniRc ='', razon='';
			if($('#txtDniBoleta').val()!=''){
				dniRc=$('#txtDniBoleta').val();
				razon=$('#txtRazonBoleta').val()
			}else{
				dniRc='00000000';
				if($('#txtRazonBoleta').val()==''){
					razon='Cliente sin documento';
				}else{
					razon=$('#txtRazonBoleta').val()
				}
			}
			
			jsonCliente.push({
				tipo: $('#sltEstadoDni').val(),
				dni: dniRc,
				razon: razon,
				direccion: $('#txtDireccionBoleta').val(),
				contado: $('#sltContado').val(),
				adelanto: $('#txtAdelanto').val()
			});
			
			var jsonProductos = [];
			$.each( $('.cardHijoProducto'), function (i, elem) {
				var productVariable ='';
				if( $(elem).find('.sltFiltroProductos').selectpicker('val')!='' ){
					if($(elem).find('.divNombProducto button').attr('title')=='Libre'){
						productVariable = $(elem).find('.campoTextoLibre').val();
					}else{
						productVariable = $(elem).find('.divNombProducto button').attr('title')
					}
					jsonProductos.push({cantidad: $(elem).find('.campoCantidad').val(),
						descripcionProducto: productVariable,
						precioProducto: $(elem).find('.campoPrecioUnit').val(),
						unidadProducto: $(elem).find('.divUnidadProducto button').attr('title'),
						unidadSunat: $(elem).find('.divUnidadProducto .sltFiltroUnidad').selectpicker('val'),
						unidadCorto: $(elem).find(`.sltFiltroUnidad option[value="${$(elem).find('.divUnidadProducto .sltFiltroUnidad').selectpicker('val')}"]`).attr('data-unidad') ,
						subtotal: $(elem).find('.campoSubTotal').val(),
						afecto: $(elem).find('#sltFiltroGravado').selectpicker('val'),
						idProd: $(elem).attr('data-producto')
					});
				}
				
			});
			
			
			$.ajax({url: 'php/insertarBoleta.php', type: 'POST', data: { emitir: 3, queSerie: $('#sltSeriesBoleta').val(), dniRUC: dniRc, razonSocial: razon, cliDireccion: $('#txtDireccionBoleta').val(),jsonProductos: jsonProductos, jsonCliente: jsonCliente, fecha: $('#txtFechaComprobante').val() }}).done(function(resp) { //  placa: $('#txtPlacaBoleta').val(),
				console.log(resp)
				$.jTicket = JSON.parse(resp); console.log( $.jTicket );
				if($.jTicket.length >=1){
					$('#modalEmisionBoleta').modal('hide');
					$('#modalArchivoBien').modal('show');
				}
			});
		}


	})

	
});
$('#btnEmitirFacturav2').click(function() {
	$('#modalEmisionBoleta .lblError').parent().addClass('d-none');
	if( $('#sltSeriesBoleta').val()=='series'){
		$('#sltSeriesBoleta').focus();
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidaste seleccionar un tipo de serie').parent().removeClass('d-none');
	}/* else if( $('#txtPlacaBoleta').val()==''){
		$('#txtPlacaBoleta').focus();
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> La placa del automóvil tiene que ser rellenado').parent().removeClass('d-none');
	} */else if( $('#txtDniBoleta').val().length!=11 ){
		$('#txtDniBoleta').focus();
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> El RUC del cliente, no es correcto').parent().removeClass('d-none');
	}else if( $('#txtRazonBoleta').val()=='' ){
		$('#txtRazonBoleta').focus();
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> La razón social no puede estar en blanco').parent().removeClass('d-none');
	}else if( $('#spTotalBoleta').text()=='0.00' ){
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Debe haber al menos un producto con precio').parent().removeClass('d-none');
	}else if( $('.cardHijoProducto').first().find('.sltFiltroProductos').selectpicker('val')== null ){
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Debe haber seleccionar al menos un producto').parent().removeClass('d-none');
	}else{
		var jsonCliente= [];
		var dniRc ='', razon='';
		if($('#txtDniBoleta').val()!=''){
			dniRc=$('#txtDniBoleta').val();
			razon=$('#txtRazonBoleta').val()
		}else{
			dniRc='00000000';
			razon='Cliente sin documento';
		}
		
		jsonCliente.push({
			tipo: $('#sltEstadoDni').val(),
			dni: dniRc,
			razon: razon,
			direccion: $('#txtDireccionBoleta').val(),
			contado: $('#sltContado').val(),
			adelanto: $('#txtAdelanto').val()
		});
		
		var jsonProductos= [];
		$.each( $('.cardHijoProducto'), function (i, elem) {
			if( $(elem).find('.sltFiltroProductos').selectpicker('val')!='' ){
				if($(elem).find('.divNombProducto button').attr('title')=='Libre'){
					productVariable = $(elem).find('.campoTextoLibre').val();
				}else{
					productVariable = $(elem).find('.divNombProducto button').attr('title')
				}
				jsonProductos.push({cantidad: $(elem).find('.campoCantidad').val(),
					descripcionProducto: productVariable,
					precioProducto: $(elem).find('.campoPrecioUnit').val(),
					unidadProducto: $(elem).find('.divUnidadProducto button').attr('title'),
					unidadSunat: $(elem).find('.divUnidadProducto .sltFiltroUnidad').selectpicker('val'),
					unidadCorto: $(elem).find(`.sltFiltroUnidad option[value="${$(elem).find('.divUnidadProducto .sltFiltroUnidad').selectpicker('val')}"]`).attr('data-unidad') ,
					subtotal: $(elem).find('.campoSubTotal').val(),
					afecto: $(elem).find('#sltFiltroGravado').selectpicker('val'),
					idProd: $(elem).attr('data-producto')
				});
		}
		});
		
		$.ajax({url: 'php/insertarBoleta.php', type: 'POST', data: { emitir: 1, queSerie: $('#sltSeriesBoleta').val(), dniRUC: dniRc, razonSocial: razon, cliDireccion: $('#txtDireccionBoleta').val(), jsonProductos: jsonProductos, jsonCliente: jsonCliente, fecha: $('#txtFechaComprobante').val() }}).done(function(resp) { // placa: $('#txtPlacaBoleta').val(),
			console.log(resp)
			$.jTicket = JSON.parse(resp); console.log( $.jTicket );
			if($.jTicket.length >=1){
				$('#modalEmisionBoleta').modal('hide');
				$('#modalArchivoBien').modal('show');
			}
		});
	}
});
$('#btnModificarPrecios').click(function() {
	$.ajax({url: 'php/llamarPrecios.php', type: 'POST'}).done(function(resp) {
		data=JSON.parse(resp);
		console.log( data );
		$('#txtPrecDieselv2').val(parseFloat(data[0].prodPrecio).toFixed(2))
		$('#txtPrecGasoholv2').val(parseFloat(data[1].prodPrecio).toFixed(2))
	});
	$('#modalModPrecios').modal('show');
});
$('#btnUpdatePrecios').click(function() {
	$('#pError3').addClass('d-none')
	if( $('#txtPrecDieselv2').val()=='' || $('#txtPrecGasoholv2').val()==''  ){
		$('#pError3').removeClass('d-none').text('Ningún precio puede estar vacío');
	}else{
		$.ajax({url: 'php/updatePrecios.php', type: 'POST', data: { diesel: $('#txtPrecDieselv2').val(), gasohol: $('#txtPrecGasoholv2').val() }}).done(function(resp) {
			console.log( resp );
			if( resp =='ok' ){
				$('#modalModPrecios').modal('hide');
				location.reload();
			}else{
				$('#pError3').removeClass('d-none').text('Hubo un error interno al guardar los datos');
			}
		});
	}
});
$('#sltFiltroClientes').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
	
	var index=$('#sltFiltroClientes').val();
	var padre = $("#sltFiltroClientes option[value="+index+"]");
	if($(this).val()!=null){
		if( padre.attr('data-ruc').length==8 ){
			$('#sltEstadoDni').val(1).change()
		}else if(padre.attr('data-ruc').length==11 ){
			$('#sltEstadoDni').val(6).change()
			//$('#chkEstadoDni').prop('checked', false).change();
		}else{
			$('#sltEstadoDni').val(7).change()
		}
	}
	$('#txtDniBoleta').val(padre.attr('data-ruc'));
	$('#txtRazonBoleta').val(padre.attr('data-razon'));
	$('#txtDireccionBoleta').val(padre.attr('data-direccion'));
});
$('#btnAgregarProducto').click(function() {
	$('#modalEmisionBoleta .lblError').parent().addClass('d-none');
	if( !$.isNumeric($('#divProductos .sltFiltroProductos').last().selectpicker('val')) ){
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Falta seleccionar un producto').parent().removeClass('d-none');
	}else if( $('#divProductos .sltFiltroUnidad').last().selectpicker('val')==null ){
		$('#modalEmisionBoleta .lblError').html('<i class="icofont-cat-alt-3"></i> Olvidó rellenar una unidad').parent().removeClass('d-none');
	}else{
		$.ajax({url: 'php/filaNueva.php', type: 'POST' }).done(function(resp) {
			//console.log(resp)
			$('#divProductos').append(resp);
			$('.selectpicker').selectpicker('render');
		});
	}
});
$('#divProductos').on('changed.bs.select', '.sltFiltroProductos', function (e, clickedIndex, isSelected, previousValue) {
	var padre = $(this).parent().parent(); //.parent()
	console.log( padre );
	
	if( $(this).selectpicker('val')!=null && $(this).selectpicker('val')!='' ){
		var queProd= $(this).selectpicker('val');
		$.each( $.precios , function(i, prodObj){
			if(prodObj.idProductos==queProd){
				//console.log(  prodObj.prodPrecio );
				//padre.find('.sltFiltroUnidad').selectpicker('val', '3')
				padre.attr('data-producto', prodObj.idProductos );
				padre.find('.sltFiltroUnidad #sltfiltroTemporal').selectpicker('val', prodObj.undSunat ).selectpicker('refresh');;
				padre.find('#sltFiltroGravado').selectpicker('val', prodObj.idGravado ).selectpicker('refresh');;
				padre.find('.campoPrecioUnit').val(parseFloat(prodObj.prodPrecio).toFixed(2));
				padre.find('.campoSubTotal').val(parseFloat(prodObj.prodPrecio).toFixed(2)).attr('data-exonerado', padre.find('#sltFiltroGravado').selectpicker('val'));
				padre.find('.campoCantidad').val(1).focus();
			}
		});

		//console.log('ver:' + JSON.stringify($(this).selectpicker('val')))

		if( clickedIndex ==1){ //codigo de la posición libre -> Antes #52
			padre.find('#sltFiltroGravado').prop('disabled', false).selectpicker('refresh');
			
			padre.find('.sltFiltroProductos').addClass('d-none');
			padre.find('.campoTextoLibre').removeClass('d-none').focus();

			padre.find('.sltFiltroPrecios').selectpicker('val', 0);
			padre.find('.campoPrecioUnit').prop('readonly', false);
			console.log('estoy en libre')
		}
		else if(clickedIndex>1){
			padre.find('.sltFiltroPrecios').selectpicker('val', 1);
			padre.find('.campoPrecioUnit').prop('readonly', true);
			console.log('estoy en otro')
		}
		padre.find('#sltFiltroGravado').selectpicker('val', 1)
		padre.find('.sltFiltroUnidad').selectpicker('val', 'NIU')
		sumaTodo();
	}
});
$('#divProductos').on('click', '.borrarFila', function (e) {
	var padre=$(this).parent().parent();
	padre.find('.campoPrecioUnit').prop('readonly',true);
	padre.find('#sltFiltroGravado').prop('disabled', true).selectpicker('refresh');
	if($('.cardHijoProducto').length>1){
		padre.remove();
	}else{
		padre.find('.sltFiltroProductos').selectpicker('val', -1).selectpicker('refresh');
		padre.find('.campoCantidad').val(0);
		padre.find('.campoPrecioUnit').val('0.00');
		padre.find('.campoSubTotal').val('0.00');
		padre.find('.bootstrap-select').removeClass('d-none');
		padre.find('.campoTextoLibre').addClass('d-none');
	}
	sumaTodo();
});

$('#divProductos').on('click', '.optPrecios', function (e) {
	var padre = $(this).parent().parent().parent().parent().parent().parent().parent();
	padre.find('.campoPrecioUnit').prop('readonly',true);
	
	switch ( padre.find('#sltFiltroPrecios').selectpicker('val')) {
		case "0":
			padre.find('.campoPrecioUnit').prop('readonly',false).focus().val('0.00');
		case "1":
			$.each( $.precios , function(i, prodObj){
				if(prodObj.idProductos == padre.attr('data-producto') ){
					padre.find('.campoPrecioUnit').val( parseFloat(prodObj.prodPrecio).toFixed(2) ).keyup(); sumaTodo(); return false;
				}
			});
			break;
		case "2":
			$.each( $.precios , function(i, prodObj){
				if(prodObj.idProductos == padre.attr('data-producto') ){
					padre.find('.campoPrecioUnit').val( parseFloat(prodObj.prodPrecioMayor).toFixed(2) ).keyup(); sumaTodo(); return false;
				}
			});
			break;
		case "3":
			$.each( $.precios , function(i, prodObj){
				if(prodObj.idProductos == padre.attr('data-producto') ){
					padre.find('.campoPrecioUnit').val( parseFloat(prodObj.prodPrecioDescto).toFixed(2) ).keyup(); sumaTodo(); return false;
				}
			});
			break;
		default:
			break;
	}
	
});

$("#txtDniBoleta").keyup(function(e){
		var code = e.which; 
		if( code==13 ){ e.preventDefault(); // console.log( 'enter' );
		pantallaOver(true);
		if( $("#txtDniBoleta").val().length>0 ){
			$.ajax({url: 'php/dataSunat.php', type: 'POST', data: { ruc: $('#txtDniBoleta').val() }}).done(function(resp) {
				//console.log(resp)
				try {
					dato = JSON.parse(resp);
					if(dato.length=!0){	
						//console.log( dato.razon_social );
						$('#txtRazonBoleta').val( dato.razon_social);
						$('#txtDireccionBoleta').val( dato.domicilio_fiscal);
					}
				} catch (error) {
					
				}
				
				$('#txtRazonBoleta').focus();
				pantallaOver(false);
			});
	 	 }
		}
});

$('#txtPagaCuanto').keyup(function() {
	calcularVuelto()
});

function calcularVuelto(){
	if( $('#txtPagaCuanto').val()!=''){
		if( $('#txtPagaCuanto').val() > parseFloat($('#spTotalBoleta').text()) ){
			$('#spanVuelto').text( parseFloat(parseFloat($('#txtPagaCuanto').val()) - parseFloat($('#spTotalBoleta').text())).toFixed(2) );
			$('#spanVuelto').parent().parent().removeClass('d-none')
		}else{
			$('#spanVuelto').parent().parent().addClass('d-none')
		}
	}else{
		$('#spanVuelto').parent().parent().addClass('d-none')
	}
}

function prepararTransformacion(id){
	$.idComprobante = id;
	$.ajax({url: 'php/prepararConversion.php', type: 'POST', data: { id: $.idComprobante }}).done(function(resp) {
		console.log(resp)
		$.transformar = JSON.parse(resp)
		$('#pConverir').text($.transformar.que);
		$('#modalTransformacion').modal('show');
	});
}
function transformar(){
	$.ajax({url: 'php/transformar.php', type: 'POST', data: { id: $.idComprobante, serie: $.transformar.serie, tipo: $.transformar.tipo }}).done(function(resp) {
		console.log(resp)
		location.reload();
	});
}


<?php if($_COOKIE['ckPower']==1){ ?>
$('#tablaPrincipal').on('click', '.btnDarBajas', function (e) {
	$('#strComprobante').text( $(this).parent().parent().find('.tdCorrelativo').text());
	$('#h5ComprobanteBaja').text( $('#strComprobante').text());
	$('#btnDarbaja').attr('data-baja', $(this).attr('data-baja'));
	$('#btnDarbaja').attr('data-boleta', $(this).attr('data-boleta'));
	$('#pErrorBajas').addClass('d-none');
	if($(this).attr('data-boleta')=='3'){
		$('#txtConceptoBaja').addClass('d-none').prev().addClass('d-none');
		$.bajaComprobante = 'boleta';
	}else{
		$('#txtConceptoBaja').removeClass('d-none').prev().removeClass('d-none');
		$.bajaComprobante = 'factura';
	}
	$('#modalDarBajas').modal('show');
});
$('#btnDarbaja').click(function() {
	if( $.bajaComprobante =='factura' && $('#txtConceptoBaja').val()=='' ){
		$('#pErrorBajas').html('<i class="icofont-cat-alt-3"></i>  Falta ingresar un motivo de baja');
		$('#pErrorBajas').removeClass('d-none');
	}else{
		$.ajax({url: 'php/darBajas.php', type: 'POST', data: { concepto: $('#txtConceptoBaja').val(), boleta: $(this).attr('data-boleta'), id: $(this).attr('data-baja') }}).done(function(resp) {
			if(resp=='ok'){
				$('#modalDarBajas').modal('hide');
				$('#modalExitoBajas').modal('show');
				$('#modalExitoBajas').on('hidden.bs.modal', function () { 
					location.reload();
				});
			}
		});

	}
});
$('#txtFechaComprobante').focusout(function() {
	let hoy = moment( moment().format('YYYY-MM-DD'), 'YYYY-MM-DD')
	let comprobante = moment($('#txtFechaComprobante').val(), 'YYYY-MM-DD')
	let diferencia = hoy.diff(comprobante, 'days')
	if( diferencia<0 ){
		$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'))
	}else if(diferencia>2 && $.soy=='factura'){
		$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'))
	}else if(diferencia>4 && $.soy=='boleta'){
		$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'))
	}else if( isNaN(diferencia)){
		$('#txtFechaComprobante').val(moment().format('YYYY-MM-DD'))
	}
});

$('#btnVaciarBandeja').click(function() {
	$('#overlay').css('display', 'flex')
	$('#pFrase').text('Limpiando los datos de la bandeja')
	$.ajax({url: '<?= $_COOKIE['servidorCasa']?>/php/limpiarBandeja.php', type: 'POST'}).done(function(resp) {
		//console.log(resp)
		$('#overlay').css('display', 'none')
	});
});
function borrarExtra(id){
	$.idComprobante=id;
	$('#modalConfirmarBorrar').modal('show');
}
function borrarDefinitivamente(){
	$.ajax({url: 'php/borrarInterno.php', type: 'POST', data: { id: $.idComprobante }}).done(function(resp) {
		console.log(resp)
		if(resp=='ok'){
			location.reload();
		}
	});
}
function compartir(serie, correlativo){ 
	// Verificamos si el navegador tiene soporte para el API compartir
	if ('share' in navigator) {
		navigator.share({
			title: "Contenido",
			text: `Su Comprobante ${serie}-${correlativo} puede ser revisado online`,
			url: `./printComprobanteA4.php?serie=${serie}&correlativo=${correlativo}`
		})
		// Mensaje en Consola cuando se presiona el botón de compartir 
		.then(() => {
			console.log("Contenido Compartido!");
		})
		.catch(console.error);
	} else {
		// Si el navegador no tiene soporte para la API compartir, le enviamos un mensaje al usuario
		alert('Lo siento, este navegador no tiene soporte para recursos compartidos.')
	}
}

<?php } //Fin de CkPower ?>
</script>
<?php include "piePagina.php"; ?>
</body>
</html>