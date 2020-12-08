<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($movimiento, $formData) !!}	
	<div class="form-group">
		{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
		{!! Form::hidden('sucursal',null,array('id'=>'sucursal')) !!}
		{!! Form::hidden('tipopago',null,array('id'=>'tipopago')) !!}
		{!! Form::hidden('persona_id', $persona_id,array('id'=>'persona_id')) !!}
	</div>

	@if($turnos_cerrados == "NO")
	<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;">EXISTEN REPARTIDORES EN TURNO</h4><h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: black;">DEBE CERRAR TODOS LOS TURNOS DE REPARTIDORES</h4>
	@endif

	@if($cant_cajas_sucursales_abiertas > 0 && $sucursal_id == 1)
	<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;">EXISTEN OTRAS CAJAS ABIERTAS</h4><h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: black;">DEBE CERRAR LA CAJA DE LAS OTRAS SUCURSALES E INGRESARLA EN LA CAJA PRINCIPAL</h4>
	@endif

	@if($sucursales_cerradas_no_ingresadas > 0 && $sucursal_id == 1)
	<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;">EXISTEN CAJAS CERRADAS DE OTRAS SUCURSALES NO INGRESADAS</h4><h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: black;">INGRESE EL MONTO DE LAS CAJAS CERRADAS DE OTRAS SUCURSALES EN LA PRINCIPAL</h4>
	@endif


	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('fecha', 'Fecha:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('fecha', '', array('class' => 'form-control input-xs', 'id' => 'fecha', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" onload="mueveReloj()">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('hora', 'Hora:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('hora', '', array('class' => 'form-control input-xs', 'id' => 'hora', 'readOnly')) !!}
		</div>
	</div>
	<div class="form-group" style="display:none;">		
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('num_caja', 'Nro:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
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
	<div class="form-group">
		<div class="control-label col-lg-4 col-md-4 col-sm-4" style ="padding-top: 15px">
			{!! Form::label('total', 'Total:')!!}
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			{!! Form::text('total', '', array('class' => 'form-control input-xs', 'id' => 'total', 'readOnly')) !!}
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
		@if( ($turnos_cerrados == "NO" || (  $sucursales_cerradas_no_ingresadas > 0 || $cant_cajas_sucursales_abiertas > 0 ) && $sucursal_id == 1))
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'disabled' => 'true', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		@else
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar','onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		@endif
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('400');
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
	$('#concepto').val('CIERRE DE CAJA');
	$('#concepto_id').val(2);

	//NRO MOVIMIENTO
	$('#num_caja').val({{$num_caja}});

	//TIPO PAGO
	$('#tipopago').val(1);

	//TOTAL
	var caja_efectivo = parseFloat($('#caja_efectivo').val());
	$('#total').val(caja_efectivo.toFixed(2));

	$('#comentario').focus();

	mueveReloj();

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


