<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($sucursal, $formData) !!}	
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
		{!! Form::label('direccion', 'Direccion:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese direccion', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('telefono', 'Telefono:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese telefono', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-5 col-md-5 col-sm-5" style ="padding-top: 15px">
		{!! Form::label('cant_balon_normal', 'Cant. Bal. Normal:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-7 col-md-7 col-sm-7">
			{!! Form::text('cant_balon_normal', null, array('class' => 'form-control input-xs', 'id' => 'cant_balon_normal', 'placeholder' => 'Ingrese cant. bal. normal', 'style' => 'margin-top: 10px;')) !!}
		</div>
	</div>
	<div class="form-group">
	<div class="control-label col-lg-5 col-md-5 col-sm-5" style ="padding-top: 15px">
		{!! Form::label('cant_balon_premium', 'Cant. Bal. Premium:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-7 col-md-7 col-sm-7">
			{!! Form::text('cant_balon_premium', null, array('class' => 'form-control input-xs', 'id' => 'cant_balon_premium', 'placeholder' => 'Ingrese cant. bal. premium', 'style' => 'margin-top: 10px;')) !!}
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