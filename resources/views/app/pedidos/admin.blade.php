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
				<div class="form-group">			
					{!! Form::label('sucursal_id', 'Sucursal:') !!}
					{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_id' , 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				<div class="form-group">
					{!! Form::label('filas', 'Filas a mostrar:')!!}
					{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
				</div>
				{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Registrar Pedido', array('class' => 'btn btn-dark waves-effect waves-light m-l-10 btn-sm btnPedido', 'style' => 'margin-top: 5px;' , 'onclick' => 'cargarRutaMenu("venta", "container", 16)')) !!}
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
			$('#turno_id').attr('value',idempleado);
			$("#empleado_nombre").val($(this).children('label').html());
			buscar('{{ $entidad }}');
		});
	});
</script>