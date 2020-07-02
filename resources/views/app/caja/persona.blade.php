@php
use App\Distrito;
use App\Provincia;
use App\Persona;
$documento = NULL;
$distrito_el = NULL;
$provincia_el = NULL;
$distritos = NULL;
$provincias = NULL;
$type = NULL;
$secondtype = NULL;
$comision = 0;
if (!is_null($persona)) {
	$documento = $persona->dni;
	if(is_null($documento) || trim($documento) == ''){
		$documento = $persona->ruc;
	}

	$user = Auth::user();
	$empresa_id = $user->empresa_id;

	$persona = Persona::where('empresa_id', '=', $empresa_id)
						->where('personamaestro_id', '=', $persona->id)->first();

	$type = $persona->type;
	$secondtype = $persona-> secondtype;
	
	if(!is_null($persona-> comision)){
		$comision = $persona-> comision;
	}
	
	$distrito_el = Distrito::find($persona->distrito_id);
	$provincia_el = Provincia::find($distrito_el->provincia_id);
	$distritos = Distrito::where('provincia_id','=',$provincia_el->id)->get();
	$provincias = Provincia::where('departamento_id','=',$provincia_el->departamento_id)->get();
}
@endphp
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($persona, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
{!! Form::hidden('personamaestro',null,  array('id' => 'personamaestro')) !!}

<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
	{!! Form::label('roles', 'Roles:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-4 col-xs-12">
		<input type="checkbox" id="cliente" name="cliente" value="C"><label for="cliente"> Cliente</label><br>
		<input type="checkbox" id="proveedor" name="proveedor" value="P"><label for="proveedor"> Proveedor</label><br>
		<input type="checkbox" class="trabajador" id="trabajador" name="trabajador" value="E"><label class="trabajador" for="trabajador"> Empleado</label>
	</div>
	<div id="comisionhtml" class="col-sm-4 col-xs-12">
	
	</div>
</div>

<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
	{!! Form::label('documento', 'N° Documento:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-4 col-xs-12">
		{!! Form::text('documento', $documento, array('class' => 'form-control input-xs', 'id' => 'documento', 'placeholder' => 'Ingrese N° Documento', 'maxlength' => '11')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('nombres', 'Nombres:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese Nombres', 'disabled' => 'disabled')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('apellidos', 'Apellidos:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('apellidos', null, array('class' => 'form-control input-xs', 'id' => 'apellidos', 'placeholder' => 'Ingrese Apellidos', 'disabled' => 'disabled')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	<div class="control-label col-lg-3 col-md-3 col-sm-3" style ="padding-top: 15px">
		{!! Form::label('razonsocial', 'Razón Social:') !!}<div class="" style="display: inline-block;color: red;">*</div>
	</div>
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('razonsocial', null, array('class' => 'form-control input-xs', 'id' => 'razonsocial', 'placeholder' => 'Ingrese Razon Social', 'disabled' => 'disabled')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	{!! Form::label('celular', 'Cel/Telf:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-5 col-xs-12">
		{!! Form::text('celular', null, array('class' => 'form-control input-xs', 'id' => 'celular', 'placeholder' => 'Ingrese Celular', 'maxlength' => '9')) !!}
	</div>

	<div class="col-sm-4 col-xs-12">
		{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese Telefono', 'maxlength' => '15')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	{!! Form::label('fechanacimiento', 'F. Nacimiento:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::date('fechanacimiento', null, array('class' => 'form-control input-xs', 'id' => 'fechanacimiento', 'placeholder' => 'Ingrese Fecha de Nacimiento')) !!}
	</div>
</div>
<div class="form-group col-xs-12">
	{!! Form::label('email', 'E-mail:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::email('email', null, array('class' => 'form-control input-xs', 'id' => 'email', 'placeholder' => 'Ingrese E-mail')) !!}
	</div>
</div>
<div class="form-group col-sm-12">
	{!! Form::label('direccion', 'Direccion:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese Direccion')) !!}
	</div>
</div>



<?php

use App\Departamento;

$departamentos = Departamento::all();


?>

<div class="form-group col-sm-12">
	{!! Form::label('departamento_id', 'Departamento:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		@if(!is_null($persona))
			<select id="departamento_id" name="departamento_id" class="form-control input-xs">
				<option disabled>SELECCIONE DEPARTAMENTO</option>
				@foreach ($departamentos as $departamento)
					@if($departamento->id == $provincia_el->departamento_id)
						<option value='{{ $departamento->id }}' selected> {{ $departamento->nombre }}</option>
					@else
						<option value='{{ $departamento->id }}'> {{ $departamento->nombre }}</option>
					@endif
				@endforeach
			</select>
		@else
			<select id="departamento_id" name="departamento_id" class="form-control input-xs">
				<option disabled selected>SELECCIONE DEPARTAMENTO</option>
				@foreach ($departamentos as $departamento)
					<option value='{{ $departamento->id }}'> {{ $departamento->nombre }}</option>
				@endforeach
			</select>
		@endif
		<i class="md md-place form-control-feedback l-h-34"></i>
	</div>
</div>


<div class="form-group col-sm-12">
	{!! Form::label('provincia_id', 'Provincia:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		@if(!is_null($persona))
			<select id="provincia_id" name="provincia_id" class="form-control input-xs">
				<option disabled>SELECCIONE PROVINCIA</option>
				@foreach ($provincias as $provincia)
					@if($provincia->id == $provincia_el->id)
						<option value='{{ $provincia->id }}' selected> {{ $provincia->nombre }}</option>
					@else
						<option value='{{ $provincia->id }}'> {{ $provincia->nombre }}</option>
					@endif
				@endforeach
			</select>
		@else
			<select id="provincia_id" name="provincia_id" class="form-control input-xs">
				<option disabled selected>SELECCIONE PROVINCIA</option>
			</select>
		@endif
		<i class="md md-place form-control-feedback l-h-34"></i>
	</div>
</div>

<div class="form-group col-sm-12">
	{!! Form::label('distrito_id', 'Distrito:', array('class' => 'col-sm-3 col-xs-12 control-label')) !!}
	<div class="col-sm-9 col-xs-12">
		@if(!is_null($persona))
			<select id="distrito_id" name="distrito_id" class="form-control input-xs">
				<option disabled selected>SELECCIONE DISTRITO</option>
				@foreach ($distritos as $distrito)
					@if($distrito->id == $distrito_el->id)
						<option value='{{ $distrito->id }}' selected> {{ $distrito->nombre }}</option>
					@else
						<option value='{{ $distrito->id }}'> {{ $distrito->nombre }}</option>
					@endif
				@endforeach
			</select>
		@else
			<select id="distrito_id" name="distrito_id" class="form-control input-xs">
				<option disabled selected>SELECCIONE DISTRITO</option>
			</select>
		@endif
		<i class="md md-place form-control-feedback l-h-34"></i>
	</div>
</div>





<div class="form-group">
	<div class="col-sm-12 text-right">
		{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarpersona()')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>

{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="documento"]').focus();
		configurarAnchoModal('600');
		$('#documento').on('change', function () {
			if(this.value.length===8){
				$('#nombres').removeAttr('disabled');
				$('#apellidos').removeAttr('disabled');
				$('#razonsocial').val('');
        		$('#razonsocial').attr('disabled','disabled');
				$('#nombres').focus();
			}else if(this.value.length===11){
				$('#razonsocial').removeAttr('disabled');
				$('#nombres').val('');
				$('#apellidos').val('');
				$('#nombres').attr('disabled','disabled');
				$('#apellidos').attr('disabled','disabled');
				$('#razonsocial').focus();
			}
    	});

		$("#documento").blur(function() {
			if($('#documento').val().length === 8){
				$('#nombres').removeAttr('disabled');
				$('#apellidos').removeAttr('disabled');
				$('#razonsocial').attr('disabled','disabled');
				$('#divMensajeErrorPersona').html("");
				$('#btnGuardar').prop('disabled',false);
				verificarpersona($('#documento').val(), 'dni', "cliente", "{{$accion}}");
			}else if($('#documento').val().length === 11){
				$('#razonsocial').removeAttr('disabled');
				$('#nombres').attr('disabled','disabled');
				$('#apellidos').attr('disabled','disabled');
				$('#divMensajeErrorPersona').html("");
				$('#btnGuardar').prop('disabled',false);
				verificarpersona($('#documento').val(),'ruc', "cliente", "{{$accion}}");
			}else{
				$('#divMensajeErrorPersona').html("");
				$('#razonsocial').attr('disabled','disabled');
				$('#nombres').attr('disabled','disabled');
				$('#apellidos').attr('disabled','disabled');
			}
		});
	}); 

function verificarpersona(documento, tipo, entidad, accion){
	var respuesta ="";
	if(accion == 0){
		var existe = null;
		var ajax = $.ajax({
			"method": "POST",
			"url": "{{ url('/verificarpersona') }}",
			"data": {
				"documento" : documento, 
				"tipo" : tipo, 
				"_token": "{{ csrf_token() }}",
				}
		}).done(function(info){
			respuesta = info;
			existe = respuesta.existe;
			console.log(existe)
		}).always(function(){
			if(existe == 0){
				
				cerrarModal();

				$('body').css('padding-right','0px')

				contadorModal  = 1;

				var url = "/caja/repetido/"+ respuesta.persona.id +"/SI";
				modalCaja( url , 'Registrar Persona');

			}else if(existe == 1){

				//RETORNAR MENSAJE DE ERROR DE DOCUMENTO REPETIDO

				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Ya tenemos una persona registrada con este documento.</li></ul></div>';

				$('#divMensajeErrorPersona').html("");
				$('#divMensajeErrorPersona').html(cadenaError);

				$('#btnGuardar').prop('disabled',true);

			}
		});
	}
	return respuesta;
}
</script>

<script>
$(document).ready(function(){  

	if($(".trabajador").is(':checked')) {
		$("#comisionhtml").html("");
		@if($comision == 0)
		$("#comisionhtml").html('<label for="comision" class="col-sm-12 col-xs-12">Comision:</label><div class="col-sm-12 col-xs-12"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="comision" id="comision1" value="1"><label class="form-check-label" for="comision1">SI</label></div><div class="form-check form-check-inline"><input checked class="form-check-input" type="radio" name="comision" id="comision2" value="0"><label class="form-check-label" for="comision2">NO</label></div></div>');
		@elseif($comision == 1)
		$("#comisionhtml").html('<label for="comision" class="col-sm-12 col-xs-12">Comision:</label><div class="col-sm-12 col-xs-12"><div class="form-check form-check-inline"><input class="form-check-input" checked type="radio" name="comision" id="comision1" value="1"><label class="form-check-label" for="comision1">SI</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="comision" id="comision2" value="0"><label class="form-check-label" for="comision2">NO</label></div></div>');
		@endif
	} else {  
		$("#comisionhtml").html("");
	}  

	$(".trabajador").click(function() {  
		if($(".trabajador").is(':checked')) {
			$("#comisionhtml").html("");
			@if($comision == 0)
			$("#comisionhtml").html('<label for="comision" class="col-sm-12 col-xs-12">Comision:</label><div class="col-sm-12 col-xs-12"><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="comision" id="comision1" value="1"><label class="form-check-label" for="comision1">SI</label></div><div class="form-check form-check-inline"><input checked class="form-check-input" type="radio" name="comision" id="comision2" value="0"><label class="form-check-label" for="comision2">NO</label></div></div>');
			@elseif($comision == 1)
			$("#comisionhtml").html('<label for="comision" class="col-sm-12 col-xs-12">Comision:</label><div class="col-sm-12 col-xs-12"><div class="form-check form-check-inline"><input class="form-check-input" checked type="radio" name="comision" id="comision1" value="1"><label class="form-check-label" for="comision1">SI</label></div><div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="comision" id="comision2" value="0"><label class="form-check-label" for="comision2">NO</label></div></div>');
			@endif
		} else {  
			$("#comisionhtml").html("");
		}  
	});  

});  
</script>

<script>
    $('#departamento_id').change(function(event){
		$.get("provincias/"+event.target.value+"",function(response, departamento){
			$('#provincia_id').empty();
			$("#provincia_id").append("<option disabled selected>SELECCIONE PROVINCIA</option>");
			for(i=0; i<response.length; i++){
				$("#provincia_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
			}
		}, 'json');
	});

	$('#provincia_id').change(function(event){
		$.get("distritos/"+event.target.value+"",function(response, provincia){
			$('#distrito_id').empty();
			$("#distrito_id").append("<option disabled selected>SELECCIONE DISTRITO</option>");
			for(i=0; i<response.length; i++){
				$("#distrito_id").append("<option value='"+response[i].id+"'> "+response[i].nombre+"</option>");
			}
		}, 'json');
	});
</script>