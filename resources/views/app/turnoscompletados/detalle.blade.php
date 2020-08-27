<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($turno, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('page', 1, array('id' => 'page')) !!}
	{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
	{!! Form::hidden('turno_id', $turno->id, array('id' => 'turno_id')) !!}
	
	<div id="datos_turno" class="form-group">
		<div class="col-lg-3 col-md-3 col-sm-3 form-group">
			{!! Form::label('filas', 'Repartidor:')!!}
			{!! Form::text('trabajador', $turno->person->apellido_pat.' '.$turno->person->apellido_mat.' '.$turno->person->nombres, array('class' => 'form-control input-sm', 'id' => 'trabajador', 'readOnly')) !!}
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 form-group">
			{!! Form::label('filas', 'Fecha y hora de inicio:')!!}
			{!! Form::text('inicio', $fechaformato = date("d/m/Y h:i:s a",strtotime($turno->inicio))  , array('class' => 'form-control input-sm', 'id' => 'inicip', 'readOnly')) !!}
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 form-group">
			{!! Form::label('filas', 'Fecha y hora de fin:')!!}
			{!! Form::text('fin', $fechaformato = date("d/m/Y h:i:s a",strtotime($turno->fin))  , array('class' => 'form-control input-sm', 'id' => 'fin', 'readOnly')) !!}
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 form-group">
			<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 28px;">
				{!! Form::label('filas', 'Filas a mostrar:')!!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 23px;">
				{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
			</div>
		</div>
	</div>

	<div id="listado{{ $entidad }}">

	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="glyphicon glyphicon-print"></i> Reporte Turno Repartidor', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnReporte'.$entidad, 'onclick' => 'imprimirDetalle();')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('1400');
	buscar('{{ $entidad }}');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
}); 


function imprimirDetalle(){
	window.open("turnoscompletados/pdfDetalleTurno?turno_id="+$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="turno_id"]').val(),"_blank");
}
</script>