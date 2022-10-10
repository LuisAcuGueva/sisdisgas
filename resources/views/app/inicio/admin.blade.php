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
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-inbox"></i> Caja actual</h2>
				<div class="clearfix"></div>
			</div>

			<div id="divMensajeError{!! $entidad !!}"></div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search_caja"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_caja]) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}

				<div class="form-group">
					{!! Form::label('sucursal_id_caja', 'Sucursal:') !!}
					{!! Form::select('sucursal_id_caja', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id_caja' , 'onchange' => 'buscar("'. $entidad_caja.'");')) !!}
				</div>

				<div class="form-group" style="display:none;">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad_caja.'\')')) !!}
				</div>

				{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'style' => 'margin-top: 5px; display:none;', 'onclick' => 'buscar(\''.$entidad_caja.'\')')) !!}
				{!! Form::close() !!}

				<div id="listado{{ $entidad_caja }}"></div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-bicycle"></i> Editar sucursal de repartidor</h2>
				<div class="clearfix"></div>
			</div>
			<div id="divMensajeErrorPassword"></div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search_turnos"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_turnos]) !!}
				<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					@if(!empty($empleados))
						<div id="empleados">
							@foreach($empleados  as $key => $value)
								<div class="empleado" id="{{ $value->id}}" >
									<img src="assets/images/empleado.png" style="width: 60px; height: 60px">
									<label class="empleado-label">{{ $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat }}</label>
								</div>
							@endforeach
							{!! Form::hidden('trabajador_id',null,array('id'=>'trabajador_id')) !!}
							{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
						</div>
					@else
						<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;"> TODOS LOS REPARTIDORES EN TURNO</h4>
					@endif
				</div>
				<div class="form-group" style="display:none;">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad_turnos.'\')')) !!}
				</div>
				{!! Form::close() !!}
				<div id="listado{{ $entidad_turnos }}"></div>
			</div>

		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-dollar"></i> Productos vendidos hoy</h2>
				<div class="clearfix"></div>
			</div>
			<div id="divMensajeErrorPassword"></div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search_vendidos"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_productos]) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}

				<div class="form-group">
					{!! Form::label('sucursal_id_productos', 'Sucursal:') !!}
					{!! Form::select('sucursal_id_productos', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id_productos' , 'onchange' => 'buscar("'. $entidad_productos.'");')) !!}
				</div>

				<div class="form-group" style="display:none;">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad_productos.'\')')) !!}
				</div>

				{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'style' => 'margin-top: 5px; display:none;', 'onclick' => 'buscar(\''.$entidad_productos.'\')')) !!}

				{!! Form::close() !!}

				<div id="listado{{ $entidad_productos }}"></div>
			</div>

		</div>
	</div>

</div>

<script>
	$(document).ready(function() {
		buscar('{{ $entidad_caja }}');
		buscar('{{ $entidad_productos }}');
		buscar('{{ $entidad_turnos }}');

		$(".empleado").on('click', function() {
			var idempleado = $(this).attr('id');
			$(".empleado").css('background', 'rgb(255,255,255)');
			$(this).css('background', 'rgb(179,188,237)');
			$('#trabajador_id').attr('value', idempleado);
			$("#empleado_nombre").val($(this).children('label').html());
			buscar('{{ $entidad_turnos }}');
		});
	});
</script>