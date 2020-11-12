<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('pedido_id',$pedido->id,array('id'=>'pedido_id')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12 tipopago">
		<div class="col-lg-6 col-md-6 col-sm-6 sucursal">
			{!! Form::label('sucursal', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
			{!! Form::select('sucursal', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal' , 'onchange' => 'permisoRegistrar();generarSaldoCaja();')) !!}	
			<div class="form-group" style="height: 12px; margin: 25px 0px;">
				{!! Form::label('saldo_caja', 'Saldo en caja:', array('class' => 'col-lg-5 col-md-5 col-sm-5 control-label')) !!}
				<div class="col-lg-5 col-md-5 col-sm-5">
					{!! Form::text('saldo_caja', number_format(0, 2, '.', ''), array('style' => 'background-color: #c3ffd6 ;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'saldo_caja' )) !!}
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3"></div>
		<div class="col-lg-6 col-md-6 col-sm-6" style="margin-bottom: 30px;">
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				<div  class="col-lg-4 col-md-4 col-sm-4">
					<img src="assets/images/efectivo.png" style="width: 60px; height: 60px">
				</div>
				<div  class="col-lg-8 col-md-8 col-sm-8">
					{!! Form::text('monto', '', array('class' => 'form-control input-lg montos', 'id' => 'monto', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
				</div>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="margin-top: 10px;">
				{!! Form::label('total', 'Total:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
				{!! Form::text('total', number_format($saldo,2) , array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 30px; margin-top: 25px;')) !!}
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3"></div>
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
	permisoRegistrar();
	generarSaldoCaja();
	configurarAnchoModal('700');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$('.tipopago .iCheck-helper').on('click', function(){
		var divpadre = $(this).parent();
		var input = divpadre.find('input');
		if( input.attr('id') == 'pago_sucursal'){
			if( divpadre.hasClass('checked')) { 
				$(".empleado").css('background', 'rgb(255,255,255)');
				$(".repartidor").css('display','none');
				$(".sucursal").css('display','');
				$('#repartidor').val('');
			}
		}else if( input.attr('id') == 'pago_repartidor'){ //codigo_vale_subcafae
			if( divpadre.hasClass('checked')) { 
				$(".sucursal").css('display','none');
				$(".repartidor").css('display','');
			}
		}
	});

	$(".empleado").on('click', function(){
		var idempleado = $(this).attr('id');
		$(".empleado").css('background', 'rgb(255,255,255)');
		$(this).css('background', 'rgb(179,188,237)');
		$('#repartidor').attr('value',idempleado);
		$("#empleado_nombre").val($(this).children('label').html());
	});

	var saldo = {{ $saldo }} ;

	$("#monto").keyup(function(){
		if( $("#monto").val() == ""){
			$('#total').val(saldo.toFixed(2));
		}else{ 
			if( is_numeric( $("#monto").val())){
				var monto = parseFloat($("#monto").val());
				var total = parseFloat($("#total").val());
				if(monto < 0 ||  monto > saldo){
					$("#monto").val("");
					$('#total').val(saldo.toFixed(2));
				}else{
					$("#total").val((saldo - monto).toFixed(2));
				}
			}else{
				$("#monto").val("");
				$('#total').val(saldo.toFixed(2));
			}
		}
	}); 

}); 

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}


function permisoRegistrar(){

	var aperturaycierre = null;

	var sucursal_id = $('#sucursal').val();

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
	}).always(function(){
		if(aperturaycierre == 0){
			$("#btnGuardar").prop('disabled',true);
			$("#monto").prop('disabled',true);

			$('#divMensajeErrorMovimiento').html("");

			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';

			var surcursal_id = $('#sucursal_id').val();

			if(sucursal_id != null){
				$('#divMensajeErrorMovimiento').html(cadenaError);
			}

		}else if(aperturaycierre == 1){
			$("#btnGuardar").prop('disabled',false);
			$("#monto").prop('disabled',false);

			$('#divMensajeErrorMovimiento').html("");

		}
	});

	return aperturaycierre;
}

function generarSaldoCaja(){
	var saldocaja = null;

	var saldo = {{ $saldo }} ;

	var sucursal_id = $('#sucursal').val();

	var serieajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/caja/saldoCaja') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		saldocaja = info;
	}).always(function(){
		$('#saldo_caja').val(saldocaja);
		var total = $('#total').val();
		if(saldocaja < total){
			$("#monto").keyup(function(){
				if( $("#monto").val() == ""){
					$('#total').val(saldo.toFixed(2));
				}else{ 
					if( is_numeric( $("#monto").val())){
						var monto = parseFloat($("#monto").val());
						var saldo_caja = parseFloat($("#saldo_caja").val());
						if(monto > saldo_caja){
							$("#monto").val("");
							$('#total').val(saldo.toFixed(2));
						}
					}else{
						$("#monto").val("");
						$('#total').val(saldo.toFixed(2));
					}
				}
			}); 
		}
	});
}
</script>