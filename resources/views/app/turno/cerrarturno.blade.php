<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimiento, $formData) !!}	
	<div class="form-group">
		{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
		{!! Form::hidden('sucursal',null,array('id'=>'sucursal')) !!}
		{!! Form::hidden('tipopago',null,array('id'=>'tipopago')) !!}
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('sucursal_id', 'Sucursal:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id', 'onchange' => 'generarNumeroCaja();')) !!}		
		</div>
	</div>
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('fecha', 'Fecha:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('fecha', '', array('class' => 'form-control input-xs', 'id' => 'fecha', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" onload="mueveReloj()">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('hora', 'Hora:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('hora', '', array('class' => 'form-control input-xs', 'id' => 'hora', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group">	
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('num_caja', 'Nro caja:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('num_caja', '', array('class' => 'form-control input-xs', 'id' => 'num_caja', 'readOnly')) !!}
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
	<!--div class="form-group">
		{!! Form::label('nombrepersona', 'Trabajador:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
		{!! Form::hidden('persona_id', null, array('id' => 'persona_id')) !!}
		<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::text('nombrepersona', null, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		</div>
	</div-->
	@if(empty($trabajadores_iniciados))
	<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;">TODOS LOS REPARTIDORES EN TURNO</h4>
	@else
	<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center;">SELECCIONE REPARTIDOR</h4>
	<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-x: scroll; border-style: groove;">
		@foreach($trabajadores_iniciados  as $key => $value)
			<div class="empleadomv" id="{{ $value->id}}" style="margin: 5px; width: 120px; height: 100px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;" >
				<img src="assets/images/empleado.png" style="width: 50px; height: 50px">
				<label style="font-size: 11px;  color: #2a3f54;">{{ $value->razon_social ? $value->razon_social : $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat}}</label>
			</div>
		@endforeach
		{!! Form::hidden('persona_id',null,array('id'=>'persona_id')) !!}
		{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
	</div>
	@endif
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('monto', 'Monto:')!!}
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('monto', '' , array('class' => 'form-control input-xs', 'id' => 'monto', 'readonly' => 'true')) !!}
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
			@if(empty($trabajadores_iniciados))
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'disabled' => 'true', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			@else
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this); actualizarTurnos();')) !!}
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

	// a continuacion creamos la fecha en la variable date
	var date = new Date()
	// Luego le sacamos los datos año, dia, mes 
	// y numero de dia de la variable date
	var año = date.getFullYear()
	var mes = date.getMonth()
	var ndia = date.getDate()
	//Damos a los meses el valor en número
	mes+=1;
	if(mes<10) mes="0"+mes;
	if(ndia<10) ndia="0"+ndia;
	//juntamos todos los datos en una variable
	var fecha = ndia + "/" + mes + "/" + año

	$('#fecha').val(fecha);

	//CONCEPTO
	$('#concepto').val('CIERRE DE TURNO DEL REPARTIDOR');
	$('#concepto_id').val(14);

	//NRO MOVIMIENTO
	$('#num_caja').val({{$num_caja}});

	//TIPO PAGO
	$('#tipopago').val(1);

	//TOTAL
	//$('#monto').val(0);

	//$('#monto').focus();

	generarNumeroCaja();

	mueveReloj();

}); 


function generarNumeroCaja(){

var num_caja = null;

var sucursal_id = $('#sucursal_id').val();

$.ajax({
	"method": "POST",
	"url": "{{ url('/turno/cargarnumerocaja') }}",
	"data": {
		"sucursal_id" : sucursal_id, 
		"_token": "{{ csrf_token() }}",
		}
}).done(function(info){
	num_caja = info;
}).always(function(){
	$('#num_caja').val(num_caja);
});

}

function actualizarTurnos(){
	setTimeout(function(){
		cargarRutaMenu('turno', 'container', '15');
	},3000);
}


function generarSaldoRepartidor(){

var saldo_repartidor = null;

var persona_id = $('#persona_id').val();

$.ajax({
	"method": "POST",
	"url": "{{ url('/turno/generarSaldoRepartidor') }}",
	"data": {
		"persona_id" : persona_id, 
		"_token": "{{ csrf_token() }}",
		}
}).done(function(info){
	saldo_repartidor = info;
}).always(function(){
	$('#monto').val(saldo_repartidor);
});

}



$(document).ready(function() {

	var personas = new Bloodhound({
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'trabajador/trabajadorautocompleting/%QUERY',
			filter: function (personas) {
				return $.map(personas, function (movie) {
					return {
						value: movie.value,
						id: movie.id,
						registro: movie.registro
					};
				});
			}
		}
	});
	personas.initialize();
	$('#nombrepersona').typeahead(null,{
		displayKey: 'value',
		source: personas.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$('#persona_id').val(datum.id);
	});

	$(".empleadomv").on('click', function(){
		var idempleado = $(this).attr('id');
		$(".empleadomv").css('background', 'rgb(255,255,255)');
		$(this).css('background', 'rgb(179,188,237)');
		$('#persona_id').attr('value',idempleado);
		$("#empleado_nombre").val($(this).children('label').html());
		generarSaldoRepartidor();
	});
}); 

	
/*Script del Reloj */
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


