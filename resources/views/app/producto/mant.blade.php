<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($producto, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('descripcion', 'Descripción:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('descripcion', null, array('class' => 'form-control input-xs', 'id' => 'name', 'placeholder' => 'Ingrese descripción')) !!}
		</div>
	</div>
	
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('precio_compra', 'Precio de compra:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('precio_compra', null, array('class' => 'form-control input-xs', 'id' => 'precio_compra', 'placeholder' => 'Precio de compra')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('precio_venta', 'Precio de venta:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('precio_venta', null, array('class' => 'form-control input-xs', 'id' => 'precio_venta', 'placeholder' => 'Precio de venta')) !!}
		</div>
	</div>
	
	@if($producto == null)
		<div class="form-group recarga" style="display:none;">
	@else
		@if($producto->recargable == 1)
			<div class="form-group recarga">
		@elseif($producto->recargable == 0)
			<div class="form-group recarga" style="display:none;">
		@endif
	@endif
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('precio_compra_envase', 'Precio de compra + envase:') !!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('precio_compra_envase', null, array('class' => 'form-control input-xs', 'id' => 'precio_compra_envase', 'placeholder' => 'Precio de compra + envase', 'style' => 'margin-top: 18px;')) !!}
		</div>
	</div>

	@if($producto == null)
		<div class="form-group recarga" style="display:none;">
	@else
		@if($producto->recargable == 1)
			<div class="form-group recarga">
		@elseif($producto->recargable == 0)
			<div class="form-group recarga" style="display:none;">
		@endif
	@endif
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('precio_venta_envase', 'Precio de venta + envase:') !!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('precio_venta_envase', null, array('class' => 'form-control input-xs', 'id' => 'precio_venta_envase', 'placeholder' => 'Precio de venta + envase', 'style' => 'margin-top: 18px;')) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('frecuente', 'Activo:', array('class' => 'col-sm-4 col-xs-12 control-label')) !!}
		<div class="col-sm-8 col-xs-12">
		
		@if($producto == null)
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
				<label class="form-check-label" for="frecuentesi">SI</label>
			</div>
			<div class="form-check form-check-inline">
				<input checked class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
				<label class="form-check-label" for="frecuenteno">NO</label>
			</div>
		@else
			@if($producto->frecuente == 1)
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
					<label class="form-check-label" for="frecuentesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
					<label class="form-check-label" for="frecuenteno">NO</label>
				</div>
			@elseif($producto->frecuente == 0)
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="frecuente" id="frecuentesi" value="1">
					<label class="form-check-label" for="frecuentesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="frecuente" id="frecuenteno" value="0">
					<label class="form-check-label" for="frecuenteno">NO</label>
				</div>
			@endif
		@endif
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('editable', 'Precio editable:', array('class' => 'col-sm-4 col-xs-12 control-label')) !!}
		<div class="col-sm-8 col-xs-12">
		
		@if($producto == null)
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
				<label class="form-check-label" for="editablesi">SI</label>
			</div>
			<div class="form-check form-check-inline">
				<input checked class="form-check-input" type="radio" name="editable" id="editableno" value="0">
				<label class="form-check-label" for="editableno">NO</label>
			</div>
		@else
			@if($producto->editable == 1)
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
					<label class="form-check-label" for="editablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="editable" id="editableno" value="0">
					<label class="form-check-label" for="editableno">NO</label>
				</div>
			@elseif($producto->editable == 0)
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="editable" id="editablesi" value="1">
					<label class="form-check-label" for="editablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="editable" id="editableno" value="0">
					<label class="form-check-label" for="editableno">NO</label>
				</div>
			@endif
		@endif
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('recargable', 'Recargable:', array('class' => 'col-sm-4 col-xs-12 control-label')) !!}
		<div class="col-sm-8 col-xs-12">
		
		@if($producto == null)
			<div class="form-check form-check-inline">
				<input class="form-check-input" type="radio" name="recargable" id="recargablesi" value="1">
				<label class="form-check-label" for="recargablesi">SI</label>
			</div>
			<div class="form-check form-check-inline">
				<input checked class="form-check-input" type="radio" name="recargable" id="recargableno" value="0">
				<label class="form-check-label" for="recargableno">NO</label>
			</div>
		@else
			@if($producto->recargable == 1)
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="recargable" id="recargablesi" value="1">
					<label class="form-check-label" for="recargablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="recargable" id="recargableno" value="0">
					<label class="form-check-label" for="recargableno">NO</label>
				</div>
			@elseif($producto->recargable == 0)
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="radio" name="recargable" id="recargablesi" value="1">
					<label class="form-check-label" for="recargablesi">SI</label>
				</div>
				<div class="form-check form-check-inline">
					<input checked class="form-check-input" type="radio" name="recargable" id="recargableno" value="0">
					<label class="form-check-label" for="recargableno">NO</label>
				</div>
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
	configurarAnchoModal('500');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$('.iCheck-helper').on('click', function(){

		var divpadre = $(this).parent();
		var input = divpadre.find('input');

		if( input.attr('id') == 'recargablesi' ){

			$(".recarga").css('display','');

		}else{

			$(".recarga").css('display','none');

		}

	});

}); 
</script>