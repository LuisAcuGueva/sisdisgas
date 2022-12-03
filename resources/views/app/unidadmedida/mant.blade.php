<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($unidadmedida, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('abreviatura', 'Abreviatura:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('abreviatura', null, array('class' => 'form-control input-xs', 'id' => 'abreviatura', 'placeholder' => 'Ingrese abreviatura', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('medida', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('medida', null, array('class' => 'form-control input-xs', 'id' => 'medida', 'placeholder' => 'Ingrese nombre', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('decimales', '¿Maneja decimales?') !!}
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9" style ="padding-top: 15px">
			@if($unidadmedida == null)
				{{ Form::radio('decimales', 'N' , true, array('id' => 'N')) }} NO
  				{{ Form::radio('decimales', 'S' , false, array('id' => 'Y')) }} SI
			@else
				@if($unidadmedida->decimal > 0)
					{{ Form::radio('decimales', 'N' , false, array('id' => 'N')) }} NO
					{{ Form::radio('decimales', 'S' , true, array('id' => 'Y')) }} SI
				@else
					{{ Form::radio('decimales', 'N' , true, array('id' => 'N')) }} NO
					{{ Form::radio('decimales', 'S' , false, array('id' => 'Y')) }} SI
				@endif
			@endif
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