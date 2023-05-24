$('body').on( 'focusout','.esMoneda',function(){ 
	var campo = $(this);
	var valor =campo.val();
	if(valor<0){
		$(this).val('0.00')
	}else{
		$(this).val(parseFloat(valor).toFixed(2));
	}
});
$('.esDecimal').focusout(function(){
	var campo = $(this);
	var valor =campo.val();
	if(valor<0){
		$(this).val('0.00')
	}else{
		$(this).val(parseFloat(valor).toFixed(2));
	}
});
$("body").on('focus', 'input',function(){
  this.select();
});
$('.esGalon').change(function(){
	var campo = $(this);
	var valor =campo.val();
	if(valor<0){
		$(this).val('0.00')
	}else{
		$(this).val(parseFloat(valor).toFixed(3));
	}
});
$('.soloNumeros').keypress(function (e) {//|| 
	if( !(e.which >= 48 /* 0 */ && e.which <= 90 /* 9 */)  ) {
        e.preventDefault();
    }
});
$('#btnAddNewUser').click(function() {
	$('#modalListadoPersonal').modal('hide');
	$('#modalNuevoPersonal').modal('show');
});
function removerPersonal(idEmple){
	$.idEmple = idEmple;
	var nombre = $(`td[data-id="${idEmple}"]`).text();
	$('#strNombre').text(nombre);
	$('#modalListadoPersonal').modal('hide');
	$('#modalBorrarPersonal').modal('show');
}
$('#btnBorrarPersona').click(function() {
	$.ajax({url: 'php/borrarPersonal.php', type: 'POST', data: { idUser: $.idEmple }}).done(function(resp) {
		if($.trim(resp)=='todo ok'){
			location.reload();
		}
	});
});
$('#btnGuardarPersona').click(function() {
	$('#lblExito').addClass('d-none');
	$('#lblError').addClass('d-none');
	if( $('#txtDniPers').val()=='' || $('#txtNombrePers').val()=='' || $('#txtApellidoPers').val()=='' ){
		$('#lblError').removeClass('d-none').find('span').text('Debe rellenar todos los campos obligatorio');
	}else if( $('#sltFiltroNiveles').selectpicker('val') == null ){
		$('#lblError').removeClass('d-none').find('span').text('Debe rellenar un nivel');
	} else{
		$('#lblExito').addClass('d-none');
		$('#lblError').addClass('d-none');
		$.ajax({url: 'php/crearPersonal.php', type: 'POST', data: {nick: $('#txtNickPers').val(), apellido: $('#txtApellidoPers').val(), nombre: $('#txtNombrePers').val(), passw: $('#txtPassPers').val(), poder: $('#sltFiltroNiveles').selectpicker('val') }}).done(function(resp) {
			console.log(resp)
			if($.trim(resp)=='todo ok'){
				//$('#modalNuevoPersonal').modal('hide');
				$('#modalNuevoPersonal input').val('');
				$('#lblExito').removeClass('d-none').find('span').text('Registro guardado con éxito');
			}else{
				$('#lblError').removeClass('d-none').find('span').text('Hubo un error interno, comunícalo a soporte informático');
			}
			$('#modalNuevoPersonal').on('hidden.bs.modal', function () { 
				location.reload();
			});
		});
	}
});
$('#btnModificarUsuarios').click(function() {
	$('#modalListadoPersonal').modal('show');
});
$('#modalGuardadoExitoso').on('hidden.bs.modal', function () { 
	location.reload();
});
function pantallaOver(tipo) {
	if(tipo){$('#overlay').css('display', 'initial');}
	else{ $('#overlay').css('display', 'none'); }
}