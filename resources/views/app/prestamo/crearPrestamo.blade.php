<style>
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
		-webkit-appearance: none; 
		margin: 0; 
	}
	input[type=number] { -moz-appearance:textfield; }
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', 'SI', array('id' => 'listar')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-4 col-md-4 col-sm-6">
			{!! Form::label('sucursal_id', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
			{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id' , 'onchange' => 'cambiarSucursal();', 'style' => 'margin-top: 7px;')) !!}
		</div>
		<div class="col-lg-3 col-md-3 col-sm-6">
			{!! Form::label('fecha', 'Fecha:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
			{!! Form::date('fecha', '', array('class' => 'form-control input-sm', 'id' => 'fecha', 'style' => 'margin-top: 7px;')) !!}
		</div>
		<div class="col-lg-5 col-md-5 col-sm-12">
			<div class="col-lg-3 col-md-3 col-sm-3">
				{!! Form::label('cliente', 'Cliente:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-success waves-effect waves-light btn-sm', 'onclick' => 'modal (\''.URL::route($ruta["cliente"], array('listar'=>'SI')).'\', \''.$titulo_cliente.'\', this);', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO')) !!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::button('<i class="glyphicon glyphicon-user"></i>', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'onclick' => 'clienteVarios()', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'VARIOS')) !!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2">
				{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('class' => 'btn btn-danger waves-effect waves-light btn-sm', 'onclick' => 'borrarCliente()', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'BORRAR')) !!}
			</div>
			{!! Form::text('cliente_prestamo', '', array('class' => 'form-control input-sm', 'id' => 'cliente_prestamo', 'style' => 'background-color: white;')) !!}
			{!! Form::hidden('cliente_prestamo_id',null,array('id'=>'cliente_prestamo_id')) !!}
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div style=" border: solid 1px; border-radius: 5px; height: 40px; margin-top: 10px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">LISTA DE PRODUCTOS</h4>
				<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
					<thead id="cabecera">
						<tr>
							<th width="60%" style="font-size: 13px !important;">Descripción</th>
							<th width="20%" style="font-size: 13px !important;">Cantidad</th>
							<th width="20%" style="font-size: 13px !important;">Envases prestados</th>
						</tr>
					</thead>
					<tbody id="detalle">
						@foreach($productos_balones as $producto)
							<tr id="{{$producto->id}}">
								<td>{{$producto->descripcion}}</td>
								<td>
									{!! Form::number($producto->id.'_cant', '0', array('class' => 'cantidad_total form-control input-xs','style' => 'width:80px; ', 'producto_id' => $producto->id, 'id' => $producto->id.'_cant', 'min' => '0')) !!}
								</td>
								<td>
									{!! Form::number($producto->id.'_prest', '0', array('class' => 'cantidad_prestamo form-control input-xs','style' => 'width:80px; ', 'producto_id' => $producto->id, 'id' => $producto->id.'_prest', 'min' => '0')) !!}
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		configurarAnchoModal('800');
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		// generarFecha();
	}); 

	//* Buscador de clientes
	var clientes = new Bloodhound({
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		limit: 5,
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		remote: {
			url: 'cliente/clienteautocompleting/%QUERY',
			filter: function (clientes) {
				return $.map(clientes, function (cliente) {
					return {
						id: cliente.id,
						value: cliente.value,
						name: cliente.name,
					};
				});
			}
		}
	});
	clientes.initialize();
	$('#cliente_prestamo').typeahead(null,{
		displayKey: 'value',
		source: clientes.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$('#cliente_prestamo').val(datum.name);
		$('#cliente_prestamo_id').val(datum.id);
		$("#cliente_prestamo").prop('disabled',true);
	});

	function clienteVarios(){
		$('#cliente_prestamo_id').val({{ $anonimo->id }});
		$('#cliente_prestamo').val('VARIOS');
		$("#cliente_prestamo").prop('disabled',true);
	}

	function borrarCliente(){
		$('#cliente_prestamo_id').val("");
		$('#cliente_prestamo').val("");
		$("#cliente_prestamo").prop('disabled',false);
	} 

	$(".cantidad_total").blur(function(){
		var producto_id = $(this).attr('producto_id');
		var max_cant = $(this).val();
		console.log(max_cant);
		$('#' + producto_id + '_prest').attr('max', max_cant);
	});

	$(".cantidad_prestamo").blur(function(){
		var max = $(this).attr('max');
		var val = $(this).val();
		var data = [];
		$(".cantidades").each(function(){
			var element = $(this); // <-- en la variable element tienes tu elemento
			var id = element.attr('id');
			id = id.replace("cant_", "");
			var cantidad = $(this).val();
			if( cantidad != ""){
				data.push(
					{"id": id , "cantidad": cantidad }
				);
			}
		});
		if(max < val){
			$(this).val("");
			swal({
				title: 'INGRESE VALOR MENOR A LA CANTIDAD DEL PRODUCTO',
				type: 'error',
			});
		}else{
			data = [];
			$(".cantidades").each(function(){
				var element = $(this); // <-- en la variable element tienes tu elemento
				var id = element.attr('id');
				id = id.replace("cant_", "");
				var cantidad = $(this).val();
				if( cantidad != ""){
					data.push(
						{"id": id , "cantidad": cantidad }
					);
				}
			});
		}
		if(data.length == 0){
			$("#devolver_envases").val("");
		}else{
			$("#devolver_envases").val(1);
			$("#data").val(JSON.stringify(data));
		}
	});

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
</script>