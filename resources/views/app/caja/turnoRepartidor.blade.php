<style>
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
		-webkit-appearance: none; 
		margin: 0; 
	}
	input[type=number] { -moz-appearance:textfield; }
	.empleado{
		cursor: pointer;
		margin: 5px; 
		width: 120px; 
		height: 110px; 
		text-align: center; 
		border-style: solid; 
		border-color: #2a3f54; 
		border-radius: 10px;
	}
	.empleado-label{
		cursor: pointer;
		vertical-align: middle;
		font-size: 12px; 
		color: #2a3f54;
	}
	#empleados{
		margin: 10px 0px; 
		border-style: groove;
		width: 100%; 
		display: -webkit-inline-box; 
		overflow-x: scroll; 
	}
	.empleado-title{
		margin: 10px 0px; 
		font-weight: 600; 
		text-align: center;
	}
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimiento, $formData) !!}	
	<div class="form-group">
		{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
		{!! Form::hidden('sucursal',null,array('id'=>'sucursal')) !!}
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4">
			{!! Form::label('fecha', 'Fecha:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('fecha', '', array('class' => 'form-control input-xs', 'id' => 'fecha', 'readOnly')) !!}
		</div>
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
			{!! Form::label('num_caja', 'Nro:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('num_caja', $num_caja, array('class' => 'form-control input-xs', 'id' => 'num_caja', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" style="display:none;">	
		<div class="control-label col-lg-4 col-md-4 col-sm-4">
			{!! Form::label('concepto', 'Concepto:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('concepto', '', array('class' => 'form-control input-xs', 'id' => 'concepto', 'readOnly')) !!}
			{!! Form::hidden('concepto_id',null,array('id'=>'concepto_id')) !!}
		</div>
	</div>
	@if(empty($trabajadores_sinturno))
		<h4 class="empleado-title" style ="color: red;">TODOS LOS REPARTIDORES EN TURNO</h4>
	@else
		<h4 class="empleado-title">SELECCIONE REPARTIDOR</h4>
		<div id="empleados">
			@foreach($trabajadores_sinturno  as $key => $value)
				<div class="empleado" id="{{ $value->id}}">
					<img src="assets/images/empleado.png" style="width: 60px; height: 60px">
					<label class="empleado-label">{{ $value->razon_social ? $value->razon_social : $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat}}</label>
				</div>
			@endforeach
			{!! Form::hidden('persona_id',null,array('id'=>'persona_id')) !!}
		</div>
	@endif
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4">
			{!! Form::label('saldo_caja', 'Saldo en caja:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('saldo_caja', number_format(0, 2, '.', ''), array('style' => 'background-color: #c3ffd6;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'saldo_caja' )) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4">
			{!! Form::label('total', 'Monto:')!!}<div class="" style="display: inline-block;color: red;">*</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::number('total', '' , array('class' => 'form-control input-xs', 'id' => 'total')) !!}
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
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			@if(empty($trabajadores_sinturno))
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'disabled' => 'true', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			@else
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			@endif
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	//SUCURSAL
	var sucursal = $('#sucursal_id').val();
	$('#sucursal').val(sucursal);

	generarSaldoCaja();
	generarFecha();

	//VUELTO AL INICIAR TURNO DEL REPARTIDOR
	$('#concepto_id').val(15);
	
	//TOTAL
	$('#total').focus();
	mueveReloj();
}); 

$("#total").keyup(function(){
	var total = parseFloat($("#total").val());
	var caja_efectivo = parseFloat($("#caja_efectivo").val());
	if( !is_numeric(total) ){
		$("#total").val("");
		return false;
	}else{
		if(total > caja_efectivo){
				$("#total").val("");
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

$(".empleado").on('click', function(){
	var idempleado = $(this).attr('id');
	$(".empleado").css('background', 'rgb(255,255,255)');
	$(this).css('background', 'rgb(179,188,237)');
	$('#persona_id').attr('value',idempleado);
});

function generarFecha(){
	var date = new Date();
	var anio = date.getFullYear();
	var mes = date.getMonth();
	var ndia = date.getDate();
	mes+=1;
	if(mes<10) mes="0"+mes;
	if(ndia<10) ndia="0"+ndia;
	var fecha = ndia + "/" + mes + "/" + anio;
	$('#fecha').val(fecha);
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function generarSaldoCaja(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/caja/saldoCaja') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(data){
		$('#saldo_caja').val(data);
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


