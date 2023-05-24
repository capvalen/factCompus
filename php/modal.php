

<div class="modal" id="modalNuevoPersonal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="icofont-plus-circle"></i> Agregar personal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<p>Nick</p>
				<input type="text" class="form-control" id="txtNickPers">
				<p>Nombres</p>
				<input type="text" class="form-control text-capitalize" id="txtNombrePers">
				<p>Apellidos</p>
				<input type="text" class="form-control text-capitalize" id="txtApellidoPers">
				<p>Contraseña</p>
				<input type="text" class="form-control" id="txtPassPers">
				<p>Nivel de acceso</p>
				<select class="selectpicker" data-live-search="false" id="sltFiltroNiveles" title="&#xed12; Filtro de Niveles">
					<option value="1">Administrador</option>
					<option value="2">Colaborador</option>
				</select>
      </div>
      <div class="modal-footer">
				<label for="" class="text-danger d-none" id="lblError"><i class="icofont-cat-alt-3"></i> <span></span></label>
				<label for="" class="text-success d-none" id="lblExito"><i class="icofont-fish-5"></i> <span></span></label>
        <button type="button" class="btn btn-outline-primary" id="btnGuardarPersona"><i class="icofont-save"></i> Guardar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modalListadoPersonal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="icofont-group"></i> Listado de personal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<div class="table-responsive">
					<button class="btn btn-outline-success mb-2" id="btnAddNewUser"><i class="icofont-plus-circle"></i> Agregar nuevo personal</button>
					<table class="table table-hover">
						<thead>
							<tr>
								<th>N°</th>
								<th>Apellidos y Nombres</th>
								<th>Nick</th>
								<th>Nivel</th>
								<th>@</th>
							</tr>
						</thead>
						<tbody>
							<?php require 'listarPersonal.php'; ?>
						</tbody>
					</table>
				</div>
      </div>
     
    </div>
  </div>
</div>
<div class="modal" id="modalBorrarPersonal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Borrar personal</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
				<p>¿Está seguro que desea borrar a: <strong id="strNombre"></strong>?</p>
      </div>
      <div class="modal-footer">				
        <button type="button" class="btn btn-outline-dark" data-dismiss="modal" id="btnCancelarBorrar"><i class="icofont-save"></i> Cancelar</button>
        <button type="button" class="btn btn-outline-danger" id="btnBorrarPersona"><i class="icofont-save"></i> Borrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalGuardadoExitoso" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
			<div class="text-center">
				<img src="images/path4585.png" alt="" class="img-fluid">
			</div>
        <h4 class="pt-2 deep-purple-text text-center">Guardado Exitósamente</h4>
				<h5 class="text-center text-muted" id="h5Detalle"></h5>
      </div>
      <div class="modal-footer">
				<p class="text-danger d-none" id="pError3"></p>
        <button type="button" class="btn btn-outline-success" data-dismiss="modal"><i class="icofont-toy-cat"></i> Ok</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalFaltaDatos" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body">
			<div class="text-center">
				<img src="images/sadfolder.png" alt="" class="img-fluid">
			</div>
        <h4 class="pt-2 deep-purple-text text-center">Faltan Datos</h4>
				<h5 class="text-center text-muted" id="h5DetalleFaltan"></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-success" data-dismiss="modal"><i class="icofont-toy-cat"></i> Ok</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal para: -->
<div class='modal fade' id='modalTransformacion' tabindex='-1'>
	<div class='modal-dialog modal-sm modal-dialog-centered'>
		<div class='modal-content'>
			<div class='modal-body'>
				<button type='button' class='close' data-dismiss='modal' aria-label='Close'> <span aria-hidden='true'>&times;</span></button>
				<h5 class='modal-title'>Convertir a...</h5>
				<p class="mb-0">Éste comprobante solo puede emitirse a:</p>
				<p class="mb-0"><strong class="text-primary" id="pConverir"></strong></p>
				<p class="">¿Deseas transformarlo?</p>
				<div class='d-flex justify-content-between'>
					<button type='button' class='btn btn-outline-secondary' data-dismiss="modal">No</button>
					<button type='button' class='btn btn-primary' onclick="transformar()" data-dismiss="modal">Sí</button>
				</div>
			</div>
		</div>
	</div>
</div>