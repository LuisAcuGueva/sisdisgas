<?php $hoy = date("Y-m-d"); ?>
<style>
	.compra-inputs{
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
		<div class="form-group compra-inputs">
			{!! Form::label('sucursal', 'Sucursal:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::select('sucursal', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal', 'onchange' => 'permisoRegistrar();')) !!}
			</div>
		</div>
		<div class="form-group compra-inputs">
			{!! Form::label('tipodocumento_id', 'Documento:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('tipodocumento_id', $cboDocumento, null, array('style' => 'background-color: #D4F0FF;' ,'class' => 'form-control input-sm', 'id' => 'tipodocumento_id')) !!}
			</div>
		</div>
		<div class="form-group compra-inputs">
			{!! Form::label('numerodocumento', 'Nro Doc:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('serie', null, array('class' => 'form-control input-sm', 'id' => 'serie', 'placeholder' => 'Serie', 'data-inputmask' => "'mask': '9999'")) !!} 
			</div> 
			<div class="col-lg-4 col-md-4 col-sm-4" style="padding-left: 0px;">
				{!! Form::text('numerodocumento', null, array('class' => 'form-control input-sm', 'id' => 'numerodocumento', 'placeholder' => 'Número', 'data-inputmask' => "'mask': '9999999'")) !!}
			</div>
		</div>
		<div class="form-group compra-inputs">
			{!! Form::label('ccruc', 'RUC:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::text('ccruc','', array('class' => 'form-control input-sm datocaja', 'id' => 'ccruc', 'maxlength' => '11')) !!}
			</div> 
			{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO PROVEEDOR', 'onclick' => 'modalCaja (\''.URL::route('compras.proveedor', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Proveedor\', this);')) !!}
		</div>
		<div class="form-group compra-inputs">
			{!! Form::hidden('proveedor_id', null, array('id' => 'proveedor_id')) !!}
			{!! Form::hidden('ultimo_proveedor',null,array('id'=>'ultimo_proveedor')) !!}
			{!! Form::label('ccrazon', 'Razón:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::textarea('ccrazon','', array('class' => 'form-control input-sm datocaja', 'rows' => '3', 'id' => 'ccrazon')) !!}
			</div> 
			{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnclienteborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-sm btnBorrar' , 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'BORRAR')) !!}
		</div>
		<div class="form-group compra-inputs" style="padding-top: 37px;">
			{!! Form::label('ccdireccion', 'Dirección:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::textarea('ccdireccion','', array('class' => 'form-control input-sm datocaja', 'rows' => '4', 'id' => 'ccdireccion')) !!}
			</div> 	
		</div>
		<div class="form-group compra-inputs" style="padding-top: 67px; padding-bottom: 13px;">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<input class="form-control input-sm" id="fecha" placeholder="Ingrese Fecha" name="fecha" readOnly="readOnly" type="date" value="{{ $hoy }}">
			</div>
		</div>
		<div class="form-group compra-inputs" style="padding-bottom: 57px;">
			{!! Form::label('comentario', 'Comentario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<textarea class="form-control input-xs" id="comentario" cols="10" rows="3" name="comentario"></textarea>
			</div>
		</div>
		<div class="form-group compra-inputs">
			{!! Form::label('saldo_caja', 'Saldo en caja:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::text('saldo_caja', number_format(0, 2, '.', ''), array('style' => 'background-color: #c3ffd6 ;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'saldo_caja' )) !!}
			</div>
		</div>
		<div class="form-group compra-inputs">
			{!! Form::label('total', 'Total:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::text('total', number_format(0, 2, '.', ''), array('style' => 'background-color: #FFEEC5;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'total' )) !!}
			</div>
		</div>
		<div class="form-group compra-inputs a_cuenta">
			{!! Form::label('a_cuenta', 'Compra a crédito:' ,array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label'))!!}
			<div class="col-lg-6 col-md-6 col-sm-6" style ="margin-top: 8px;">
				<input name="a_cuenta" type="checkbox" id="a_cuenta">
			</div>
		</div>
		<div class="form-group credito" style="display: none;">
			{!! Form::label('pago', 'Monto a pagar:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::number('pago', null, array('class' => 'form-control input-sm', 'id' => 'pago','size' => '6')) !!}
			</div>
		</div>
		<div class="form-group credito" style="display: none;">
			{!! Form::label('credito', 'Por pagar:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::number('credito', null, array('class' => 'form-control input-sm', 'id' => 'credito','size' => '6', 'disabled')) !!}
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
			<!-- GERSON (19-11-22) -->
			{!! Form::hidden('decimal', null, array('id' => 'decimal')) !!}
			<!--  -->
		</div>
		
		<div class="form-group col-lg-12 col-md-12 col-sm-12" id="divProductos" style="margin-top: 20px; overflow: auto; height: 280px;">
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
					<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>
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
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:120px;">Precio Unit</th>
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
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarCompra(\''.$entidad.'\', this)')) !!}
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	 </div>	
{!! Form::close() !!}

<script>
$('input').iCheck({
	checkboxClass: 'icheckbox_flat-green',
	radioClass: 'iradio_flat-green'
});

$(document).ready(function() {
	$('#detallesCompra').html('');
	$('#cantproductos').val('0');
	configurarAnchoModal('875');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$("#serie").inputmask({"mask": "9999"});
	$("#numerodocumento").inputmask({"mask": "9999999"});
	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});
	permisoRegistrar();
}); 

$('.a_cuenta .iCheck-helper').on('click', function(){
	if( $(this).parent().hasClass('checked')) { 
		$(".credito").css('display','');
		$('#credito').prop('checked',true);
	}else{
		$(".credito").css('display','none');
		$('#credito').prop('checked',false);
	}
});

$("#pago").keyup(function(){
	if( $("#pago").val() != ""){
		if( is_numeric( $("#pago").val())){
			var total = $("#totalcompra").val()
			var pago = parseFloat($("#pago").val());
			var saldo_caja = parseFloat($("#saldo_caja").val());
			if(pago < 0 ||  pago > total || pago > saldo_caja){
				$("#pago").val("");
				$("#credito").val( $("#totalcompra").val() );
			}else{
				var credito = total-pago;
				$("#credito").val(credito.toFixed(2));
			}
		}else{
			$("#pago").val("");
		}
	}else{
		$("#credito").val( $("#totalcompra").val() );
	}
});  

var proveedores = new Bloodhound({
	datumTokenizer: function (d) {
		return Bloodhound.tokenizers.whitespace(d.value);
	},
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	limit:10,
	remote: {
		url: 'proveedor/proveedorautocompleting/%QUERY',
		filter: function (proveedores) {
			return $.map(proveedores, function (proveedor) {
				return {
					value: proveedor.razon_social,
					id: proveedor.id,
					ruc: proveedor.ruc,
					direccion: proveedor.direccion
				};
			});
		}
	}
});
proveedores.initialize();
$('#ccrazon').typeahead(null,{
	displayKey: 'value',
	limit:10,
	source: proveedores.ttAdapter()
}).on('typeahead:selected', function (object, datum) {
	$('#proveedor_id').val(datum.id);
	$('#ccrazon').val(datum.razon_social);
	$('#ccruc').val(datum.ruc);
	$("#ccruc").prop('disabled', true);
	$('#ccrazon').prop('disabled', true);
	if(datum.direccion != ""){
		$('#ccdireccion').val(datum.direccion);
		$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
	}else{
		$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="ccdireccion"]').focus();
	}
});

$('.btnBorrar').on('click', function(){
	$('#proveedor_id').val("");
	$('#ccruc').val("");
	$('#ccrazon').val("");
	$('#ccdireccion').val("");
	$("#ccruc").prop('disabled',false);
	$("#ccrazon").prop('disabled',false);
	$('#ccruc').focus();
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

function buscarProducto(valor){
    if(valor.length >= 3){
        $.ajax({
            type: "POST",
            url: "compras/buscandoproducto",
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

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function seleccionarProducto(idproducto){
	var _token =$('input[name=_token]').val();
	$.post('{{ URL::route("compras.consultaproducto")}}', {idproducto: idproducto, sucursal_id: $('#sucursal').val() ,_token: _token} , function(data){
		var datos = data.split('@');
		$("#producto_id").val(datos[0]);
		$("#precio_compra").val(datos[1]);
		$("#precio_venta").val(datos[2]);
		$("#precioventa").val(datos[2]);
		$("#stock").val(datos[3]); 
		$("#recargable").val(datos[4]);
		$("#envases_vacios").val(datos[7]);
		$("#decimal").val(datos[10]);
		if(datos[4] == 1){
			$(".divEnvase").css('display','');
			$(".divEnvaseBalon").css('display','');
			$("#precio_compra_envase").val(datos[5]);
			$("#precio_venta_envase").val(datos[6]);
			$("#total_envases").val(datos[9]);
		}else{
			$(".divEnvase").css('display','none');
			$(".divEnvaseBalon").css('display','none');
		}
	});
	$("cantidad").focus();
}

/* GERSON (24/11/22) */
// Deteccion si se usará enteros o decimales
$("#cantidad, #cantidad_envase").keypress(function(evt){
	var product_id = $('#producto_id').val();
	if(product_id != null){
		var decimal = $('#decimal').val();
		if(decimal=='null' || decimal=='0'){

			var charCode = (evt.which) ? evt.which : event.keyCode;
			if(charCode > 31 && (charCode < 48 || charCode > 57)){
				return false;
			}
			return true;
			
		}else{
			
			var charCode = (evt.which) ? evt.which : event.keyCode;
			if((charCode > 31 && (charCode < 46 || charCode > 57))||charCode==47){
				return false;
			}
			return true;

		}
	}
});
/*  */

function agregarCarrito(elemento){
	var cantidad = $('#cantidad').val();
	var cantidad_envase = $('#cantidad_envase').val();
	var total_envases = $('#total_envases').val();
	var precio_compra = $('#precio_compra').val();
	var precio_compra_envase = $('#precio_compra_envase').val();
	var precio_venta = $('#precio_venta').val();
	var precio_venta_envase = $('#precio_venta_envase').val();
	var product_id = $('#producto_id').val();
	var stock = $('#stock').val();
	var recargable = parseInt($('#recargable').val());
	var envases_vacios = parseInt($('#envases_vacios').val());
	/* GERSON (24/11/22) */
	var decimal = $('#decimal').val();
	if(decimal=='null' || decimal=='0'){
		var cantidad = parseInt(cantidad);
		
	}else{
		var cantidad = parseFloat(cantidad);
	}
	/*  */
	if( cantidad =="" && cantidad_envase =="" ){
		swal({
			type: 'error',
			title: 'INGRESE CANTIDAD',
			});
		return false;
	}

	if( cantidad ==""){
		cantidad = 0;
	}
	if( cantidad_envase ==""){
		cantidad_envase = 0;
	}

	var _token = $('input[name=_token]').val();

	if(parseFloat(precio_venta) < parseFloat(precio_compra)){
		swal({
			type: 'error',
			title: 'INGRESE UN PRECIO DE VENTA MAYOR AL DE COMPRA',
			});
	}else if( is_numeric(cantidad) != true){
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
	}else if( is_numeric(precio_compra) != true){
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
	}else if( is_numeric(precio_venta) != true){
		swal({
			type: 'error',
			title: 'EL PRECIO DE VENTA DEBE SER UN VALOR NÚMERICO',
			});
	}else if(product_id=="" || product_id=="0"){
		swal({
			type: 'error',
			title: 'SELECCIONE UN PRODUCTO',
			});
	}else{
		if( recargable == 1){
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
		$.post('{{ URL::route("compras.agregarcarritocompra")}}', {cantidad: cantidad, cantidad_envase: cantidad_envase, precio_compra: precio_compra, precio_compra_envase: precio_compra_envase, producto_id: product_id, precio_venta: precio_venta, precio_venta_envase: precio_venta_envase, detalle: $('#detalle').val(),_token: _token} , function(data){
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
				$("#tablaProducto").html("<tr><td align='center' colspan='6'>Digite más de 3 caracteres.</td></tr>");
			}
		});
	}
}

function generarSaldoCaja(){
	var saldocaja = null;
	$.ajax({
		"method": "POST",
		"url": "{{ url('/caja/saldoCaja') }}",
		"data": {
			"sucursal_id" : $('#sucursal').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		saldocaja = info;
		$('#saldo_caja').val(saldocaja);
	});
}

function permisoRegistrar(){
	var aperturaycierre = null;
	$.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : $('#sucursal').val(), 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
		if(aperturaycierre == 0){
			$("#btnGuardar").prop('disabled',true);
			$("#comentario").prop('disabled',true);
			$('#saldo_caja').val((0).toFixed(2));
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';
			$('#divMensajeErrorCompras').html(cadenaError);
		}else if(aperturaycierre == 1){
			$("#btnGuardar").prop('disabled',false);
			$("#comentario").prop('disabled',false);
			$('#divMensajeErrorCompras').html("");
			$("#nombreproducto").val("");
			generarSaldoCaja();
		}
	});
}

function calculatetotal(){
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

function guardarCompra(entidad, idboton) {
	if($("#proveedor_id").val()==""){
		swal({
			title: 'DEBE INGRESAR UN PROVEEDOR',
			type: 'error',
			});
		$("#ccruc").focus();
		return false;
	}else if($("#serie").val()=="" || $("#numerodocumento").val()==""){
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
		$("#nombreproducto").focus();
		return false;
	}else if(!$("#a_cuenta").prop('checked')){

		var total = parseFloat($('#total').val());
		var saldo_caja = parseFloat($('#saldo_caja').val());

		if( total > saldo_caja ){
			swal({
				title: 'NO HAY SUFICIENTE SALDO EN CAJA PARA LA COMPRA',
				type: 'error',
				});
			return false;
		}else{

			var sucursal = document.getElementById("sucursal");
			var tipo = $('#tipodocumento_id').val();
			var total = parseFloat($("#total").val());
			var letra = "";
			if(tipo == 4){
				letra ="FC";
			}else if(tipo == 5){
				letra ="BC";
			}

			var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text 
						+"</p><p><label>N° Compra: </label>  "+ letra + $('#serie').val() + "-"+ $('#numerodocumento').val() 
						+ "</p><p><label>Proveedor:  </label>  "+ $('#ccrazon').val() 
						+ "</p><p><label>Total:  </label>  S/."+  total.toFixed(2) +"</p><div>" ;

			swal({
				title: 'Confirmar Guardado',
				html: mensaje,
				type: 'question',
				showCancelButton: true,
				confirmButtonColor: '#54b359',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Guardar Compra'
			}).then((result) => {
				if (result.value) {
					guardar("{{$entidad}}");
				}
			});
		}
	}else{
		var sucursal = document.getElementById("sucursal");
		var tipo = $('#tipodocumento_id').val();
		var total = parseFloat($("#total").val());
		var pago = "";
		if( $("#pago").val() == "" ){
			pago = 0;
		}else{
			pago = parseFloat($("#pago").val());
		}
		
		var credito = parseFloat($("#credito").val());
		var letra = "";
		if(tipo == 4){
			letra ="FC";
		}else if(tipo == 5){
			letra ="BC";
		}

		var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text 
					+"</p><p><label>N° Compra: </label>  "+ letra + $('#serie').val() + "-"+ $('#numerodocumento').val() 
					+ "</p><p><label>Proveedor:  </label>  "+ $('#ccrazon').val() 
					+ "</p><p><label>Total:  </label>  S/."+  total.toFixed(2) 
					+ "</p><p><label>Compra a crédito:  </label>  "+ "SI"
					+ "</p><p><label>Monto a pagar:  </label>  S/."+  pago.toFixed(2)
					+ "</p><p><label>Cuenta por pagar:  </label>  S/."+  credito.toFixed(2)+"</p><div>";

		swal({
			title: 'Confirmar Guardado',
			html: mensaje,
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#54b359',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Guardar Compra'
		}).then((result) => {
			if (result.value) {
				guardar("{{$entidad}}");
			}
		});
	}	
}

function mostrarultimoproveedor(){
	var proveedor = null;
	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/proveedor/ultimoproveedor') }}",
		"data": {
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		proveedor = info;
	}).always(function(){
		if( $("#ultimo_proveedor").val() == "" ){
			$("#ultimo_proveedor").val(proveedor.id);
		}else{
			if( $("#ultimo_proveedor").val() != proveedor.id){
				if(proveedor.dni != null){
					$('#ccrazon').val(proveedor.apellido_pat + " " + proveedor.apellido_mat + " " + proveedor.nombres);
				}else{
					$('#ccrazon').val(proveedor.razon_social);
				}
				$('#proveedor_id').val(proveedor.id);
				$('#ccruc').val(proveedor.ruc);
				$('#ccdireccion').val(proveedor.direccion);
				$('#ultimo_proveedor').val('');
				$("#ccruc").prop('disabled', true);
				$('#ccrazon').prop('disabled', true);
			}
		}
	});
}
</script>
