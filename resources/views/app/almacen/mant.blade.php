<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($almacen, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('sucursal',null,array('id'=>'sucursal')) !!}
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('nombre', 'Nombre:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('nombre', null, array('class' => 'form-control input-xs', 'id' => 'nombre', 'placeholder' => 'Ingrese nombre')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
		{!! Form::label('cantidad_balones', 'Cant. Balones:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5">
			{!! Form::number('cantidad_balones', null, array('class' => 'form-control input-xs ', 'id' => 'cantidad_balones', 'placeholder' => 'Ingrese cantidad')) !!}
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
	configurarAnchoModal('500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	//SUCURSAL
	var sucursal = $('#sucursal_id').val();
	$('#sucursal').val(sucursal);
}); 
</script>