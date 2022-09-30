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
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('pedido_id',$pedido->id,array('id'=>'pedido_id')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-6 col-md-6 col-sm-6 pago_sucursal">
			{!! Form::label('', 'Cobrar en sucursal:' ,array('class' => 'input-lg', 'style' => 'cursor: pointer;'))!!}
			<input name="pago_sucursal" type="checkbox" id="pago_sucursal" checked>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6 pago_repartidor">
			{!! Form::label('', 'Cobrar con repartidor:' ,array('class' => 'input-lg', 'style' => 'cursor: pointer;'))!!}
			<input name="pago_repartidor" type="checkbox" id="pago_repartidor">
		</div>
		<div class="col-lg-5 col-md-5 col-sm-5 sucursal">
			{!! Form::label('sucursal', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
			{!! Form::select('sucursal', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal' , 'onchange' => 'permisoRegistrar();')) !!}		
		</div><div class="col-lg-3 col-md-3 col-sm-3 sucursal"></div>
		<div class="col-lg-8 col-md-8 col-sm-8 repartidor" style="display:none;">
			@if(!empty($turnos_iniciados))
			<div id="empleados">
				@foreach($turnos_iniciados  as $key => $value)
					<div class="empleado" id="{{ $value->id}}">
						<img src="assets/images/empleado.png" style="width: 60px; height: 60px">
						<label class="empleado-label">{{ $value->person->nombres.' '.$value->person->apellido_pat.' '.$value->person->apellido_mat }}</label>
					</div>
				@endforeach
				{!! Form::hidden('repartidor',null,array('id'=>'repartidor')) !!}
				{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
			</div>
			@else
				<h4 style ="margin: 10px 0px; font-weight: 600; text-align: center; color: red;">NO HAY REPARTIDORES EN TURNO</h4>
			@endif
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4" style="margin-bottom: 30px;">
			<div  class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::label('metodopago_id', 'Método de pago:' ,array('class' => 'input-md'))!!}
				{!! Form::select('metodopago_id', $cboMetodoPago, null, array('class' => 'form-control input-sm', 'id' => 'metodopago_id')) !!}		
				{!! Form::text('metodopago', 'EFECTIVO', array('class' => 'form-control input-md', 'id' => 'metodopago', 'readOnly', 'style' => 'display: none;' )) !!}
			</div>
			<div  class="col-lg-12 col-md-12 col-sm-12">
				{!! Form::label('monto', 'Monto:' ,array('class' => 'input-md'))!!}
				{!! Form::number('monto', '', array('class' => 'form-control input-lg montos', 'id' => 'monto', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
				{!! Form::label('total', 'Total:' ,array('class' => 'input-md'))!!}
				{!! Form::text('total', number_format($saldo,2) , array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 30px;')) !!}
			</div>
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
	configurarAnchoModal('840');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	permisoRegistrar();
	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
}); 

$("#monto").keyup(function(){
	if( $("#monto").val() == ""){
		$('#total').val(saldo.toFixed(2));
	}else{ 
		if( is_numeric( $("#monto").val())){
			var monto = parseFloat($("#monto").val());
			var total = parseFloat($("#total").val());
			if(monto < 0 ||  monto > total){
				$("#monto").val("");
				$('#total').val(saldo.toFixed(2));
			}else{
				$("#total").val((total - monto).toFixed(2));
			}
		}else{
			$("#monto").val("");
			$('#total').val(saldo.toFixed(2));
		}
	}
}); 

$('.pago_sucursal .iCheck-helper').on('click', function(){
	if($(this).parent().hasClass('checked')) { 
		$(".empleado").css('background', 'rgb(255,255,255)');
		$(".repartidor").css('display','none');
		$(".sucursal").css('display','');
		$('#repartidor').val('');
		$("#metodopago").css('display', 'none');
		$("#metodopago_id").css('display', '');
		desmarcarPagoRepartidor();
	}else{
		$(this).parent().addClass('checked');
	}
});

$('.pago_repartidor .iCheck-helper').on('click', function(){
	if( $(this).parent().hasClass('checked')) { 
		$(".sucursal").css('display','none');
		$(".repartidor").css('display','');
		$("#metodopago_id").val('1');
		$("#metodopago").css('display', '');
		$("#metodopago_id").css('display', 'none');
		desmarcarPagoSucursal();
	}else{
		$(this).parent().addClass('checked');
	}
});

$(".empleado").on('click', function(){
	var idempleado = $(this).attr('id');
	$(".empleado").css('background', 'rgb(255,255,255)');
	$(this).css('background', 'rgb(179,188,237)');
	$('#repartidor').attr('value',idempleado);
});

function desmarcarPagoSucursal(){
	$('#pago_sucursal').parent().removeClass('checked');
	$('#pago_sucursal').prop('checked',false);
}

function desmarcarPagoRepartidor(){
	$('#pago_repartidor').parent().removeClass('checked');
	$('#pago_repartidor').prop('checked',false);
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function permisoRegistrar(){
	var aperturaycierre = null;
	$.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : $('#sucursal').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
		if(aperturaycierre == 0){
			$("#btnGuardar").prop('disabled',true);
			$("#monto").prop('disabled',true);
			$("#metodopago_id").prop('disabled',true);
			$('#divMensajeErrorMovimiento').html("");
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';
			$('#divMensajeErrorMovimiento').html(cadenaError);
		}else if(aperturaycierre == 1){
			$("#btnGuardar").prop('disabled',false);
			$("#monto").prop('disabled',false);
			$("#metodopago_id").prop('disabled',false);
			$('#divMensajeErrorMovimiento').html("");
		}
	});
}
</script>