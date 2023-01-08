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
				<div class="col-sm-12">
					<div class="form-group">		
						{!! Form::label('sucursal_id', 'Sucursal:') !!}
						{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-xs', 'id' => 'sucursal_id' , 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">		
						{!! Form::label('fechai', 'Desde:') !!}
						{!! Form::date('fechai', $desde, array('class' => 'form-control input-sm', 'id' => 'fechai')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">		
						{!! Form::label('fechaf', 'Hasta:') !!}
						{!! Form::date('fechaf', $hasta, array('class' => 'form-control input-sm', 'id' => 'fechaf')) !!}
					</div>
				</div>
				<div class="col-sm-12" style="margin-top:10px;">
					<div class="form-group">
						{!! Form::label('cliente', 'Cliente:') !!}
						{!! Form::text('cliente', '', array('class' => 'form-control input-sm', 'size' => '40','id' => 'cliente')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">	
						{!! Form::label('trabajador', 'Trabajador:') !!}
						<div class="form-group">
							{!! Form::text('trabajador', '', array('class' => 'form-control input-sm', 'size' => '40', 'id' => 'trabajador')) !!}
							{!! Form::hidden('trabajador_id', '', array('id' => 'trabajador_id')) !!}
						</div>
						{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnproductoborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-sm btnBorrar' , 'style' => 'height: 30px; margin-top: -7px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'Borrar')) !!}
					</div>
				</div>
				<div class="col-sm-12" style="margin-top:10px;">
					<div class="form-group" style="margin-left:10px;">	
						{!! Form::label('tipo', 'Tipo de entrega:') !!}
						{!! Form::select('tipo', $cboTipo, null, array('class' => 'form-control input-sm', 'id' => 'tipo' ,'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">	
						{!! Form::label('tipo', 'Tipo de documento:') !!}
						{!! Form::select('tipodocumento', $cboTipoDocumento, null, array('class' => 'form-control input-sm', 'id' => 'tipodocumento' ,'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">	
						{!! Form::label('tipo', 'Tipo de vale:') !!}
						{!! Form::select('tipovale', $cboTipoVale, null, array('class' => 'form-control input-sm', 'id' => 'tipovale' ,'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					<div class="form-group" style="margin-left:10px;">	
						{!! Form::label('filas', 'Filas a mostrar:')!!}
						{!! Form::selectRange('filas', 1, 30, 15, array('class' => 'form-control input-sm', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
					</div>
					{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
				{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-primary waves-effect waves-light m-l-10 btn-sm btnNuevo', 'style' => 'margin-top: 5px;', 'onclick' => 'modal (\''.URL::route($ruta["crear"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
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
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="cliente"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="fechai"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="fechaf"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
		
		$('.btnBorrar').on('click', function(){
			$('#trabajador_id').val("");
			$('#trabajador').val("");
			$("#trabajador").prop('disabled',false);
			$('#trabajador').focus();
			buscar('{{ $entidad }}');
		});
		
		var personas = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			limit:10,
			remote: {
				url: 'trabajador/trabajadorautocompleting/%QUERY',
				filter: function (personas) {
					return $.map(personas, function (trabajador) {
						return {
							value: trabajador.value,
							id: trabajador.id,
						};
					});
				}
			}
		});
		personas.initialize();
		$('#trabajador').typeahead(null,{
			displayKey: 'value',
			limit:10,
			source: personas.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#trabajador_id').val(datum.id);
			$('#trabajador').val(datum.value);
			$('#trabajador').prop('disabled', true);
			buscar('{{ $entidad }}');
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