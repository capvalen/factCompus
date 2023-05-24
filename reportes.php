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
	<title>Facturador electrónico</title>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="icofont.min.css">
	<link rel="stylesheet" href="css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/anksunamun.css">
	<link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="css/tableexport.min.css">
	<link rel="shortcut icon" href="images/VirtualCorto.png" type="image/png">


</head>
<body>
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
<?php include 'menu-wrapper.php'; ?>

<section class="mt-3">
	<div class="container-fluid  px-5">
		<div class="row">
		<div class="col-md-3">
			<center><img src="<?= $_COOKIE['logo'];?>" class='img-fluid mt-3 w-75'></center>
		</div>
		<div class="col ml-4">
			<h3 class="display-4">Reportes</h3>
			<small class="text-muted">Usuario: <?= strtoupper($_COOKIE['ckAtiende']); ?></small>
		</div></div>
		
		<div class="row pt-3">
			<label class="pt-2"><strong>Filtros:</strong></label>
			<div class="col-12 col-md-4">
				<div class="input-daterange input-group" id="datepicker">
					<input type="text" class="form-control input-sm" name="start" id="txtFecha1" />
					<span class="input-group-addon pt-2 px-2">hasta</span>
					<input type="text" class="form-control input-sm" name="end" id="txtFecha2" />
				</div>
			</div>
			<div class="col">
				<select class="selectpicker" data-live-search="false" id="sltFiltroReporte" title="&#xed12; Tipo de reporte">
					<option value="0">Resumido</option>
					<option value="1">Contable</option>
					<!-- <option value="2">Kardex</option> -->
					<option value="3">Detallado</option>
				</select>
				<select class="selectpicker" data-live-search="false" id="sltFiltroProducto" title="&#xed12; Productos">
					<?php 
					$sqlProd="SELECT `idProductos`, `prodDescripcion`, p.`idUnidad`, `prodPrecio`, `prodActivo`, u.undDescipcion
					FROM `productos` p inner join unidades u on u.idUnidad = p.idUnidad 
					WHERE `prodActivo`=1 and idProductos<>0";
					$resultadoProd=$cadena->query($sqlProd);
					while($rowProd=$resultadoProd->fetch_assoc()){ ?>
					<option value="<?= $rowProd['idProductos'];?>"><?= $rowProd['prodDescripcion'];?></option>
				
					<?php 
					}	
						?>
				</select>
				<button class="btn btn-outline-primary ml-3" id="btnBuscarReporte"><i class="icofont-search-2"></i></button>
				<button class="btn btn-outline-success ml-3 d-none" id="btnGuardarReporte"><i class="icofont-file-excel"></i> Guardar reporte</button>
			</div>
		</div>
		

		<div class="table-responsive">
			<table class="table table-hover mt-3" id="tablaCabeceras" >
				<thead>
					<tr>
						<th data-sort="int"><i class="icofont-expand-alt"></i> N°</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Tipo</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Correlativo</th>
						<th data-sort="int"><i class="icofont-expand-alt"></i> Fecha y Hora</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Cliente</th>
						<th data-sort="float"><i class="icofont-expand-alt"></i> I.G.V.</th>
						<th data-sort="float"><i class="icofont-expand-alt"></i> Monto</th>
						<th data-sort="string"><i class="icofont-expand-alt"></i> Estado</th>
						<th>@</th>
					</tr>
				</thead>
				<tbody>
			
				</tbody>
			</table>
		</div>
		<div class="d-none table-responsive" id="divTablaSysCont"></div>

	</div>
</section>











<?php include "php/modal.php"; ?>

<script src="js/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/impotem.js?version=1.0.7"></script>
<script src="js/moment.js"></script>
<script src="js/bootstrap-select.js"></script>
<script src="js/stupidtable.js"></script>
<script src="js/bootstrap-datepicker.js?version=1.0.1"></script>
<script src="js/xlsx.core.min.js"></script>
<script src="js/FileSaver.min.js"></script>
<script src="js/tableexport.js?version=1.1"></script>

<script>
$(document).ready(function(){
	$('.selectpicker').selectpicker('render');
	$('.selectpicker').selectpicker('val', -1);
	$('#sltFiltroReporte').selectpicker('val',"0");
	$('table').stupidtable();
	/* $.ajax({url: 'php/getPreciosProductos.php', type: 'POST' }).done(function(resp) {
		//console.log(resp)
		$.precios = JSON.parse(resp);
		console.log( $.precios );
	}); */
	
	$('[data-toggle="tooltip"]').tooltip();
	$('.input-daterange').datepicker({
		format: "dd/mm/yyyy",
		todayBtn: "linked",
    language: "es",
		autoclose: true
	});
	$('.input-daterange input').val(moment().format('DD/MM/YYYY'));
});
$('.input-daterange input').change(function(){
	$('#btnGuardarReporte').addClass('d-none');
})
/* $('#btnEmitirBoleta').click(function() {
	$.ajax({url: 'emision.php', type: 'POST', data: { emitir: 3, factura: $('#txtCodigoFact').val() }}).done(function(resp) {
		console.log(resp)
		if(resp=='fin'){
			$('#modalArchivoBien').modal('show');
		}
	});
}); */
$('#btnBuscarReporte').click(function() {
	$('#tablaCabeceras').removeClass('d-none');
	$('#divTablaSysCont').addClass('d-none');
	if( $('#txtFecha1').val()!='' &&  $('#txtFecha2').val()!=''){
		

		switch ($('#sltFiltroReporte').selectpicker('val')) {
			case "0":
				var fecha1 = moment($('#txtFecha1').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				var fecha2 = moment($('#txtFecha2').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				$.ajax({url: 'php/listarTodoPorFecha.php', type: 'POST', data:{fecha: fecha1, fecha2: fecha2 } }).done(function(resp) {
					$('#tablaCabeceras tbody').children().remove();
					$('#tablaCabeceras tbody').append(resp).anotherJqueryMethod;
				});
				break;
			case "1":
				$('#divTablaSysCont').removeClass('d-none');
				$('#tablaCabeceras').addClass('d-none');
				var fecha1 = moment($('#txtFecha1').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				var fecha2 = moment($('#txtFecha2').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				$.ajax({url: 'php/reporteSysConta.php', type: 'POST', data:{fecha1: fecha1, fecha2: fecha2 } }).done(function(resp) {
					$('#divTablaSysCont').children().remove();
					$('#divTablaSysCont').append(resp).anotherJqueryMethod;
				});
				break;
			case "2":
				$('#divTablaSysCont').removeClass('d-none');
				$('#tablaCabeceras').addClass('d-none');
				$.ajax({url: 'php/reporteKardex.php', type: 'POST', data: { }}).done(function(resp) { console.log( resp );
					$('#divTablaSysCont').children().remove();
					$('#divTablaSysCont').append(resp).anotherJqueryMethod;
				});
			break;
			case '3':
				$('#divTablaSysCont').removeClass('d-none');
				$('#tablaCabeceras').addClass('d-none');
				var fecha1 = moment($('#txtFecha1').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				var fecha2 = moment($('#txtFecha2').val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
				$.ajax({url: 'php/reporteC34.php', type: 'POST', data: {fecha: fecha1, fecha2: fecha2 }}).done(function(resp) { console.log( resp );
					$('#divTablaSysCont').children().remove();
					$('#divTablaSysCont').append(resp).anotherJqueryMethod;
				});
			break;
			default:
				break;
		}
		$('[data-toggle="tooltip"]').tooltip();
		$('#btnGuardarReporte').removeClass('d-none');

	}
});


$('#btnGuardarReporte').click(function() {
	console.log( $('#sltFiltroReporte').selectpicker('val') );
	switch ($('#sltFiltroReporte').selectpicker('val')) {
		case "0":
			var instance = new TableExport(document.getElementById('tablaCabeceras'), {
				formats: ['xlsx'],
				exportButtons: false,
				sheetname: "Reporte resumido"
			});
			var exportData = instance.getExportData()['tablaCabeceras']['xlsx'];
			instance.export2file(exportData.data, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Reporte resumido', exportData.fileExtension);
			
			

		break;
		case "1":
		case '3':
			console.log( 'pido' );
			var instance = new TableExport(document.getElementById('tablaSysConta'), {
				formats: ['xlsx'],
				exportButtons: false,
				sheetname: "Reporte contable"
			});
			var exportData = instance.getExportData()['tablaSysConta']['xlsx'];
			instance.export2file(exportData.data, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'Reporte contable', exportData.fileExtension);
		break;
		default:
		break;

	}
	
});

</script>

</body>
</html>