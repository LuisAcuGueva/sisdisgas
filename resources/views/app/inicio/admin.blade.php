<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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

		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><i class="fa fa-inbox"></i> Pedidos a crédito</h2>
					<div class="clearfix"></div>
				</div>

				<div id="divMensajeError{!! $entidad !!}"></div>
				<div class="x_content">
					{!! Form::open(['route' => $ruta["search_credito"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_credito]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}

					<div class="form-group">
						{!! Form::label('sucursal_id_credito', 'Sucursal:') !!}
						{!! Form::select('sucursal_id_credito', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id_credito' , 'onchange' => 'buscar("'. $entidad_credito.'");')) !!}
					</div>

					<div class="form-group" style="display:none;">
						{!! Form::label('filas', 'Filas a mostrar:')!!}
						{!! Form::selectRange('filas', 1, 30, 1, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad_credito.'\')')) !!}
					</div>

					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'style' => 'margin-top: 5px; display:none;', 'onclick' => 'buscar(\''.$entidad_credito.'\')')) !!}
					{!! Form::close() !!}

					<div id="listado{{ $entidad_credito }}"></div>
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 50px;">
			<div class="x_panel">
				<div class="x_title">
					<h2><i class="fa fa-bicycle"></i> Repartidores en turno</h2>
					<div class="clearfix"></div>
				</div>
				<div id="divMensajeErrorPassword"></div>
				<div class="x_content">
					{!! Form::open(['route' => $ruta["search_turnos"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_turnos]) !!}
					<div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
						{!! Form::hidden('page', 1, array('id' => 'page')) !!}
						{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
						@if(!empty($turnos_iniciados))
						<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-y: scroll; border-style: groove;">
							@foreach($turnos_iniciados as $key => $value)
							<div class="empleado" id="{{ $value->id}}" style="margin: 5px; width: 120px; height: 110px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;">
								<img src="assets/images/empleado.png" style="width: 50px; height: 50px">
								<?php
								$nombre_completo = $value->person->nombres . ' ' . $value->person->apellido_pat . ' ' . $value->person->apellido_mat;
								?>
								<label style="font-size: 11px;  color: #2a3f54;">{{ $nombre_completo }}</label>
							</div>
							@endforeach
							{!! Form::hidden('turno_id',null,array('id'=>'turno_id')) !!}
							{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
						</div>
						@else
						<h4 class="page-venta" style="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;"> NO HAY REPARTIDORES EN TURNO</h4>
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
	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="x_panel">
				<div class="x_title">
					<h2><i class="fa fa-cubes"></i> Inventario actual</h2>
					<div class="clearfix"></div>
				</div>
				<div id="divMensajeErrorPassword"></div>
				<div class="x_content">
					{!! Form::open(['route' => $ruta["search_stock"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad_inventario]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}

					<div class="form-group">
						{!! Form::label('sucursal_id_stock', 'Sucursal:') !!}
						{!! Form::select('sucursal_id_stock', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id_stock' , 'onchange' => 'buscar("'. $entidad_inventario.'");')) !!}
					</div>

					<div class="form-group" style="display:none;">
						{!! Form::label('filas', 'Filas a mostrar:')!!}
						{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad_inventario.'\')')) !!}
					</div>

					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnBuscar', 'style' => 'margin-top: 5px; display:none;', 'onclick' => 'buscar(\''.$entidad_inventario.'\')')) !!}

					{!! Form::close() !!}
					<div id="listado{{ $entidad_inventario }}"></div>

				</div>

			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

</div>

<script>
	$(document).ready(function() {
		buscar('{{ $entidad_caja }}');
		buscar('{{ $entidad_productos }}');
		buscar('{{ $entidad_inventario }}');
		buscar('{{ $entidad_turnos }}');
		buscar('{{ $entidad_credito }}');

		$(".empleado").on('click', function() {
			var idempleado = $(this).attr('id');
			$(".empleado").css('background', 'rgb(255,255,255)');
			$(this).css('background', 'rgb(179,188,237)');
			$('#turno_id').attr('value', idempleado);
			$("#empleado_nombre").val($(this).children('label').html());
			buscar('{{ $entidad_turnos }}');
		});
	});
</script>