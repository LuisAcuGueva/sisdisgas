<?php $hoy = date("Y-m-d"); ?>
<style>
	.movalm-inputs{
		height: 12px; 
		margin: 25px 0px;
	}
	.tbl-span{
		display: block; 
		font-size:.9em;
	}
	.table-producto td{
		text-align: center;
	}
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
		-webkit-appearance: none; 
		margin: 0; 
	}
	input[type=number] { -moz-appearance:textfield; }
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('detalle', 'false', array( 'id' => 'detalle')) !!}
	<input type="hidden" name="cantproductos" id="cantproductos">
	<div class="col-lg-4 col-md-4 col-sm-4">
		<div class="form-group movalm-inputs">
			{!! Form::label('sucursal', 'Sucursal:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::select('sucursal', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal')) !!}
			</div>
		</div>
		<div class="form-group movalm-inputs">
			{!! Form::label('tipo', 'Tipo:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::select('tipo', $cboTipo, null, array('style' => 'background-color: #fcffd4;' ,'class' => 'form-control input-sm', 'id' => 'tipo')) !!}
			</div>
		</div>
		<div class="form-group movalm-inputs">
			{!! Form::label('tipodocumento_id', 'Documento:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('tipodocumento_id', $cboDocumento, null, array('style' => 'background-color: #D4F0FF;' ,'class' => 'form-control input-sm', 'id' => 'tipodocumento_id')) !!}
			</div>
		</div>
		<div class="form-group movalm-inputs">
			{!! Form::label('numerodocumento', 'Nro Doc:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('serie', null, array('class' => 'form-control input-sm', 'id' => 'serie', 'placeholder' => 'Serie', 'data-inputmask' => "'mask': '9999'")) !!} 
			</div> 
			<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left: 0px;">
				{!! Form::text('numerodocumento', null, array('class' => 'form-control input-sm', 'id' => 'numerodocumento', 'placeholder' => 'Número', 'data-inputmask' => "'mask': '9999999'")) !!}
			</div>
		</div>
		<div class="form-group movalm-inputs">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<input class="form-control input-sm" id="fecha" placeholder="Ingrese Fecha" name="fecha" type="date" value="{{ $hoy }}" readOnly="readOnly">
			</div>
		</div>
		<div class="form-group movalm-inputs" style="display:none;">
			{!! Form::label('total', 'Total:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('total', number_format(0, 2, '.', ''), array('style' => 'background-color: #FFEEC5;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'total' )) !!}
			</div>
		</div>
		<div class="form-group movalm-inputs">
			{!! Form::label('comentario', 'Comentario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<textarea class="form-control input-xs" id="comentario" cols="10" rows="5" name="comentario"></textarea>
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="form-group col-lg-8 col-md-8 col-sm-8" style="height: 12px; margin-top: 28px;">
			{!! Form::label('nombreproducto', 'Producto:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::text('nombreproducto', null, array('class' => 'form-control input-sm', 'id' => 'nombreproducto', 'placeholder' => 'Ingrese nombre','onkeyup' => 'buscarProducto($(this).val());')) !!}
			</div>
			<div class="col-lg-0 col-md-0 col-sm-0">
                {!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO PRODUCTO' , 'onclick' => 'modalCaja (\''.URL::route('producto.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Producto\', this);')) !!}
    		</div>
			{!! Form::hidden('producto_id', null, array( 'id' => 'producto_id')) !!}
			{!! Form::hidden('stock', null, array('id' => 'stock')) !!}
			{!! Form::hidden('envases_vacios', null, array('id' => 'envases_vacios')) !!}
			{!! Form::hidden('recargable', null, array('id' => 'recargable')) !!}
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12" id="divProductos" style="margin-top: 20px; overflow:auto; height:180px;">
			<table class='table-condensed table-hover table-producto' border='1'>
				<thead>
					<tr>
						<th class='text-center' style='width:300px;'>Nombre</th>
						<th class='text-center' style='width:110px;'>Precio Compra</th>
						<th class='text-center' style='width:110px;'>Precio Venta</th>
						<th class='text-center' style='width:110px;'>Stock</th>
						<th class='text-center' style='width:110px;'>Recargable</th>
						<th class='text-center' style='width:110px;'>Envases Vacíos</th>
					</tr>
				</thead>
				<tbody id='tablaProducto'>
					<tr><td colspan='6'>Digite más de 3 caracteres.</td></tr>
				</tbody>
			</table>
		</div>
		<div class="form-group col-lg-12 col-md-12 col-sm-12">
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_compra', 'Precio Compra:', array('class' => 'control-label')) !!}
				{!! Form::number('precio_compra', null, array('class' => 'form-control input-sm', 'id' => 'precio_compra' )) !!}
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_venta', 'Precio Venta:', array('class' => 'control-label')) !!}
				{!! Form::number('precio_venta', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta' )) !!}
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('cantidad', 'Cantidad:', array('class' => 'control-label')) !!}
				{!! Form::number('cantidad', null, array('class' => 'form-control input-sm', 'id' => 'cantidad' )) !!}
			</div>
		</div>
		<div class="form-group col-lg-12 col-md-12 col-sm-12 divEnvase" style="display: none;">
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_compra_envase', 'Precio Compra Envase:', array('class' => 'control-label')) !!}
				{!! Form::number('precio_compra_envase', null, array('class' => 'form-control input-sm', 'id' => 'precio_compra_envase')) !!}
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_venta_envase', 'Precio Venta Envase:', array('class' => 'control-label')) !!}
				{!! Form::number('precio_venta_envase', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta_envase')) !!}
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('cantidad_envase', 'Cantidad Envases:', array('class' => 'control-label')) !!}
				{!! Form::number('cantidad_envase', null, array('class' => 'form-control input-sm', 'id' => 'cantidad_envase')) !!}
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 text-center">
			{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Agregar Producto', array('class' => 'btn btn-warning btn-sm float-right', 'style' => 'height: 30px; margin-bottom: 1px;', 'id' => 'agregarProducto')) !!}
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 20px;">
			<div id="divDetail" class="table-responsive" style="overflow:auto; border:1px outset">
		        <table style="width: 100%;" class="table-condensed table-striped" border="1">
		            <thead>
		                <tr>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:50px;">N°</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:350px;">Producto</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:120px;">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:120px;">Precio</th>
							<th bgcolor="#E0ECF8" class='text-center' style="width:120px;">Cantidad Envases</th>
							<th bgcolor="#E0ECF8" class='text-center' style="width:120px;">Precio Envase</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:120px;">Subtotal</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:100px;">Eliminar</th>
		                </tr>
		            </thead>
		            <tbody id="detallesCompra">
		            </tbody>
		            <tbody border="1">
		            	<tr>
		            		<th colspan="7" style="text-align: right;">TOTAL</th>
		            		<td class="text-center">
		            			<center id="totalcompra2">0.00</center><input type="hidden" id="totalcompra" readonly="" name="totalcompra" value="0.00">
		            		</td>
		            	</tr>
		            </tbody>		           
		        </table>
		    </div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 text-right" style="margin-top: 10px;">
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarMovAlmacen(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	 </div>	
{!! Form::close() !!}

<script>
$(document).ready(function() {
	$('#detallesCompra').html('');
	$('#cantproductos').val('0');
	configurarAnchoModal('875');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$("#serie").inputmask({"mask": "9999"});
	$("#numerodocumento").inputmask({"mask": "9999999"});
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
}); 

$('#tipo').on('change', function(){
	$("#detallesCompra").html("");
	$('#nombreproducto').focus();
	calculatetotal();
	generarTipodoc();
});

$('#sucursal').on('change', function(){
	$("#nombreproducto").val("");
	$("#tablaProducto").html("<tr><td colspan='6'>Digite más de 3 caracteres.</td></tr>");
});

$('#agregarProducto').on('click', function(){
	agregarCarrito(); 
});

function escogerFila(){
	$('.escogerFila').on('click', function(){
		$('.escogerFila').css('background-color', 'white');
		$(this).css('background-color', 'yellow');
	});
}

function quitarFila(){
	$('.quitarFila').on('click', function(){
		event.preventDefault();
		$(this).parent('span').parent('td').parent('tr').remove();
		calculatetotal();
	});
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function generarTipodoc() {
	var tipo = $("#tipo").val();
	var tiposdoc = "";
		var select = "";
	$.ajax({
		"method": "POST",
		"url": "{{ url('/movalmacen/generartipodocumento') }}",
		"data": {
			"tipo" : tipo, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		tiposdoc = info;
		$.each(tiposdoc, function(i, item) {
			select += '<option value="' + item.id + '">'+ item.abreviatura + ' - ' + item.descripcion + '</option>';
		});
		$("#tipodocumento_id").html(select);
	});
}

function buscarProducto(valor){
    if(valor.length >= 3){
        $.ajax({
            type: "POST",
            url: "movalmacen/buscandoproducto",
            data: "nombre="+$("#nombreproducto").val() + "&sucursal="+$("#sucursal").val() + "&_token="+$('input[name=_token]').val(),
            success: function(a) {
                datos=JSON.parse(a);
                var a = '';
                if(datos.length > 0) {
	                for(c=0; c < datos.length; c++){
	                    a +="<tr style='cursor:pointer' class='escogerFila' id='"+datos[c].idproducto+"' onclick=\"seleccionarProducto('"+datos[c].idproducto+"')\"><td><span class='tbl-span'>"+datos[c].nombre+"</span></td><td><span class='tbl-span'>"+datos[c].precio_compra+"</span></td><td><span class='tbl-span'>"+datos[c].precio_venta+"</span></td><td><span class='tbl-span'>"+datos[c].stock+"</span></td><td><span class='tbl-span'>"+datos[c].recargable+"</span></td><td><span class='tbl-span'>"+datos[c].envases_vacios+"</span></td></tr>";
	                }	                
	            } else {
	            	a +="<tr><td colspan='6'>Productos no encontrados.</td></tr>";
	            }
	            $("#tablaProducto").html(a);
				escogerFila();
    	    }
        });
    } else {
    	$("#tablaProducto").html("<tr><td colspan='6'>Digite más de 3 caracteres.</td></tr>");
    }
}

function seleccionarProducto(idproducto){
	var _token =$('input[name=_token]').val();
	$.post(
		'{{ URL::route("movalmacen.consultaproducto")}}',
		{
			idproducto: idproducto, 
			sucursal_id: $('#sucursal').val(),
			_token: _token
		}, 
	function(data){
		var datos = data.split('@');
		$("#producto_id").val(datos[0]);
		$("#precio_compra").val(datos[1]);
		$("#precio_venta").val(datos[2]);
		$("#precioventa").val(datos[2]);
		$("#stock").val(datos[3]); 
		$("#recargable").val(datos[4]);
		$("#envases_vacios").val(datos[7]);
		if(datos[4] == 1){
			$(".divEnvase").css('display','');
			$("#precio_compra_envase").val(datos[5]);
			$("#precio_venta_envase").val(datos[6]);
			$("#total_envases").val(datos[9]);
		}else{
			$(".divEnvase").css('display','none');
		}
	});
	$("#cantidad").focus();
}

function agregarCarrito(){
	var cantidad = $('#cantidad').val();
	var cantidad_envase = $('#cantidad_envase').val();
	var total_envases = $('#total_envases').val();
	var precio_compra = $('#precio_compra').val();
	var precio_compra_envase = $('#precio_compra_envase').val();
	var precio_venta = $('#precio_venta').val();
	var precio_venta_envase = $('#precio_venta_envase').val();
	var product_id = $('#producto_id').val();
	var tipo = $('#tipo').val();
	var stock = $('#stock').val();
	var recargable = parseInt($('#recargable').val());
	var envases_vacios = $('#envases_vacios').val();

	if( cantidad ==""){
		cantidad = 0;
	}
	if( cantidad_envase ==""){
		cantidad_envase = 0;
	}

	if( cantidad =="" && cantidad_envase =="" ){
		swal({
			type: 'error',
			title: 'INGRESE CANTIDAD',
			});
		return false;
	}
	
	var _token =$('input[name=_token]').val();

	if(product_id=="" || product_id=="0"){
		swal({
			type: 'error',
			title: 'SELECCIONE UN PRODUCTO',
			});
	}else if(parseFloat(precio_venta) < parseFloat(precio_compra)){
		swal({
			type: 'error',
			title: 'INGRESE UN PRECIO DE VENTA MAYOR AL DE COMPRA',
			});
	}else if(!is_numeric(cantidad)){
		swal({
			type: 'error',
			title: 'CANTIDAD DEBE SER UN VALOR NÚMERICO',
			});
	}else if(precio_compra.trim() == '' ){
		swal({
			title: 'INGRESE PRECIO DE COMPRA',
			type: 'error',
			});
	}else if(precio_compra.trim() == 0){
		swal({
			type: 'error',
			title: 'EL PRECIO DE COMPRA DEBE SER MAYOR A 0',
			});
	}else if(!is_numeric(precio_compra)){
		swal({
			type: 'error',
			title: 'EL PRECIO DE COMPRA DEBE SER UN VALOR NÚMERICO',
			});
	}else if(precio_venta.trim() == 0){
		swal({
			type: 'error',
			title: 'EL PRECIO DE VENTA DEBE SER MAYOR A 0',
			});
	}else if(precio_venta.trim() == '' ){
		swal({
			title: 'INGRESE PRECIO DE VENTA',
			type: 'error',
			});
	}else if(!is_numeric(precio_venta)){
		swal({
			type: 'error',
			title: 'EL PRECIO DE VENTA DEBE SER UN VALOR NÚMERICO',
			});
	}else{
		stock = parseInt( $('#stock').val() );
		cantidad = parseInt( $('#cantidad').val() );
		if(tipo == "E"){
			if(stock < cantidad || stock < cantidad_envase){
				swal({
					type: 'error',
					title: 'NO HAY SUFICIENTE STOCK',
					});
				$('#cantidad').val('');
				$('#cantidad').focus();
			}else{
				$.post('{{ URL::route("movalmacen.agregarcarritocompra")}}', {
					tipo: tipo, 
					cantidad: cantidad, 
					cantidad_envase: cantidad_envase, 
					precio_compra: precio_compra,
					precio_compra_envase: precio_compra_envase, 
					producto_id: product_id, 
					precio_venta: precio_venta, 
					precio_venta_envase: precio_venta_envase, 
					detalle: $('#detalle').val(),
					_token: _token
				} , function(data){
					$('#detalle').val(true);
					if(data === '0-0') {
						swal({
							type: 'error',
							title: 'NO ES UN FORMATO VÁLIDO DE CANTIDAD',
							});
						$('#cantidad').val('').focus();
						return false;
					} else {
						var producto_id = $('#producto_id').val();
						if ($("#Product" + producto_id)[0]) {
							$("#Product" + producto_id).html(data);
						} else {
							$('#detallesCompra').append('<tr id="Product' + producto_id + '">' + data + '</tr>');
						}		
						$("#Product" + producto_id).css('display', 'none').fadeIn(1000);	
						calculatetotal();
						quitarFila();
						$("#nombreproducto").val('');
						$("#cantidad").val('');
						$("#precio_compra").val('');
						$("#precio_venta").val('');
						$("#precio_compra_envase").val('');
						$("#precio_venta_envase").val('');
						$("#total_envases").val('');
						$("#cantidad_envase").val('');
						$("#nombreproducto").focus();
						$('.escogerFila').css('background-color', 'white');
						$(".divEnvase").css('display','none');
						$("#tablaProducto").html("<tr><td colspan='6'>Digite más de 3 caracteres.</td></tr>");
					}
				});
			}
		}else{
			if(recargable == 1){
				if( cantidad > envases_vacios){
					swal({
					type: 'error',
					title: 'NO HAY SUFICIENTES ENVASES VACÍOS',
					});
					$('#cantidad').val('');
					$('#cantidad').focus();
					return false;
				}
			}
			$.post(
				'{{ URL::route("movalmacen.agregarcarritocompra")}}', 
				{
					tipo: tipo, 
					cantidad: cantidad, 
					cantidad_envase: cantidad_envase, 
					precio_compra: precio_compra, 
					precio_compra_envase: precio_compra_envase, 
					producto_id: product_id, 
					precio_venta: precio_venta, 
					precio_venta_envase: precio_venta_envase, 
					detalle: $('#detalle').val(),
					_token: _token
				}, 
			function(data){
				$('#detalle').val(true);
				if(data === '0-0') {
					swal({
						type: 'error',
						title: 'NO ES UN FORMATO VÁLIDO DE CANTIDAD',
						});
					$('#cantidad').val('').focus();
					return false;
				} else {
					var producto_id = $('#producto_id').val();
					if ($("#Product" + producto_id)[0]) {
						$("#Product" + producto_id).html(data);
					} else {
						$('#detallesCompra').append('<tr id="Product' + producto_id + '">' + data + '</tr>');
					}		
					$("#Product" + producto_id).css('display', 'none').fadeIn(1000);	
					calculatetotal();
					quitarFila();
					$("#nombreproducto").val('');
					$("#cantidad").val('');
					$("#precio_compra").val('');
					$("#precio_venta").val('');
					$("#precio_compra_envase").val('');
					$("#precio_venta_envase").val('');
					$("#total_envases").val('');
					$("#cantidad_envase").val('');
					$("#nombreproducto").focus();
					$('.escogerFila').css('background-color', 'white');
					$(".divEnvase").css('display','none');
					$("#tablaProducto").html("<tr><td colspan='6'>Digite más de 3 caracteres.</td></tr>");
				}
			});
		}
	}
}

function calculatetotal() {
	var i = 1;
	var total = 0;
	$('#detallesCompra tr .numeration').each(function() {
		$(this).html(i);
		i++;
	});

	i = 1;
	$('#detallesCompra tr .infoProducto').each(function() {
		$(this).find('.producto_id').attr('name', '').attr('name', 'producto_id' + i);
		$(this).find('.productonombre').attr('name', '').attr('name', 'productonombre' + i);
		$(this).find('.cantidad').attr('name', '').attr('name', 'cantidad' + i);
		$(this).find('.cantidadenvase').attr('name', '').attr('name', 'cantidadenvase' + i);
		$(this).find('.preciocompra').attr('name', '').attr('name', 'preciocompra' + i);
		$(this).find('.preciocompraenvase').attr('name', '').attr('name', 'preciocompraenvase' + i);
		$(this).find('.precioventa').attr('name', '').attr('name', 'precioventa' + i);
		$(this).find('.precioventaenvase').attr('name', '').attr('name', 'precioventaenvase' + i);
		$(this).find('.subtotal').attr('name', '').attr('name', 'subtotal' + i);
		total += parseFloat($(this).find('.subtotal').val());
		i++;
	});
	$('#cantproductos').val(i-1);
	$('#totalcompra2').html(total.toFixed(2));
	$('#totalcompra').val(total.toFixed(2));
	$("#credito").val(total.toFixed(2));
	$('#total').val(total.toFixed(2));
}

function guardarMovAlmacen(entidad, idboton) {
	if($("#serie").val()=="" || $("#numerodocumento").val()==""){
		swal({
			title: 'DEBE INGRESAR SERIE Y NÚMERO',
			type: 'error',
			});
		$("#serie").focus();
		return false;
	}else if($("#total").val()== 0){
		swal({
			title: 'EL TOTAL DEBE SER MAYOR QUE 0 SOLES , INGRESE PRODUCTOS AL DETALLE DE LA COMPRA',
			type: 'error',
			});
		return false;
	}else{
		var sucursal = document.getElementById("sucursal");
		var tipomov = $('#tipo').val();
		var tipo = $('#tipodocumento_id').val();
		var total = parseFloat($("#total").val());
		var letra = "";
		if(tipo == 4){
			letra ="FC";
		}else if(tipo == 5){
			letra ="BC";
		}

		if( tipomov == 'I'){
			var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text 
					+"</p><p><label>Tipo de Movimiento: </label>  INGRESO"
					+"</p><p><label>N° Documento: </label>  "+ letra + $('#serie').val() + "-"+ $('#numerodocumento').val() 
					+ "</p><p><label>Total:  </label>  S/."+  total.toFixed(2) +"</p><div>" ;
		}else{
			var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text 
					+"</p><p><label>Tipo de Movimiento: </label>  SALIDA" 
					+"</p><p><label>N° Documento: </label>  "+ letra + $('#serie').val() + "-"+ $('#numerodocumento').val() 
					+ "</p><p><label>Total:  </label>  S/."+  total.toFixed(2) +"</p><div>" ;
		}

		swal({
			title: 'Confirmar Guardado',
			html: mensaje,
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#54b359',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar movimiento de almacen'
		}).then((result) => {
			if (result.value) {
				guardar("{{$entidad}}");
			}
		});
	}	
}
</script>