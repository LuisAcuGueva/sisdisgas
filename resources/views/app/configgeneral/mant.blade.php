<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($configgeneral, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('nombre', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('descripcion', 'Descripción:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::textarea('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'descripcion', 'placeholder' => 'Ingrese descripcion', 'rows' => '3', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('valor', 'Valor:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('valor', null, array('class' => 'form-control input-xs', 'id' => 'valor', 'placeholder' => 'Ingrese valor', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('470');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>