<style>
	.empleadomv{
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
	#empleados_mant{
		margin: 10px 0px; 
		border-style: groove;
		width: 100%; 
		display: -webkit-inline-box; 
		overflow-x: scroll; 
	}
	.repartidor-title{
		padding-top: 10px; 
		font-weight: 600; 
		text-align: center;
	}
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimiento, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('sucursal_id', 'Sucursal:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'style' => 'margin-top:8px;', 'id' => 'sucursal_id', 'onchange' => 'cambiarSucursal();')) !!}		
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('fecha', 'Fecha:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('fecha', '', array('class' => 'form-control input-xs', 'style' => 'margin-top:8px;', 'id' => 'fecha', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" onload="mueveReloj()">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('hora', 'Hora:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('hora', '', array('class' => 'form-control input-xs', 'style' => 'margin-top:8px;', 'id' => 'hora', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group">	
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('num_caja', 'Nro caja:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('num_caja', '', array('class' => 'form-control input-xs', 'style' => 'margin-top:8px;', 'id' => 'num_caja', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" style="display:none;">	
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('concepto', 'Concepto:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('concepto', '', array('class' => 'form-control input-xs', 'id' => 'concepto', 'readOnly')) !!}
			{!! Form::hidden('concepto_id',null,array('id'=>'concepto_id')) !!}
		</div>
	</div>
	<h4 class="repartidor-title"></h4>
	<div id="empleados_mant"></div>
	{!! Form::hidden('persona_id',null,array('id'=>'persona_id')) !!}
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('totalrepartidor', 'Saldo:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('totalrepartidor', '' , array('class' => 'form-control input-xs', 'style' => 'margin-top:8px;', 'id' => 'totalrepartidor', 'readonly' => 'true')) !!}
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('monto', 'Monto:')!!}<div class="" style="display: inline-block; color: red;">*</div>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('monto', '' , array('class' => 'form-control input-xs', 'style' => 'margin-top:8px;', 'id' => 'monto')) !!}
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
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'disabled' => 'true', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
	
	//CONCEPTO
	$('#concepto').val('INGRESO DE PEDIDOS DEL REPARTIDOR');
	$('#concepto_id').val(13);

	generarFecha();
	mueveReloj();
	cambiarSucursal();
	clickRepartidor();
}); 

$("#monto").keyup(function(){
	var monto = parseFloat($("#monto").val());
	var saldo = parseFloat($("#totalrepartidor").val());
	if( !is_numeric(monto) ){
		$("#monto").val("");
		return false;
	}else{
		if(monto > saldo){
				$("#monto").val("");
		}
	}
	if( monto <= 0){
		$('#btnGuardar').prop('disabled', true);
		var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
		cadenaError += '<li>El monto a ingresar debe ser mayor a 0.</li></ul></div>';
		$('#divMensajeErrorTurnorepartidor').html(cadenaError);
	}else{
		$('#btnGuardar').prop('disabled', false);
		$('#divMensajeErrorTurnorepartidor').html("");
	}
});

function clickRepartidor(){
	$(".empleadomv").on('click', function(){
		var idempleado = $(this).attr('id');
		$(".empleadomv").css('background', 'rgb(255,255,255)');
		$(this).css('background', 'rgb(179,188,237)');
		$('#persona_id').attr('value',idempleado);
		generarSaldoRepartidor();
	});
}

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

function cambiarSucursal(){
	generarNumeroCaja();
	generarEmpleados();
	permisoRegistrar();
}

function permisoRegistrar(){
	var aperturaycierre = null;
	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
		if(aperturaycierre == 0){
			$("#btnGuardar").prop('disabled',true);
			$("#comentario").prop('disabled',true);
			$("#monto").prop('disabled',true);
			$("#saldo_caja").val('');
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';
			$('#divMensajeErrorTurnorepartidor').html(cadenaError);
		}else if(aperturaycierre == 1){
			$("#btnGuardar").prop('disabled',false);
			$("#comentario").prop('disabled',false);
			$("#monto").prop('disabled',false);
			$('#divMensajeErrorTurnorepartidor').html("");
		}
	});
	return aperturaycierre;
}

function generarNumeroCaja(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/turno/cargarnumerocaja') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(num_caja){
		$('#num_caja').val(num_caja);
	});
}

function generarEmpleados(){
	$('#persona_id').val('');
	var empleados = null;
	var tabla = "";
	$.ajax({
		"method": "POST",
		"url": "{{ url('/turno/cargarempleados') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		empleados = info;
		if( empleados != ""){
			$('.repartidor-title').html("SELECCIONE REPARTIDOR");
			$('.repartidor-title').css("color","#2a3f54");
			$('#empleados_mant').css('display', '');
		}else{
			$('.repartidor-title').html("NINGÚN REPARTIDOR EN TURNO");
			$('.repartidor-title').css("color","red");
			$('#empleados_mant').css('display', 'none');
		}
		$.each(empleados, function(i, item) {
			tabla =  tabla +'<div class="empleadomv" id="' + item.id + '"><img src="assets/images/empleado.png" style="width: 60px; height: 60px"><label class="empleado-label">' + item.nombres + ' ' + item.apellido_pat  + ' ' + item.apellido_mat +'</label></div>';   
		});
		$('#empleados_mant').html(tabla);
		clickRepartidor();
	});
}

function generarSaldoRepartidor(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/turno/generarSaldoRepartidor') }}",
		"data": {
			"persona_id" : $('#persona_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		$('#totalrepartidor').val(info);
	});
}

function mueveReloj() {
	marcacion = new Date()
	Hora = marcacion.getHours()
	Minutos = marcacion.getMinutes()
	Segundos = marcacion.getSeconds()
	/*variable para el apóstrofe de am o pm*/
	if (Hora < 12) {
		dn = "a.m"
	}else{
		dn = "p.m"
		Hora = Hora - 12
	}
	if (Hora == 0)
	Hora = 12
	/* Si la Hora, los Minutos o los Segundos son Menores o igual a 9, le añadimos un 0 */
	if (Hora <= 9) Hora = "0" + Hora
	if (Minutos <= 9) Minutos = "0" + Minutos
	if (Segundos <= 9) Segundos = "0" + Segundos
	/* Termina el Script del Reloj */
	horaImprimible = Hora + ":" + Minutos + ":" + Segundos + " " + dn
	$('#hora').val(horaImprimible);
	//La función se tendrá que llamar así misma para que sea dinámica, 
	//de esta forma:
	setTimeout(mueveReloj,1000)
}
</script>