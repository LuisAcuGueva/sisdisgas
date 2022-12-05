<style>
	.alert.alert-danger{
		font-size: 18px;
	}
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
		-webkit-appearance: none; 
		margin: 0; 
	}
	input[type=number] { -moz-appearance:textfield; }
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
	.producto{
		cursor: pointer;
		display: table;
		margin: 5px; 
		width: 85px; 
		height: 85px; 
		text-align: center; 
		border-style: solid; 
		border-color: #2a3f54; 
		border-radius: 10px;
	}
	.product-label{
		cursor: pointer;
		display: table-cell;
		vertical-align: middle;
		font-size: 12px; 
		color: #2a3f54;
	}
	#div_productos{
		border-style: groove; 
		width: 100%; 
		height: 200px; 
		overflow-y: scroll;
	}
	.section-title{
		border: solid 1px; 
		border-radius: 5px; 
		height: 35px; 
		margin-bottom: 10px; 
		text-align: center; 
		color: #ffffff; 
		border-color: #2a3f54; 
		background-color: #2a3f54;
	}
	.text-title{
		margin-top: 8px; 
		font-weight: 600;
	}
	.text-empleado-red{
		margin: 10px 0px; 
		text-align: center; 
		color: red;
	}
	.inputDetProducto{
		text-align: right; 
		width: 70px;
	}
	#detalle_prod td, #detalle_pago td{
		vertical-align: middle;
		text-align: center; 
	}
	#cabecera th{
		font-size: 13px !important; 
		text-align: center;
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
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div id="divMensajeError{!! $entidad !!}"></div>
				</div>
				{!! Form::open(['route' => $ruta["guardarventa"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'IDFORMMANTENIMIENTO'.$entidad]) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="section-title">
						<h4 class="text-title">SELECCIONE SUCURSAL Y REPARTIDOR</h4>
					</div>
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-5 venta-sucursal" style="margin-top: 25px;">
							{!! Form::label('venta_sucursal', 'Venta en sucursal:' ,array('class' => 'input-md'))!!}
							<input name="venta_sucursal" type="checkbox" id="venta_sucursal">
						</div>
						<div class="col-lg-7 col-md-7 col-sm-7">
							{!! Form::label('sucursal_id', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
							{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id' , 'onchange' => 'cambiarSucursal();')) !!}		
						</div>
					</div>
					{!! Form::hidden('empleado_id',null,array('id'=>'empleado_id')) !!}
					{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
					<h4 id="text-sucursal-repartidor" class="text-title text-empleado-red" style ="display: none;"></h4>
					<div id="empleados" style ="display: none;"></div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="row section-title">
						<h4 class="text-title">DATOS DEL DOCUMENTO</h4>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 m-b-15">
						{!! Form::label('tipodocumento_id', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::select('tipodocumento_id', $cboTipoDocumento, null, array('class' => 'form-control input-sm', 'id' => 'tipodocumento_id', 'onchange' => 'generarNumeroSerie();')) !!}		
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 m-b-15">
						{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('serieventa', '', array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'")) !!}
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4 m-b-15" style="margin-top: 17px;">
						{!! Form::label('fecha', 'Fecha:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('fecha', '', array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
					</div>
					<div class="col-lg-8 col-md-8 col-sm-8 m-b-15" style="margin-top: 10px;">
						<div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-10px;">
							{!! Form::label('cliente', 'Cliente:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 20px;">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-success waves-effect waves-light btn-sm', 'onclick' => 'modal (\''.URL::route($ruta["cliente"], array('listar'=>'SI')).'\', \''.$titulo_cliente.'\', this);', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO')) !!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 10px;">
							{!! Form::button('<i class="glyphicon glyphicon-user"></i>', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'onclick' => 'clienteVarios()', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'VARIOS')) !!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 10px;">
							{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('class' => 'btn btn-danger waves-effect waves-light btn-sm', 'onclick' => 'borrarCliente()', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'BORRAR')) !!}
						</div>
						{!! Form::text('cliente', '', array('class' => 'form-control input-sm', 'id' => 'cliente', 'style' => 'background-color: white;')) !!}
						{!! Form::hidden('cliente_id',null,array('id'=>'cliente_id')) !!}
						{!! Form::hidden('ultimo_cliente',null,array('id'=>'ultimo_cliente')) !!}
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 m-b-15" style="margin-bottom: 10px;">
						{!! Form::label('cliente_direccion', 'Dirección:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::textarea('cliente_direccion', null, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'cliente_direccion', 'readOnly')) !!}
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 m-b-15" style="margin-bottom: 10px;">
						{!! Form::label('comentario', 'Comentario:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::textarea('comentario', null, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'comentario')) !!}
					</div>
				</div>

				<div class="col-lg-8 col-md-8 col-sm-8">
					<div class="col-lg-12 col-md-12 col-sm-12 section-title">
						<h4 class="text-title">SELECCIONE PRODUCTOS</h4>
					</div>
					<div id="div_productos" class="col-lg-12 col-md-12 col-sm-12"></div>
					<div class="form-group col-lg-12 col-md-12 col-sm-12">
						{!! Form::hidden('cantidad_productos',null,array('id'=>'cantidad_productos', 'value' => '0')) !!}
						<h4 align="center" class="col-lg-12 col-md-12 col-sm-12" style="color: #2a3f54; font-weight: 600;">LISTA DE PRODUCTOS</h4>
						<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="padding: 0px 0px !important;">
							<thead id="cabecera">
								<tr>
									<th>Descripción</th>
									<th>Cantidad</th>
									<th>Precio S/.</th>
									<th>Envase Nuevo</th>
									<th>Total S/.</th>
									<th>Eliminar</th>
								</tr>
							</thead>
							<tbody id="detalle_prod"></tbody>
						</table>
					</div>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4">
					<div class="col-lg-12 col-md-12 col-sm-12 section-title">
						<h4 class="text-title">PAGO</h4>
					</div>
					<div class="row">
						<div  class="col-lg-10 col-md-10 col-sm-10">
							{!! Form::select('metodopago_id', $cboMetodoPago, null, array('class' => 'form-control input-sm', 'id' => 'metodopago_id')) !!}		
							{!! Form::number('monto_pago', '', array('class' => 'form-control input-sm montos', 'id' => 'monto_pago', 'style' => 'text-align: right; margin-top: 5px; font-size: 18px', 'placeholder' => '0.00')) !!}
						</div>
						<div  class="col-lg-2 col-md-2 col-sm-2">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-warning waves-effect waves-light btn-sm', 'onclick' => 'agregarPago();', 'data-toggle' => 'tooltip', 'data-placement' => 'bottom' ,  'title' => 'AGREGAR PAGO')) !!}
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 tabla_pagos" style="display: none; margin-top: 5px;">
							<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="padding: 0px !important; margin: 0px !important;">
								<thead id="cabecera">
									<tr>
										<th>Método pago</th>
										<th>Monto S/.</th>
										<th>Eliminar</th>
									</tr>
								</thead>
								<tbody id="detalle_pago"></tbody>
								<tfoot>
									<tr>
										<td>TOTAL PAGOS</td>
										<td colspan="2">{!! Form::text('total_pago', 0, array('class' => 'form-control', 'id' => 'total_pago', 'readOnly', 'style' => 'text-align: right; font-size: 25px; margin: 0px;')) !!}</td>
									</tr>
								</tfoot>
							</table>
							
						</div>
					</div>
					<hr style="margin-top: 10px; margin-bottom: 10px;">
					<div class="row">
						{!! Form::hidden('activar_checkbox', 0 ,array('id'=>'activar_checkbox')) !!}
						<div class="col-lg-4 col-md-4 col-sm-4 vale_fise">
							{!! Form::label('', 'FISE:' ,array('class' => 'input-sm'))!!}
							<div  style="margin-left:12px">
								<input name="vale_balon_fise" type="checkbox" id="vale_balon_fise" disabled>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 vale_subcafae">
							{!! Form::label('', 'SUBCAFAE:' ,array('class' => 'input-sm'))!!}
							<div  style="margin-left:28px">
								<input name="vale_balon_subcafae" type="checkbox" id="vale_balon_subcafae" disabled>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 vale_monto">
							{!! Form::label('', 'DTO S/:' ,array('class' => 'input-sm'))!!}
							<div  style="margin-left:25px">
								<input name="vale_balon_monto" type="checkbox" id="vale_balon_monto" disabled>
							</div>
						</div>
					</div>
					<div class="row div_fise" style="display: none; margin-top: 10px;">
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::text('codigo_vale_fise', '', array('class' => 'form-control input-sm', 'id' => 'codigo_vale_fise', 'placeholder' => 'Código FISE')) !!}
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::text('monto_vale_fise', '', array('class' => 'form-control input-sm', 'id' => 'monto_vale_fise', 'style' => 'text-align: right; font-size: 23px;', 'readOnly')) !!}
						</div>
					</div>
					<div class="row div_subcafae" style="display: none; margin-top: 10px;">
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::text('codigo_vale_subcafae', '', array('class' => 'form-control input-sm', 'id' => 'codigo_vale_subcafae', 'placeholder' => 'Código SUBCAFAE')) !!}
						</div>
					</div>
					<div class="row div_dto" style="display: none; margin-top: 10px;">
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::text('codigo_vale_monto', '', array('class' => 'form-control input-sm', 'id' => 'codigo_vale_monto' ,'placeholder' => 'Código vale monto')) !!}
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::number('monto_vale_balon', '', array('class' => 'form-control input-sm', 'id' => 'monto_vale_balon', 'style' => 'text-align: right; font-size: 23px;', 'placeholder' => '0.00')) !!}
						</div>
					</div>
					<hr style="margin-top: 10px; margin-bottom: 10px;">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::label('total', 'Total:' ,array('class' => 'input-md'))!!}
							{!! Form::text('total', '', array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 25px;')) !!}
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6">
							{!! Form::label('vuelto', 'Vuelto:' ,array('class' => 'input-md'))!!}
							{!! Form::text('vuelto', '', array('class' => 'form-control input-lg', 'id' => 'vuelto', 'readOnly', 'style' => 'text-align: right; font-size: 25px; color: red;', 'placeholder' => '0.00')) !!}
						</div>
					</div>
					<hr style="margin-top: 10px; margin-bottom: 10px;">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 pedido-credito">
							{!! Form::label('pedido_credito', 'Pedido a crédito:' ,array('class' => 'input-md', 'style' => 'margin-top: 5px;'))!!}
							<input name="pedido_credito" type="checkbox" id="pedido_credito">
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4">
							{!! Form::button('<i class="fa fa-check"></i> Guardar', array( 'class' => 'btn btn-success waves-effect waves-light btn-md btnGuardar', 'id' => 'btnGuardar', 'disabled','onClick' => 'guardarPedido();')) !!}
						</div>
					</div>
				</div>
				{!! Form::close() !!}
        	</div>
        </div>
    </div>
</div>

<script>
//* Activar css checkbox
$('input').iCheck({
	checkboxClass: 'icheckbox_flat-green',
	radioClass: 'iradio_flat-green'
});
$(document).ready(function(){
	//* Definir precio de vale fise
	$('#monto_vale_fise').val(({{$descuento_fise->valor}}).toFixed(2));
	$("#total").val((0).toFixed(2));
	$("#vuelto").val((0).toFixed(2));
	$("#tipodocumento_id").val(3);
	$("#serieventa").inputmask({"mask": "9999-9999999"});

	bloquearChecksVales();
	limpiarDetalleProductos();
	generarFecha();
	cambiarSucursal();

	$("#monto_vale_balon").blur(function(){
		if( $("#monto_vale_balon").val() != ""){
			if( is_numeric( $("#monto_vale_balon").val())){
				var total = 0;
				$("#detalle_prod tr").each(function(){
					//var cantidad = parseInt($(this).attr('cantidad'));
					/* GERSON (05/11/22) */
					var cantidad = parseFloat($(this).attr('cantidad'));
					/*  */
					var precio = parseFloat($(this).attr('precio'));
					total += precio*cantidad;
				});
				$("#total").val(total.toFixed(2));
				var monto_vale_balon = parseFloat($("#monto_vale_balon").val());
				if(monto_vale_balon < 0 ||  monto_vale_balon > total){
					$("#monto_vale_balon").val("");
				}else{
					calcularTotal(true);
				}
			}else{
				$("#monto_vale_balon").val("");
			}
		}else{
			calcularTotal(true);
		}
	}); 
});

// LISTO

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
					value: cliente.value,
					id: cliente.id,
					direccion: cliente.direccion,
				};
			});
		}
	}
});
clientes.initialize();
$('#cliente').typeahead(null,{
	displayKey: 'value',
	source: clientes.ttAdapter()
}).on('typeahead:selected', function (object, datum) {
	$('#cliente').val(datum.value);
	$('#cliente_id').val(datum.id);
	$('#cliente_direccion').val(datum.direccion);
	$("#cliente").prop('disabled',true);
}); 

//* Activar pestañas flotantes en botones de clientes
$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'}); 

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

function clienteVarios(){
	$('#cliente_id').val({{ $anonimo->id }});
	$('#cliente').val('VARIOS');
	$("#cliente").prop('disabled',true);
}

function borrarCliente(){
	$('#cliente_id').val("");
	$('#cliente').val("");
	$('#cliente_direccion').val("");
	$("#cliente").prop('disabled',false);
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

//* Funcion click check venta en sucursal
$('.venta-sucursal .iCheck-helper').on('click', function(){
	generarEmpleados();
});

//* Funcion click check pedido credito
$('.pedido-credito .iCheck-helper').on('click', function(){
	$(this).parent().hasClass('checked')
		? $('#btnGuardar').prop('disabled', false)
		: $('#btnGuardar').prop('disabled', true);
});

function cambiarSucursal(){
	generarNumeroSerie(); 
	generarEmpleados();
	permisoRegistrar();
}

function generarNumeroSerie(){
	$.ajax({
		"method": "POST",
		"url": "{{ url('/venta/serieventa') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"tipodocumento_id" : $('#tipodocumento_id').val(),
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(serie){
		$('#serieventa').val(serie);
	});
}

function generarEmpleados(){
	var tabla = "";
	var venta_sucursal = $('.venta-sucursal .iCheck-helper').parent().hasClass('checked');
	$.ajax({
		"method": "POST",
		"url": "{{ url('/turno/cargarempleados') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(empleados){
		if(venta_sucursal){
			$('#text-sucursal-repartidor').html("VENTA EN SUCURSAL");
			$('#text-sucursal-repartidor').css("display","");
			$('#empleados').css('display', 'none');
		}else{
			if(empleados != ""){ //* HAY REPARTIDORES
				$('#text-sucursal-repartidor').css("display","none");
				$('#empleados').css('display', '-webkit-inline-box');	
			}else{ //* NO HAY REPARTIDORES
				$('#text-sucursal-repartidor').html("NO HAY REPARTIDOR EN TURNO");
				$('#text-sucursal-repartidor').css("display","");
				$('#empleados').css('display', 'none');
			}
		}
		// LLENAR CAJÓN DE REPARTIDOR
		$.each(empleados, function(i, item) {
			tabla += '<div class="empleado" id="' + item.id + '"><img src="assets/images/empleado.png" style="width: 60px; height: 60px"><label class="empleado-label">' + item.nombres + ' ' + item.apellido_pat  + ' ' + item.apellido_mat +'</label></div>';   
		});
		$('#empleados').html(tabla);
		$('#empleado_id').val("");
		$('#empleado_nombre').val("");
		clickRepartidor();
	});
}

function permisoRegistrar(){
	var aperturaycierre = null;
	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(aperturaycierre){
		if(aperturaycierre == 0){
			$('form').find('input, textarea, button').prop('disabled',true);
			$("#tipodocumento_id").prop('disabled',true);
			$("#metodopago_id").prop('disabled',true);
			bloquearCheckVentaSucursal();
			bloquearCheckPedidoCredito();
			swal({
				title: 'APERTURAR CAJA DE LA SUCURSAL',
				type: 'error',
			});
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';
			$('#divMensajeErrorVenta').html(cadenaError);
			$('#div_productos').html('');
			limpiarDetalleProductos();
			desactivarVales();
			calcularTotal(false);
		}else if(aperturaycierre == 1){
			$('form').find('input, textarea, button').prop('disabled',false);
			$("#tipodocumento_id").prop('disabled',false);
			$("#metodopago_id").prop('disabled',false);
			desbloquearCheckVentaSucursal();
			desbloquearCheckPedidoCredito();
			generarProductos();
			$('#divMensajeErrorVenta').html("");
		}
	});
}

function generarProductos(){
	var tabla = "";
	$.ajax({
		"method": "POST",
		"url": "{{ url('/venta/cargarproductos') }}",
		"data": {
			"sucursal_id" : $('#sucursal_id').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(productos){
		// LLENAR CAJÓN DE PRODUCTOS
		$.each(productos, function(i, item) {
			tabla += '<div class="producto col-lg-3 col-md-3 col-sm-3" id="' + item.id  + '"  precio="' + item.precio_venta + '" precio_envase="' + item.precio_venta_envase + '" descripcion="' + item.descripcion + '" editable="' + item.editable + '" recargable="' + item.recargable + '" stock="' + item.cantidad + '" decimal="' + item.decimal + '"><label class="product-label">' + item.descripcion + '<br>STOCK: ' + item.cantidad + '</label></div>';
		});
		$('#div_productos').html(tabla);
		limpiarDetalleProductos();
		desactivarVales();
		clickProducto();
		calcularTotal(true);
	});
}

//* Funcion para habilitar escoger repartidor
function clickRepartidor(){
	$(".empleado").on('click', function(){
		$(".empleado").css('background', 'rgb(255,255,255)');
		$(this).css('background', 'rgb(179,188,237)');
		$('#empleado_id').attr('value', $(this).attr('id'));
		$("#empleado_nombre").val($(this).children('label').html());
	});
}

function pintarFilaProducto(){
	var primero = true;
	$("#detalle_prod tr").each(function(){
		var id = parseInt($(this).attr('id'));
		if((id == "4" || id == "5") && primero){
			$(this).css('background-color', '#97ff9a9e');
			primero = false;
		}
	});
}

//* Funcion para habilitar checks de vales
function clickVales(){
	if($('#activar_checkbox').val() == 1){
		clickValeFise();
		clickValeSubcafae();
		clickValeMonto();
	}
}
function clickValeFise(){
	$('.vale_fise .iCheck-helper').on('click', function(){
		var vale_fise = $(this).parent().hasClass('checked');
		if(vale_fise) {
			$(".div_fise").css('display','');
			$(".div_subcafae").css('display','none');
			$(".div_dto").css('display','none');
			pintarFilaProducto();
		}else {
			$(".div_fise").css('display','none');
			$("#detalle_prod tr").css('background-color', '');
		}
		desmarcarValeSubcafae();
		desmarcarValeMonto();
		calcularTotal(true);
		limpiarCamposVales();
	});
}
function clickValeSubcafae(){
	$('.vale_subcafae .iCheck-helper').on('click', function(){
		if($(this).parent().hasClass('checked')) { 
			$(".div_subcafae").css('display','');
			$(".div_fise").css('display','none');
			$(".div_dto").css('display','none');
			pintarFilaProducto();
		}else {
			$(".div_subcafae").css('display','none');
			$("#detalle_prod tr").css('background-color', '');
		}
		desmarcarValeFise();
		desmarcarValeMonto();
		calcularTotal(true);
		limpiarCamposVales();
	});
}
function clickValeMonto(){
	$('.vale_monto .iCheck-helper').on('click', function(){
		if($(this).parent().hasClass('checked')) { 
			$(".div_dto").css('display','');
			$(".div_subcafae").css('display','none');
			$(".div_fise").css('display','none');
		}else {
			$(".div_dto").css('display','none');
		}
		$("#detalle_prod tr").css('background-color', '');
		desmarcarValeFise();
		desmarcarValeSubcafae();
		calcularTotal(true);
		limpiarCamposVales();
	});
}

function bloquearChecksVales(){
	$('#vale_balon_subcafae').parent().addClass('disabled');
	$('#vale_balon_subcafae').prop('disabled', true);
	$('#vale_balon_monto').parent().addClass('disabled');
	$('#vale_balon_monto').prop('disabled', true);
	$('#vale_balon_fise').parent().addClass('disabled');
	$('#vale_balon_fise').prop('disabled', true);
}
function desbloquearChecksVales(){
	$('#vale_balon_fise').parent().removeClass('disabled');
	$('#vale_balon_fise').prop('disabled', false);
	$('#vale_balon_subcafae').parent().removeClass('disabled');
	$('#vale_balon_subcafae').prop('disabled', false);
	$('#vale_balon_monto').parent().removeClass('disabled');
	$('#vale_balon_monto').prop('disabled', false);
}

function bloquearCheckVentaSucursal(){
	$('#venta_sucursal').parent().addClass('disabled');
	$('#venta_sucursal').prop('disabled', true);
}
function desbloquearCheckVentaSucursal(){
	$('#venta_sucursal').parent().removeClass('disabled');
	$('#venta_sucursal').prop('disabled', false);
}
function bloquearCheckPedidoCredito(){
	$('#pedido_credito').parent().addClass('disabled');
	$('#pedido_credito').prop('disabled', true);
}
function desbloquearCheckPedidoCredito(){
	$('#pedido_credito').parent().removeClass('disabled');
	$('#pedido_credito').prop('disabled', false);
}

//* Funciones para desmarcar checks
function desmarcarValeFise(){
	$('#vale_balon_fise').parent().removeClass('checked');
	$('#vale_balon_fise').prop('checked',false);
}
function desmarcarValeSubcafae(){
	$('#vale_balon_subcafae').parent().removeClass('checked');
	$('#vale_balon_subcafae').prop('checked',false);
}
function desmarcarValeMonto(){
	$('#vale_balon_monto').parent().removeClass('checked');
	$('#vale_balon_monto').prop('checked',false);
}
function desmarcarPedidoCredito(){
	$('#pedido_credito').parent().removeClass('checked');
	$('#pedido_credito').prop('checked', false);
}

function limpiarCamposVales(){
	$('#codigo_vale_fise').val('');
	$('#codigo_vale_subcafae').val('');
	$('#codigo_vale_monto').val('');
	$('#monto_vale_balon').val('');
}
function ocultarCamposVales(){
	$(".div_fise").css('display','none');
	$(".div_subcafae").css('display','none');
	$(".div_dto").css('display','none');
}

//* Funcion que desmarca y bloquea checks de vales y desmarca check de pedido credito
function desactivarVales(){
	desmarcarValeSubcafae();
	desmarcarValeFise();
	desmarcarValeMonto();
	bloquearChecksVales();
	limpiarCamposVales();
	desmarcarPedidoCredito();
}

function limpiarDetalleProductos(){
	$("#detalle_prod").html("");
	$("#cantidad_productos").val(0);
	$('#activar_checkbox').val(0);
}

//* Funciones para habilitar inputs de precio y cantidad editable para detalle_prod de productos
function habilitarPrecioEditable(){
	$(".precio_editable").blur(function() {
		var precio_nuevo = parseFloat($(this).val());
		var tr = $(this).parent().parent();
		var precio_actual = $(tr).attr('precio');
		var recargable = $(tr).attr('recargable');
		var decimal = $(tr).attr('decimal');
		if(is_numeric(precio_nuevo)){
			if(precio_nuevo > 0){
				/* GERSON (05/11/22) */
				if(decimal=='null' || decimal=='0'){
					var cantidad_actual = parseInt($(tr).attr('cantidad'));
				}else{
					var cantidad_actual = parseFloat($(tr).attr('cantidad'));
				}
				/*  */
				if(recargable == 0){
					$(tr).attr('precio',(precio_nuevo).toFixed(2));
					var acumulado = parseFloat(cantidad_actual * precio_nuevo);
					$(tr).find('.precioacumulado').html((acumulado).toFixed(2));
					$(tr).find('.btnEliminar').attr('precio',(acumulado).toFixed(2));
					$(tr).attr('total',(acumulado).toFixed(2));
					calcularTotal(true);
				}else{
					var cantidad_envase = parseInt($(tr).attr('cantidad_envase'));
					var precio_envase = parseFloat($(tr).attr('precio_envase'));
					if(precio_nuevo < precio_envase){
						$(tr).attr('precio',(precio_nuevo).toFixed(2));
						var acumulado = parseFloat((cantidad_actual * precio_nuevo) + (cantidad_envase * precio_envase));
						$(tr).find('.precioacumulado').html((cantidad_actual * precio_nuevo) + " + " + (cantidad_envase * precio_envase) + " = " + (acumulado).toFixed(2));
						$(tr).find('.btnEliminar').attr('precio',(acumulado).toFixed(2));
						$(tr).attr('total',(acumulado).toFixed(2));
						calcularTotal(true);
					}else{
						swal({
							title: 'EL PRECIO DEL PRODUCTO NO PUEDE SER MAYOR AL PRECIO DEL PRODUCTO + ENVASE',
							type: 'error',
						});
						$(this).val(precio_actual);
					}
				}
			}else{
				swal({
					title: 'LOS CAMPOS PRECIOS EDITABLES DEBEN SER MONTOS POSITIVOS',
					type: 'error',
				});
				$(this).val(precio_actual);
			}
		}else{
			swal({
				title: 'LOS CAMPOS PRECIOS EDITABLES DEBEN SER NÚMERICOS',
				type: 'error',
			});
			$(this).val(precio_actual);
		}
	});
}

function habilitarCantidadEditable(){
	$(".cantidad_editable, .cantidad_editable_decimal").blur(function() {
		var tr = $(this).parent().parent();
		var cantidad_actual = $(tr).attr('cantidad');
		var recargable = $(tr).attr('recargable');
		/* GERSON (26/11/22) */
		var decimal = $(tr).attr('decimal');
		if(decimal=='null' || decimal=='0'){
			var cantidad_nueva = parseInt($(this).val());
		}else{
			var cantidad_nueva = parseFloat($(this).val());
		}
		/*  */
		if(is_numeric(cantidad_nueva)){
			if(cantidad_nueva >= 0){
				var precio_actual = parseFloat($(tr).attr('precio'));
				/* GERSON (17/11/22) */
				if(decimal=='null' || decimal=='0'){
					var stock = parseInt($(tr).attr('stock'));
				}else{
					var stock = parseFloat($(tr).attr('stock'));
				}
				/*  */
				var cantidad_envase = (recargable == 0) ? 0 : parseInt($(tr).attr('cantidad_envase'));
				var precio_envase = (recargable == 0) ? 0 : parseFloat($(tr).attr('precio_envase'));
				if(stock >= (cantidad_nueva + cantidad_envase)){
					$(tr).attr('cantidad',cantidad_nueva);
					var acumulado = parseFloat(( cantidad_nueva * precio_actual) + (cantidad_envase * precio_envase));
					recargable == 0
						? $(tr).find('.precioacumulado').html((acumulado).toFixed(2))
						: $(tr).find('.precioacumulado').html((cantidad_nueva * precio_actual) + " + " + (cantidad_envase * precio_envase) + " = " + (acumulado).toFixed(2));
					$(tr).find('.btnEliminar').attr('precio',(acumulado).toFixed(2));
					$(tr).attr('total',(acumulado).toFixed(2));
					calcularTotal(true);
				}else{
					swal({
						title: 'NO HAY STOCK SUFICIENTE',
						type: 'error',
					});
					$(this).val(cantidad_actual);
				}
			}else{
				swal({
					title: 'LOS CAMPOS DE CANTIDAD DEBEN SER NÚMEROS POSITIVOS',
					type: 'error',
				});
				$(this).val(cantidad_actual);
			}
		}else{
			swal({
				title: 'LOS CAMPOS DE CANTIDAD DEBEN SER NUMÉRICOS',
				type: 'error',
			});
			$(this).val(cantidad_actual);
		}
	});
}
function habilitarCantidadEnvaseEditable(){
	$(".cantidad_envase_editable, .cantidad_envase_editable_decimal").blur(function() {
		var cantidad_envase_nueva = parseInt($(this).val());
		var tr = $(this).parent().parent();
		var cantidad_envase = $(tr).attr('cantidad_envase');
		var recargable = $(tr).attr('recargable');
		var decimal = $(tr).attr('decimal');
		if(is_numeric(cantidad_envase_nueva)){
			if(cantidad_envase_nueva >= 0){
				var precio_actual = parseFloat($(tr).attr('precio'));
				/* GERSON (05/11/22) */
				if(decimal=='null' || decimal=='0'){
					var cantidad_actual = parseInt($(tr).attr('cantidad'));
					var stock = parseInt($(tr).attr('stock'));
				}else{
					var cantidad_actual = parseFloat($(tr).attr('cantidad'));
					var stock = parseFloat($(tr).attr('stock'));
				}
				/*  */
				var precio_envase = parseFloat($(tr).attr('precio_envase'));
				if(stock >= (cantidad_actual + cantidad_envase_nueva)){
					$(tr).attr('cantidad_envase',cantidad_envase_nueva);
					var acumulado = parseFloat((cantidad_actual * precio_actual) + (cantidad_envase_nueva * precio_envase));
					$(tr).find('.precioacumulado').html((cantidad_actual * precio_actual) + " + " + (cantidad_envase_nueva * precio_envase) + " = " + (acumulado).toFixed(2));
					$(tr).find('.btnEliminar').attr('precio',(acumulado).toFixed(2));
					$(tr).attr('total',(acumulado).toFixed(2));
					calcularTotal(true);
				}else{
					swal({
						title: 'NO HAY STOCK SUFICIENTE',
						type: 'error',
					});
					$(this).val(cantidad_envase);
				}
			}else{
				swal({
					title: 'LOS CAMPOS DE CANTIDAD DEBEN SER NÚMEROS POSITIVOS',
					type: 'error',
				});
				$(this).val(cantidad_envase);
			}
		}else{
			swal({
				title: 'LOS CAMPOS DE CANTIDAD DEBEN SER NUMÉRICOS',
				type: 'error',
			});
			$(this).val(cantidad_envase);
		}
	});
}
function habilitarPrecioEnvaseEditable(){
	$(".precio_envase_editable").blur(function() {
		var precio_nuevo = parseFloat($(this).val());
		var tr = $(this).parent().parent();
		var precio_envase = $(tr).attr('precio_envase');
		var decimal = $(tr).attr('decimal');
		if(is_numeric(precio_nuevo)){
			if(precio_nuevo >= 0){
				var precio_actual = parseFloat($(tr).attr('precio'));
				/* GERSON (05/11/22) */
				if(decimal=='null' || decimal=='0'){
					var cantidad_actual = parseInt($(tr).attr('cantidad'));
				}else{
					var cantidad_actual = parseFloat($(tr).attr('cantidad'));
				}
				/*  */
				var cantidad_envase = parseInt($(tr).attr('cantidad_envase'));
				if(precio_nuevo >= precio_actual ){
					$(tr).attr('precio_envase',(precio_nuevo).toFixed(2));
					var acumulado = parseFloat((cantidad_actual * precio_actual) + (cantidad_envase * precio_nuevo));
					$(tr).find('.precioacumulado').html((cantidad_actual * precio_actual) + " + " + (cantidad_envase * precio_nuevo) + " = " + (acumulado).toFixed(2));
					$(tr).find('.btnEliminar').attr('precio',(acumulado).toFixed(2));
					$(tr).attr('total',(acumulado).toFixed(2));
					calcularTotal(true);
				}else{
					swal({
						title: 'EL PRECIO DEL PRODUCTO CON ENVASE DEBE SER MAYOR AL PRECIO NORMAL',
						type: 'error',
					});
					$(this).val(precio_envase);
				}
			}else{
				swal({
					title: 'LOS CAMPOS PRECIOS EDITABLES DEBEN SER MONTOS POSITIVOS',
					type: 'error',
				});
				$(this).val(precio_envase);
			}
		}else{
			swal({
				title: 'LOS CAMPOS PRECIOS EDITABLES DEBEN SER NÚMERICOS',
				type: 'error',
			});
			$(this).val(precio_envase);
		}
	});
}

function calcularTotal(caja_abierta){
	$('#divMensajeErrorVenta').html('');
	var total = 0;
	$("#detalle_prod tr").each(function(){
		total += parseFloat($(this).attr('total'));
	});
	if($('#vale_balon_fise').parent().hasClass('checked')){
		total -= parseFloat($('#monto_vale_fise').val());
	}
	if($('#vale_balon_subcafae').parent().hasClass('checked')) { 
		var primero = true;
		$("#detalle_prod tr").each(function(){
			var id = parseInt($(this).attr('id'));
			if((id == "5" || id == "4") && primero == true){
				primero = false;
				total -= parseFloat($(this).attr('precio'));
			}
		});
	}
	if($('#vale_balon_monto').parent().hasClass('checked') && $('#monto_vale_balon').val() != ""){
		total -= parseFloat($('#monto_vale_balon').val() );
	}
	$("#total").val(total.toFixed(2));
	var total_pago = parseFloat($("#total_pago").val());
	if(total == 0 && caja_abierta){
		$('#btnGuardar').prop('disabled', false);
	}else if(!caja_abierta){
		var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';
		$('#divMensajeErrorVenta').html(cadenaError);
		$('#btnGuardar').prop('disabled', true);
	}else{
		if($('#pedido_credito').parent().hasClass('checked')){
			$('#btnGuardar').prop('disabled', false)
		}else{
			if(total_pago >= total){
				$('#vuelto').val((total_pago - total).toFixed(2));
				$('#btnGuardar').prop('disabled', false)
			}else{
				$('#vuelto').val((0).toFixed(2));
				$('#btnGuardar').prop('disabled', true);
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>El monto TOTAL DE PAGOS debe ser igual o mayor al TOTAL.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
			}
		}
	}
}

//* PAGOS 
function agregarPago(){
	$('#divMensajeErrorVenta').html('');
	var cboMetodoPago = document.getElementById("metodopago_id");
	var metodopago_id = $("#metodopago_id").val();
	if($("#monto_pago").val()){
		var monto_pago = parseFloat($("#monto_pago").val());
		var total_pago = parseFloat($("#total_pago").val());
		var total = parseFloat($("#total").val());
		$("#total_pago").val((total_pago + monto_pago).toFixed(2));
		var nuevo_pago = '<tr metodopago_id="' + metodopago_id + '" monto="' + monto_pago + '"><td>' 
		+ cboMetodoPago.options[cboMetodoPago.selectedIndex].text + '</td><td>' + monto_pago.toFixed(2)  + '</td>'
		+ '<td><button class="btn btn-danger btn-sm" onclick="borrarPago(' + monto_pago + ', this)"><i class="glyphicon glyphicon-trash"></i></button></td>'
		+'</tr>';
		$("#detalle_pago").append(nuevo_pago);
		$(".tabla_pagos").css('display', '');
		$("#monto_pago").val('');
		calcularTotal(true);
	}
}
function borrarPago(monto_borrar, comp){
	$('#divMensajeErrorVenta').html('');
	var total_pago = parseFloat($("#total_pago").val());
	$("#total_pago").val(total_pago - monto_borrar);
	//* Remover tr detalle_prod producto
	(($(comp).parent()).parent()).remove();
	if(total_pago == monto_borrar){
		$('#vuelto').val((0).toFixed(2));
		$(".tabla_pagos").css('display', 'none');
	}
	calcularTotal(true);
}

//* Funcion para habilitar agregar producto
function clickProducto(){
	$(".producto").on('click', function(){
		var idproducto = $(this).attr('id');
		var precio = parseFloat($(this).attr('precio'));
		var precio_envase = parseFloat($(this).attr('precio_envase'));
		var descripcion = $(this).attr('descripcion');
		var editable = $(this).attr('editable');
		var recargable = $(this).attr('recargable');
		var stock = $(this).attr('stock');
		/* GERSON (26/11/22) */
		var decimal = $(this).attr('decimal');
		if(decimal=='null' || decimal=='0'){
			var decimal = 0;
			var detalle_cantidad = parseInt($(this).attr('cantidad'));
			var detalle_stock = parseInt($(this).attr('stock'));
		}else{
			var decimal = 1;
			var detalle_cantidad = parseFloat($(this).attr('cantidad'));
			var detalle_stock = parseFloat($(this).attr('stock'));
		}
		/*  */
		//* Pintar producto por 0.3seg
		$(this).css('background', 'rgb(179,188,237)');
		setTimeout(function () {
			$('.producto').css('background', 'rgb(255,255,255)');
		}, 300);

		var existe_producto = false;
		var cantidad_productos = $("#cantidad_productos").val();
		if(cantidad_productos != 0){
			$("#detalle_prod tr").each(function(){
				if(idproducto == this.id){
					existe_producto = true;
					var detalle_precio = parseFloat($(this).attr('precio'));
					var detalle_cantidad_envase = parseFloat($(this).attr('cantidad_envase'));
					var detalle_precio_envase = parseFloat($(this).attr('precio_envase'));
					if(recargable == 0){
						var detalle_cantidad_total = detalle_cantidad;
					}else{
						var detalle_cantidad_total = detalle_cantidad + detalle_cantidad_envase;
					}
					if(detalle_cantidad_envase == 0){
						detalle_cantidad_envase = "";
					}
					if(detalle_stock > detalle_cantidad_total){
						detalle_cantidad++;
						$(this).attr('cantidad',detalle_cantidad);
						var detalle_producto = '<td>' + descripcion + '</td>';
						decimal == 1
							? detalle_producto += '<td><input type="number" class="form-control input-xs cantidad_editable_decimal inputDetProducto" value="' + detalle_cantidad + '"></td>'
							: detalle_producto += '<td><input type="number" class="form-control input-xs cantidad_editable inputDetProducto" value="' + detalle_cantidad + '"></td>';
						editable == 0 
							? detalle_producto += '<td>'+ (precio).toFixed(2)+'</td>'
							: detalle_producto += '<td><input type="number" class="form-control input-xs precio_editable inputDetProducto" value="'+ (detalle_precio).toFixed(2) +'"></td>';
						if(recargable == 1){
							decimal == 1
								? detalle_producto += '<td>Cant: <input type="number" class="form-control input-xs cantidad_envase_editable_decimal inputDetProducto" value="'+ detalle_cantidad_envase +'"> Precio S/. <input type="number" class="form-control input-xs precio_envase_editable inputDetProducto" value="'+ (detalle_precio_envase).toFixed(2) +'"></td>'
								: detalle_producto += '<td>Cant: <input type="number" class="form-control input-xs cantidad_envase_editable inputDetProducto" value="'+ detalle_cantidad_envase +'"> Precio S/. <input type="number" class="form-control input-xs precio_envase_editable inputDetProducto" value="'+ (detalle_precio_envase).toFixed(2) +'"></td>';
							var acumulado = parseFloat( ( detalle_cantidad * detalle_precio ) + ( detalle_cantidad_envase * detalle_precio_envase) );
							detalle_producto += '<td class="precioacumulado">'+ ((detalle_cantidad - detalle_cantidad_envase) * detalle_precio) + " + " + ( detalle_cantidad_envase * detalle_precio_envase) +  " = " +(acumulado).toFixed(2) +'</td>';
						}else{
							detalle_producto += '<td>-</td>';
							var acumulado = parseFloat( (editable == 0 ? precio : detalle_precio)  * detalle_cantidad );
							detalle_producto += '<td class="precioacumulado">'+ (acumulado).toFixed(2) +'</td>';
						}
						detalle_producto += '<td><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto=' + idproducto + ' precio=' + (acumulado).toFixed(2) + ' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
						$(this).attr('total',(acumulado).toFixed(2));
						$(this).attr('decimal',decimal);
						$(this).html(detalle_producto);
					}else{
						swal({
							title: 'NO HAY STOCK SUFICIENTE',
							type: 'error',
						});
					}
				}
			});
		}

		//* Si no existe el producto en el detalle_prod
		if(!existe_producto){
			cantidad_productos++;
			$("#cantidad_productos").val(cantidad_productos);
			recargable == 1
				? nuevo_detalle_producto = '<tr id="' + idproducto + '" cantidad="' + 1 + '" total="' + (precio).toFixed(2) + '" precio="' + (precio).toFixed(2) + '" stock="' + stock + '" recargable="' + recargable + '" cantidad_envase="' + 0 + '" precio_envase="' + (precio_envase).toFixed(2) + '" decimal="' + decimal + '">'
				: nuevo_detalle_producto = '<tr id="' + idproducto + '" cantidad="' + 1 + '" total="' + (precio).toFixed(2) + '" precio="' + (precio).toFixed(2) + '" stock="' + stock + '" recargable="' + recargable + '" decimal="' + decimal + '">';
			decimal == 1
				? nuevo_detalle_producto += '<td>'+ descripcion + '</td><td><input type="number" class="form-control input-xs cantidad_editable_decimal inputDetProducto" value="' + 1 + '"></td>'
				: nuevo_detalle_producto += '<td>'+ descripcion + '</td><td><input type="number" class="form-control input-xs cantidad_editable inputDetProducto" value="' + 1 + '"></td>';
			editable == 0
				? nuevo_detalle_producto += '<td>' + (precio).toFixed(2) + '</td>'
				: nuevo_detalle_producto += '<td><input type="number" class="form-control input-xs precio_editable inputDetProducto" value="' + (precio).toFixed(2) + '"></td>';
			if(recargable == 1){
				decimal == 1
					? nuevo_detalle_producto += '<td>Cant: <input type="number" class="form-control input-xs cantidad_envase_editable_decimal inputDetProducto"> Precio S/. <input type="number" class="form-control input-xs precio_envase_editable inputDetProducto" value="' + (precio_envase).toFixed(2) + '"></td>' 
					: nuevo_detalle_producto += '<td>Cant: <input type="number" class="form-control input-xs cantidad_envase_editable inputDetProducto"> Precio S/. <input type="number" class="form-control input-xs precio_envase_editable inputDetProducto" value="' + (precio_envase).toFixed(2) + '"></td>' ;
				nuevo_detalle_producto += '<td class="precioacumulado">' + precio + " + 0 = " + (precio).toFixed(2) + '</td>';
			}else if(recargable == 0){
				nuevo_detalle_producto += '<td>-</td>'
				+ '<td class="precioacumulado">' + (precio).toFixed(2) + '</td>';
			}
			nuevo_detalle_producto += '<td><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto=' + idproducto + ' precio=' + (precio).toFixed(2) + ' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
			$("#detalle_prod").append(nuevo_detalle_producto);
		}

		$(".cantidad_editable").keypress(function(evt){
			var charCode = (evt.which) ? evt.which : event.keyCode;
			if(charCode > 31 && (charCode < 48 || charCode > 57)){
				return false;
			}
			return true;
		});
		$(".cantidad_envase_editable").keypress(function(evt){
			var charCode = (evt.which) ? evt.which : event.keyCode;
			if(charCode > 31 && (charCode < 48 || charCode > 57)){
				return false;
			}
			return true;
		});

		habilitarPrecioEditable();
		habilitarCantidadEditable();
		habilitarPrecioEnvaseEditable();
		habilitarCantidadEnvaseEditable();

		//* Habilitar vales de balon
		if(idproducto == 4 || idproducto == 5){
			$('#activar_checkbox').val(1);
			clickVales();
			desbloquearChecksVales();
			desmarcarValeFise();
			desmarcarValeSubcafae();
			desmarcarValeMonto();
			limpiarCamposVales();
			ocultarCamposVales();
			$("#detalle_prod tr").css('background-color', '');
		}
		calcularTotal(true);
	});
}

function eliminarDetalle(comp){
	var idproducto = $(comp).attr('idproducto');
	if(idproducto == 4 || idproducto == 5 ){
		$('#activar_checkbox').val(0);
		activar_checkbox = $('#activar_checkbox').val();
		bloquearChecksVales();
		desmarcarValeFise();
		desmarcarValeSubcafae();
		desmarcarValeMonto();
		limpiarCamposVales();
		ocultarCamposVales();
		$("#detalle_prod tr").css('background-color', '');
	}

	var cantidad_productos = $("#cantidad_productos").val() - 1;
	$("#cantidad_productos").val(cantidad_productos);

	//* Remover tr detalle_prod producto
	(($(comp).parent()).parent()).remove();
	
	$("#detalle_prod tr").each(function(){
		var idproducto = $(this).attr('id');
		if(idproducto == 4 || idproducto == 5 ){
			$('#activar_checkbox').val(1);
			activar_checkbox = $('#activar_checkbox').val();
			desbloquearChecksVales();
			clickVales();
		}
	});
	calcularTotal(true);
}

function guardarPedido(){
	var cboSucursal = document.getElementById("sucursal_id");
	var sucursal_id = $('#sucursal_id').val();
	var venta_sucursal = $('#venta_sucursal').parent().hasClass('checked');
	var empleado = $('#empleado_id').val();
	var cliente = $('#cliente_id').val();
	var cantidad_productos = parseInt($("#cantidad_productos").val());

	var vale_fise = $("#vale_balon_fise").parent().hasClass('checked') && $("#codigo_vale_fise").val() == ""; //* Vale fise checkeado con codigo vacío 
	var vale_subcafae = $("#vale_balon_subcafae").parent().hasClass('checked') && $("#codigo_vale_subcafae").val() == ""; //* Vale subcafae checkeado con codigo vacío 
	var vale_monto = $("#vale_balon_monto").parent().hasClass('checked') && ($("#codigo_vale_monto").val() == "" || $("#monto_vale_balon").val() == ""); //* Vale monto checkeado con codigo o monto vacío 

	var tipo = $('#tipodocumento_id').val();
	var letra = "";
	if(tipo == 1){
		letra ="BV";
	}else if(tipo == 2){
		letra ="FV";
	}else if(tipo == 3){
		letra ="TK";
	}

	var total = parseFloat($("#total").val());
	var total_pago = parseFloat($("#total_pago").val());

	if((!empleado && !venta_sucursal) || cantidad_productos == 0 || !cliente || vale_fise || vale_subcafae || vale_monto){
		var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
		if(!empleado  && !venta_sucursal) cadenaError += '<li> El campo Repartidor es obligatorio.</li>';
		if(!cliente) cadenaError += '<li> El campo Cliente es obligatorio.</li>';
		if(cantidad_productos ==0) cadenaError += '<li>Debe agregar mínimo un producto.</li>';
		if(vale_fise) cadenaError += '<li>Ingresa Código de vale FISE.</li>';
		if(vale_subcafae) cadenaError += '<li>Ingresa Código de vale SUBCAFAE.</li>';
		if(vale_monto){
			if($("#codigo_vale_monto").val() == "") cadenaError += '<li>Ingresa Código de vale monto.</li>';
			if($("#monto_vale_balon").val() == "") cadenaError += '<li>Ingresa Monto de descuento de vale monto.</li>';
		}
		cadenaError += "</ul></div>";
		$('#divMensajeErrorVenta').html(cadenaError);
	}else{
		$('#divMensajeErrorVenta').html('');
		var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ cboSucursal.options[cboSucursal.selectedIndex].text 
						+"</p><p><label>N° Venta: </label>  " + letra + $('#serieventa').val() ;

		if( $("#vale_balon_fise").prop('checked') ){
			mensaje += "</p><p><label>Vale:  </label>  " + "FISE"
			+"</p><p><label>Código FISE:  </label>  " + $('#codigo_vale_fise').val()
		}

		if( $("#vale_balon_subcafae").prop('checked') ){
			mensaje += "</p><p><label>Vale:  </label>  "+ "SUBCAFAE"
			+"</p><p><label>Código SUBCAFAE:  </label>  "+ $('#codigo_vale_subcafae').val()
		}

		if( $("#vale_balon_monto").prop('checked') ){
			mensaje += "</p><p><label>Vale:  </label>  " + "VALE MONTO"
			+"</p><p><label>Código MONTO:  </label>  " + $('#codigo_vale_monto').val()
			+"</p><p><label>Monto del Vale:  </label>  S/."+ parseFloat($('#monto_vale_balon').val()).toFixed(2)
		}

		venta_sucursal
			? mensaje +="</p><p><label align='center'>VENTA EN SUCURSAL</label>"
			: mensaje +="</p><p><label>Empleado:  </label>  " + $('#empleado_nombre').val();

		mensaje +="</p><p><label>Cliente:  </label>  " + $('#cliente').val()
				+"</p><p><label>Dirección:  </label>  " + $('#cliente_direccion').val();

		if($('#comentario').val() != "") mensaje += "</p><p><label>Comentario:  </label>  " + $('#comentario').val();

		mensaje +="</p><p><label>Total:  </label>  S/." +  total.toFixed(2);

		if($("#pedido_credito").prop('checked')){
			mensaje += "</p><p><label>Pedido a crédito:  </label>  " + "SI"
					+ "</p><p><label>Monto a cobrar:  </label>  S/."+   (total_pago).toFixed(2)
					+ "</p><p><label>Cuenta por cobrar:  </label>  S/." +  (total - total_pago).toFixed(2);
		}

		mensaje += "</p></div>" ;

		swal({
			title: 'Confirmar Guardado',
			html: mensaje,
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#54b359',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar Venta'
		}).then((result) => {
			if (result.value) {
				guardarVenta();
			}
		});
	}
}

function guardarVenta() {
	var params = $('#IDFORMMANTENIMIENTOVenta').serializeArray();

	var det_productos = [];
	$("#detalle_prod tr").each(function(){
		var id = parseInt($(this).attr('id'));
		var total = parseFloat($(this).attr('total'));
		//var cantidad = parseInt($(this).attr('cantidad'));
		/* GERSON (05/11/22) */
		var cantidad = parseFloat($(this).attr('cantidad'));
		/*  */
		var precio = parseFloat($(this).attr('precio'));
		if( $(this).attr('cantidad_envase') != undefined ){
			var cantidad_envase = parseInt($(this).attr('cantidad_envase'));
			var precio_envase = parseFloat($(this).attr('precio_envase'));
		}else{
			var cantidad_envase = "";
			var precio_envase = "";
		}
		det_productos.push({
			"id": id , 
			"cantidad": cantidad, 
			"precio": precio , 
			"cantidad_envase": cantidad_envase, 
			"precio_envase": precio_envase,
			"total": total,
		});
	});

	var det_pagos = [];
	$("#detalle_pago tr").each(function(){
		var metodopago_id = parseInt($(this).attr('metodopago_id'));
		var monto = parseFloat($(this).attr('monto'));
		det_pagos.push({
			"metodopago_id": metodopago_id,
			"monto": monto,
		});
	});

	params.push({ 
		name: "det_productos", 
		value: JSON.stringify(det_productos) 
	}); 
	params.push({ 
		name: "det_pagos", 
		value: JSON.stringify(det_pagos) 
	}); 

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/guardarventa') }}",
		"data": jQuery.param(params),
	}).done(function(data){
		if (data === 'OK') {
			mostrarMensaje('Accion realizada correctamente', 'OK');
			cargarRutaMenu('pedidos_actual', 'container', '15');
		} else {
			console.log("Error: " + data)
		}
	});
}
</script>