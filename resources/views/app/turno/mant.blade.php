<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($concepto, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
			{!! Form::label('concepto', 'Concepto:') !!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-9 col-md-9 col-sm-9">
			{!! Form::text('concepto', null, array('class' => 'form-control input-xs', 'id' => 'concepto', 'placeholder' => 'Ingrese concepto')) !!}
		</div>
	</div>
	<div class="form-group">
		{!! Form::label('tipo', 'Tipo:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
		<div class="col-lg-9 col-md-9 col-sm-9">
			@if($concepto == null)
				<select id="tipom" name="tipom" class="form-control input-xs">
					<option value="0" selected>INGRESO</option>
					<option value="1">EGRESO</option>
				</select>
			@elseif($concepto->tipo == 0)
				<select id="tipom" name="tipom" class="form-control input-xs">
					<option value="0" selected>INGRESO</option>
					<option value="1">EGRESO</option>
				</select>
			@elseif($concepto->tipo == 1)
				<select id="tipom" name="tipom" class="form-control input-xs">
					<option value="0">INGRESO</option>
					<option value="1" selected>EGRESO</option>
				</select>
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
	configurarAnchoModal('450');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>