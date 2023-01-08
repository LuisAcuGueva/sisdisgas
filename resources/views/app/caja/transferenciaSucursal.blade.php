<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimiento, $formData) !!}	
	<div class="form-group">
		{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
		{!! Form::hidden('sucursal',null,array('id'=>'sucursal')) !!}
	</div>

	<div class="col-lg-6 col-md-6">
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('sucursal_origen', 'Origen:')!!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('sucursal_origen', $sucursal_origen, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_origen')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('saldo_caja_origen', 'Saldo en caja:') !!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('saldo_caja_origen', number_format(0, 2, '.', ''), array('style' => 'background-color: #c3ffd6 ;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'saldo_caja_origen' )) !!}
			</div>
		</div>
	</div>

	<div class="col-lg-6 col-md-6">
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('sucursal_destino', 'Destino:')!!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('sucursal_destino', $sucursal_destino, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_destino')) !!}
			</div>
		</div>
		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('saldo_caja_destino', 'Saldo en caja:') !!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('saldo_caja_destino', number_format(0, 2, '.', ''), array('style' => 'background-color: #c3ffd6 ;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'saldo_caja_destino' )) !!}
			</div>
		</div>
	</div>

	<div class="col-md-12 separator">
		
		<div align="center" class="">
			<h4>Tranferencia de efectivo</h4>
		</div>
		<div class="form-group" onload="mueveReloj()">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('hora', 'Hora:')!!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('hora', '', array('class' => 'form-control input-xs', 'id' => 'hora', 'readOnly')) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('total_transferir', 'Monto:')!!}<div class="" style="display: inline-block;color: red;">*</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::number('total_transferir', '' , array('class' => 'form-control input-xs', 'id' => 'total_transferir')) !!}
			</div>
		</div>

		<div class="form-group">
			<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
				{!! Form::label('comentario', 'Comentario:')!!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<textarea class="form-control input-xs" id="comentario" cols="10" rows="5" name="comentario"></textarea>
			</div>
		</div>

	</div>
	
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			<!-- @if(empty($trabajadores_sinturno)) -->
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			<!-- @else
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			@endif -->
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('800');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	//SUCURSAL
	var sucursal = $('#sucursal_id').val();
	$('#sucursal').val(sucursal);

	generarSaldoCaja1();
	generarSaldoCaja2();

	mueveReloj();
}); 

$("#total_transferir").keyup(function(){
	var total = parseFloat($("#total_transferir").val());
	var saldo_caja_origen = parseFloat($("#saldo_caja_origen").val());
	if( !is_numeric(total) ){
		$("#total_transferir").val("");
		return false;
	}else{
		if(total > saldo_caja_origen){
			$("#total_transferir").val("");
		}
	}
	if( total <= 0){
		$('#btnGuardar').prop('disabled', true);
		var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
		cadenaError += '<li>El monto a ingresar debe ser mayor a 0.</li></ul></div>';
		$('#divMensajeErrorTurnorepartidor').html(cadenaError);
	}else{
		$('#btnGuardar').prop('disabled', false);
		$('#divMensajeErrorTurnorepartidor').html("");
	}
}); 

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function generarSaldoCaja1(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/caja/saldoCaja') }}",
		"data": {
			"sucursal_id" : $('#sucursal_origen').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(data){
		$('#saldo_caja_origen').val(parseFloat(data).toFixed(2));
	});
}

function generarSaldoCaja2(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/caja/saldoCaja') }}",
		"data": {
			"sucursal_id" : $('#sucursal_destino').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(data){
		$('#saldo_caja_destino').val(parseFloat(data).toFixed(2));
	});
}

function mueveReloj() {
	marcacion = new Date()
	Hora = marcacion.getHours()
	Minutos = marcacion.getMinutes()
	Segundos = marcacion.getSeconds()
	if (Hora < 12) {
		dn = "a.m"
	}else{
		dn = "p.m"
		Hora = Hora - 12
	}
	if (Hora == 0)
	Hora = 12
	if (Hora <= 9) Hora = "0" + Hora
	if (Minutos <= 9) Minutos = "0" + Minutos
	if (Segundos <= 9) Segundos = "0" + Segundos
	horaImprimible = Hora + ":" + Minutos + ":" + Segundos + " " + dn
	$('#hora').val(horaImprimible);
	setTimeout(mueveReloj,1000)
}
</script>


