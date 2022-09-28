<style>
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
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> {{ $title }}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					@if(!empty($turnos_iniciados))
						<div id="empleados">
							@foreach($turnos_iniciados  as $key => $value)
								<div class="empleado" id="{{ $value->id}}">
									<img src="assets/images/empleado.png" style="width: 60px; height: 60px">
									<label class="empleado-label">{{ $value->person->nombres.' '.$value->person->apellido_pat.' '.$value->person->apellido_mat }}</label>
								</div>
							@endforeach
							{!! Form::hidden('turno_id',null,array('id'=>'turno_id')) !!}
							{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
						</div>
					@else
						<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;"> NO HAY REPARTIDORES EN TURNO</h4>
					@endif
				</div>
				<div class="form-group">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Dar monto para vuelto', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["vuelto"], array('listar'=>'SI')).'\', \''.$tituloMontoVuelto.'\', this);')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> Descargar dinero', array('class' => 'btn btn-warning waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["descargadinero"], array('listar'=>'SI')).'\', \''.$tituloDescargaDinero.'\', this);')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> Gastos extras', array('class' => 'btn btn-dark waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["gastos"], array('listar'=>'SI')).'\', \''.$tituloGastos.'\', this);')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-remove"></i> Cerrar turno', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnNuevo', 'onclick' => 'modal (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$tituloCierreTurno.'\', this);')) !!}
				{!! Form::close() !!}
				<div id="listado{{ $entidad }}"></div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		$("#tipob").change(function () {
			buscar('{{ $entidad }}');
		});
	});
	$(".empleado").on('click', function(){
		var idempleado = $(this).attr('id');
		$(".empleado").css('background', 'rgb(255,255,255)');
		$(this).css('background', 'rgb(179,188,237)');
		$('#turno_id').attr('value',idempleado);
		$("#empleado_nombre").val($(this).children('label').html());
		buscar('{{ $entidad }}');
	});
</script>