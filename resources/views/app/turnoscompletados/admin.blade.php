<?php
$hasta = date("Y-m-d");
$desde = strtotime ( '-14 day' , strtotime ( $hasta ) ) ;
$desde = date( 'Y-m-d' , $desde );
?>
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
					@if(!empty($empleados))
					<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-x: scroll; border-style: groove;">
						@foreach($empleados  as $key => $value)
							<div class="empleado" id="{{ $value->id}}" style="margin: 5px; width: 120px; height: 110px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;" >
								<img src="assets/images/empleado.png" style="width: 50px; height: 50px">
								<?php
									$nombre_completo = $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat;
								?>
								<label style="font-size: 11px;  color: #2a3f54;">{{ $nombre_completo }}</label>
							</div>
						@endforeach
						{!! Form::hidden('trabajador_id',null,array('id'=>'trabajador_id')) !!}
						{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
					</div>
					@else
					<h4 class="page-venta" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;"> NO HAY REPARTIDORES EN TURNO</h4>
					@endif
				</div>
				<div class="form-group">
					{!! Form::label('desde', 'Desde:', array('class' => 'col-sm-3 col-xs-12 input-md', 'style' => 'margin-top: 8px;')) !!}
					<div class="col-sm-9 col-xs-12">
						<input class="form-control input-md" id="desde" placeholder="Ingrese Fecha" name="desde" type="date" value="{{ $desde }}">
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('hasta', 'Hasta:', array('class' => 'col-sm-3 col-xs-12 input-md', 'style' => 'margin-top: 8px;')) !!}
					<div class="col-sm-9 col-xs-12">
						<input class="form-control input-md" id="hasta" placeholder="Ingrese Fecha" name="hasta" type="date" value="{{ $hasta }}">
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::close() !!}
				
				<div id="listado{{ $entidad }}"></div>

			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="concepto"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		$("#tipob").change(function () {
			buscar('{{ $entidad }}');
		});
		$(".empleado").on('click', function(){
			var idempleado = $(this).attr('id');
			$(".empleado").css('background', 'rgb(255,255,255)');
			$(this).css('background', 'rgb(179,188,237)');
			$('#trabajador_id').attr('value',idempleado);
			$("#empleado_nombre").val($(this).children('label').html());
			buscar('{{ $entidad }}');
		});
	});
</script>