<?php
$hoy = date("Y-m-d");
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('detalle', 'false', array( 'id' => 'detalle')) !!}
	<input type="hidden" name="cantproductos" id="cantproductos">
	<div class="col-lg-4 col-md-4 col-sm-4">
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('sucursal', 'Sucursal:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::select('sucursal', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal')) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('tipodocumento_id', 'Documento:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::select('tipodocumento_id', $cboDocumento, null, array('style' => 'background-color: #D4F0FF;' ,'class' => 'form-control input-sm', 'id' => 'tipodocumento_id')) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('numerodocumento', 'Nro Doc:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-3 col-md-3 col-sm-3">
				{!! Form::text('serie', null, array('class' => 'form-control input-sm', 'id' => 'serie', 'placeholder' => 'Serie')) !!} 
			</div> 
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::text('numerodocumento', null, array('class' => 'form-control input-sm', 'id' => 'numerodocumento', 'placeholder' => 'Número Documento')) !!}
			</div>
		</div>
		<div id="opcEmpresa">
			{!! Form::hidden('proveedor_id', null, array('id' => 'proveedor_id')) !!}
			{!! Form::hidden('ultimo_proveedor',null,array('id'=>'ultimo_proveedor')) !!}
			<div class="form-group" style="height: 12px; margin: 25px 0px;">
				{!! Form::label('ccruc', 'RUC:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
				<div class="col-lg-5 col-md-5 col-sm-5">
	    			{!! Form::text('ccruc','', array('class' => 'form-control input-sm datocaja', 'id' => 'ccruc', 'maxlength' => '11')) !!}
	    		</div> 
				<div class="col-lg-0 col-md-0 col-sm-0">
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO PROVEEDOR', 'onclick' => 'modalCaja (\''.URL::route('compras.proveedor', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Proveedor\', this);', 'title' => 'Nuevo Proveedor')) !!}
					{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnclienteborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-sm btnBorrar' , 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'Borrar')) !!}
				</div>
		    </div>
		    <div class="form-group" style="height: 12px; margin: 25px 0px;">
	    		{!! Form::label('ccrazon', 'Razón:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
				<div class="col-lg-8 col-md-8 col-sm-8">
	    			{!! Form::text('ccrazon','', array('class' => 'form-control input-sm datocaja', 'id' => 'ccrazon')) !!}
	    		</div> 
		    </div>
		    <div class="form-group" style="height: 12px; margin: 25px 0px;">
	    		{!! Form::label('ccdireccion', 'Dirección:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
				<div class="col-lg-8 col-md-8 col-sm-8">
	    			{!! Form::text('ccdireccion','', array('class' => 'form-control input-sm datocaja', 'id' => 'ccdireccion')) !!}
	    		</div> 	
		    </div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<div class='input-group input-group-sm' id='divfecha'>
					<input class="form-control input-sm" id="fecha" placeholder="Ingrese Fecha" name="fecha" type="date" value="{{ $hoy }}">
				</div>
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('total', 'Total:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('total', number_format(0, 2, '.', ''), array('style' => 'background-color: #FFEEC5;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'total' )) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('a_cuenta', 'Compra a crédito:' ,array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label'))!!}
			<div class="col-lg-4 col-md-4 col-sm-4"style ="margin-top: 15px;">
				<input name="a_cuenta" type="checkbox" id="a_cuenta">
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="form-group col-lg-12 col-md-12 col-sm-12" style="height: 12px;">
			{!! Form::label('nombreproducto', 'Producto:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::text('nombreproducto', null, array('class' => 'form-control input-sm', 'id' => 'nombreproducto', 'placeholder' => 'Ingrese nombre','onkeyup' => 'buscarProducto($(this).val());')) !!}
			</div>
			<div class="col-lg-0 col-md-0 col-sm-0">
                {!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO PRODUCTO' , 'onclick' => 'modalCaja (\''.URL::route('producto.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Producto\', this);', 'title' => 'Nuevo Producto')) !!}
    		</div>
			{!! Form::hidden('producto_id', null, array( 'id' => 'producto_id')) !!}
			{!! Form::hidden('precioventa', null, array('id' => 'precioventa')) !!}
			{!! Form::hidden('stock', null, array('id' => 'stock')) !!}
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12" id="divProductos" style="margin-top: 20px; overflow:auto; height:180px; padding-right:10px; border:1px outset">
			<table class='table-condensed table-hover' border='1'>
				<thead>
					<tr>
						<th class='text-center' style='width:300px;'><span style='display: block;'>Nombre</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Precio Compra</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Precio Venta</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Stock</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Stock Seguridad</span></th>
					</tr>
				</thead>
				<tbody id='tablaProducto'>
					<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>
				</tbody>
			</table>
		</div>

		<div class="form-group">
			<table>
			<tr>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td><b>Precio Compra</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('precio_compra', null, array('class' => 'form-control input-sm', 'id' => 'precio_compra','size' => '6')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td><b>Precio Venta</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('precio_venta', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta', 'size' => '6')) !!}</td>
				<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>
				<td><b>Cantidad</b></td>
				<td>&nbsp</td>
				<td>{!! Form::text('cantidad', null, array('class' => 'form-control input-sm', 'id' => 'cantidad', 'size' => '6', 'onkeyup' => "javascript:this.value=this.value.toUpperCase();")) !!}</td>
			</tr>
			</table>
		</div>

		<hr>

		<div class="form-group">
			<div class="col-lg-2 col-md-2 col-sm-2 credito">
				{!! Form::label('pago', 'Monto a pagar:', array('class' => 'control-label')) !!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 credito">
				{!! Form::text('pago', null, array('class' => 'form-control input-sm', 'id' => 'pago','size' => '6')) !!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 credito">
				{!! Form::label('credito', 'Por pagar (S/.):', array('class' => 'control-label')) !!}
			</div>
			<div class="col-lg-2 col-md-2 col-sm-2 credito">
				{!! Form::text('credito', null, array('class' => 'form-control input-sm', 'id' => 'credito','size' => '6', 'disabled')) !!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 text-right">
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardarCompra(\''.$entidad.'\', this)')) !!}
				{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-warning btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
			</div>
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
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:250px;">Cantidad</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:120px;">Precio Unit</th>
		                    <th bgcolor="#E0ECF8" class="text-center" style="width:120px;">Subtotal</th>
		                    <th bgcolor="#E0ECF8" class='text-center' style="width:100px;">Eliminar</th>
		                </tr>
		            </thead>
		            <tbody id="detallesCompra">
		            </tbody>
		            <tbody border="1">
		            	<tr>
		            		<th colspan="5" style="text-align: right;">TOTAL</th>
		            		<td class="text-center">
		            			<center id="totalcompra2">0.00</center><input type="hidden" id="totalcompra" readonly="" name="totalcompra" value="0.00">
		            		</td>
		            	</tr>
		            </tbody>		           
		        </table>
		    </div>
		</div>
	 </div>	
{!! Form::close() !!}
<style type="text/css">
tr.resaltar {
    background-color: #A9F5F2;
    cursor: pointer;
}
</style>
<script type="text/javascript">

var valorbusqueda="";
var indice = -1;
var anterior = -1;

/*$(document).on('change', '#tipodocumento_id', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	if($(this).val() == '4') {
		$('#opcEmpresa').css('display', 'block');
	} else {
		$('#opcEmpresa').css('display', 'none');
	}
});*/

$(document).on('keyup', '#ccruc', function(e) {
	e.preventDefault();
	e.stopImmediatePropagation();
	if ($(this).val().length == 11) {
		buscarEmpresa();
	}
});

$('input').iCheck({
	checkboxClass: 'icheckbox_flat-green',
	radioClass: 'iradio_flat-green'
});

$(document).ready(function() {
	$('#detallesCompra').html('');
	$('#cantproductos').val('0');
	configurarAnchoModal('1200');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	$('.btnBorrar').on('click', function(){
		$('#proveedor_id').val("");
		$('#ccruc').val("");
		$('#ccrazon').val("");
		$('#ccdireccion').val("");
		$("#ccruc").prop('disabled',false);
		$("#ccrazon").prop('disabled',false);
		$('#ccruc').focus();
	});

	$(".credito").css('display','none');
	
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_compra"]').keydown( function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if(key == 13) {
			e.preventDefault();
			var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
			inputs.eq( inputs.index(this)+ 1 ).focus();
		}
	});
	
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_venta"]').keydown( function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if(key == 13) {
			e.preventDefault();
			var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
			inputs.eq( inputs.index(this)+ 1 ).focus();
		}
	});

	//agregar producto sin lote
	$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad"]').keydown( function(e) {
		var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		if(key == 13) {
			if($(this).val() == '') {
				return false;
				$(this).focus();
			}
			/*e.preventDefault();
			var inputs = $(this).closest('form').find(':input:visible:not([disabled]):not([readonly])');
			inputs.eq( inputs.index(this)+ 1 ).focus();*/
			addpurchasecart();
			indice = -1;
		}
	});

	$("#pago").keyup(function(){
		if( $("#pago").val() != ""){
			if( is_numeric( $("#pago").val())){
				var total = $("#totalcompra").val()
				var pago = parseFloat($("#pago").val());
				if(pago < 0 ||  pago > total){
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

	$('.iCheck-helper').on('click', function(){
		var divpadre = $(this).parent();
		var input = divpadre.find('input');
		if( input.attr('id') == 'a_cuenta'){ //codigo_vale_subcafae
			if( divpadre.hasClass('checked')) { 
				$(".credito").css('display','');
				$('#credito').prop('checked',true);
			}else{
				$(".credito").css('display','none');
				$('#credito').prop('checked',false);
			}
		}
	});
	
}); 

function buscarProducto(valor){
    if(valor.length >= 3){
        $.ajax({
            type: "POST",
            url: "compras/buscandoproducto",
            data: "nombre="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="nombreproducto"]').val() + "&sucursal="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="sucursal"]').val() + "&_token="+$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[name="_token"]').val(),
            success: function(a) {
                datos=JSON.parse(a);
                //$("#divProductos").html("<table class='table table-bordered table-condensed table-hover' border='1' id='tablaProducto'><thead><tr><th class='text-center'>P. Activo</th><th class='text-center'>Nombre</th><th class='text-center'>Presentacion</th><th class='text-center'>Stock</th><th class='text-center'>P.Kayros</th><th class='text-center'>P.Venta</th></tr></thead></table>");
                $("#divProductos").css("overflow-x",'hidden');
                var pag=parseInt($("#pag").val());
                //var d=0;
                var a = '';
                if(datos.length > 0) {
	                for(c=0; c < datos.length; c++){
	                    a +="<tr style='cursor:pointer' class='escogerFila' id='"+datos[c].idproducto+"' onclick=\"seleccionarProducto('"+datos[c].idproducto+"')\"><td><span style='display: block; font-size:.9em'>"+datos[c].nombre+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].precio_compra+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].precio_venta+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].stock+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].stock_seguridad+"</span></td></tr>";
	                }	                
	            } else {
	            	a +="<tr><td align='center' colspan='8'>Productos no encontrados.</td></tr>";
	            }
	            $("#tablaProducto").html(a);
                $('#tablaProducto_filter').css('display','none');
                $("#tablaProducto_info").css("display","none");
    	    }
        });
    } else {
    	$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
    }
}

$(document).on('click', '.escogerFila', function(){
	$('.escogerFila').css('background-color', 'white');
	$(this).css('background-color', 'yellow');
});

function seleccionarProducto(idproducto){
	//alert(idproducto);
	var _token =$('input[name=_token]').val();
	$.post('{{ URL::route("compras.consultaproducto")}}', {idproducto: idproducto,_token: _token} , function(data){
		//$('#divDetail').html(data);
		//calculatetotal();
		var datos = data.split('@');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="producto_id"]').val(datos[0]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_compra"]').val(datos[1]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_venta"]').val(datos[2]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="stock"]').val(datos[3]);
		//$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="preciocompra"]').val(datos[4]);
	});
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="cantidad"]').focus();
}

function addpurchasecart(elemento){
	var cantidad = $('#cantidad').val();
	var precio = $('#precio_compra').val();
	var precioventa = $('#precio_venta').val();
	var product_id = $('#producto_id').val();
	var stock = $('#stock').val();

	var _token =$('input[name=_token]').val();

	if(parseFloat(precioventa) < parseFloat(precio)){
		swal({
			type: 'error',
			title: 'INGRESE UN PRECIO DE VENTA MAYOR AL DE COMPRA',
			});
	}else if(cantidad.trim() == '' ){
		swal({
			type: 'error',
			title: 'INGRESE CANTIDAD DEL PRODUCTO A AGREGAR',
			});
	}else if(cantidad.trim() == 0){
		swal({
			type: 'error',
			title: 'LA CANTIDAD DEL PRODUCTO A AGREGAR DEBE SER MAYOR A 0',
			});
	}else if( is_numeric(cantidad) != true){
		swal({
			type: 'error',
			title: 'CANTIDAD DEBE SER UN VALOR NÚMERICO',
			});
	}else if(precio.trim() == '' ){
		swal({
			title: 'INGRESE PRECIO DE COMPRA',
			type: 'error',
			});
	}else if(precio.trim() == 0){
		swal({
			type: 'error',
			title: 'EL PRECIO DE COMPRA DEBE SER MAYOR A 0',
			});
	}else if( is_numeric(precio) != true){
		swal({
			type: 'error',
			title: 'EL PRECIO DE COMPRA DEBE SER UN VALOR NÚMERICO',
			});
	}else if(precioventa.trim() == 0){
		swal({
			type: 'error',
			title: 'EL PRECIO DE VENTA DEBE SER MAYOR A 0',
			});
	}else if(precioventa.trim() == '' ){
		swal({
			title: 'INGRESE PRECIO DE VENTA',
			type: 'error',
			});
	}else if( is_numeric(precioventa) != true){
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
		$.post('{{ URL::route("compras.agregarcarritocompra")}}', {cantidad: cantidad, precio: precio, producto_id: product_id, precioventa: precioventa, detalle: $('#detalle').val(),_token: _token} , function(data){
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
				/*bootbox.alert("Producto Agregado");
				setTimeout(function () {
					$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="nombre"]').focus();
				},2000) */
				//var totalpedido = $('#totalpedido').val();
				//$('#total').val(totalpedido);
				$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').val('');
				$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad"]').val('');
				$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_compra"]').val('');
				$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_venta"]').val('');
				$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
				$('.escogerFila').css('background-color', 'white');
				$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
			}
		});
	}
}

function calculatetotal () {
	/*var _token =$('input[name=_token]').val();
	var valor =0;
	$.post('{ URL::route("venta.calculartotal")}}', {valor: valor,_token: _token} , function(data){
		valor = retornarFloat(data);
		$("#total").val(valor);
		//generarSaldototal();
		// var totalpedido = $('#totalpedido').val();
		// $('#total').val(totalpedido);
	});*/
	//Reorganizamos los nombres y números de las filas de la tabla
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
		$(this).find('.preciocompra').attr('name', '').attr('name', 'preciocompra' + i);
		$(this).find('.precioventa').attr('name', '').attr('name', 'precioventa' + i);
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

function buscarEmpresa() {
	ruc = $("#ccruc").val();     
    $.ajax({
        type: 'GET',
        url: "proveedor/buscarEmpresa",
        data: "ruc="+ruc,
        beforeSend(){
            $("#ccruc").val('Comprobando...');
        },
        success: function (a) {
            if(a == '')  {
				$("#ccruc").val(ruc);
				swal({
				title: 'NO SE ENCONTRÓ PROVEEDOR',
				type: 'error',
				});
        	} else {
        		var e = a.split(';;');
        		$("#ccruc").val(ruc);
				$('#proveedor_id').val(e[0]);
        		$('#ccrazon').val(e[1]);
				$("#ccruc").prop('disabled', true);
				$('#ccrazon').prop('disabled', true);
				if(e[2] != ""){
        			$('#ccdireccion').val(e[2]);
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
				}else{
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="ccdireccion"]').focus();
				}
        	}
        }
    });
}

$(document).on('click', '.quitarFila', function(event) {
	event.preventDefault();
	$(this).parent('span').parent('td').parent('tr').remove();
	calculatetotal();
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

/*
swal({
	title: 'Confirmar Guardado',
	html: "<p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text +"</p><p><label>N° Venta: </label>  "+ letra+ $('#serieventa').val()+"</p><p><label>Cliente:  </label>  "+ $('#cliente').val()+"</p><p><label>Empleado:  </label>  "+ $('#empleado_nombre').val()+"</p><p><label>Total:  </label>  S/."+  total.toFixed(2) +"</p>",
	type: 'question',
	showCancelButton: true,
	confirmButtonColor: '#54b359',
	cancelButtonColor: '#d33',
	confirmButtonText: 'Guardar Venta'
	}).then((result) => {
		if (result.value) {
			guardarventa();
			setTimeout(function(){
				cargarRutaMenu('turno', 'container', '15');
			},2000);
		}
	});
*/

function guardarCompra(entidad, idboton) {
	
	if($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="proveedor_id"]').val()==""){
		swal({
			title: 'DEBE INGRESAR UN PROVEEDOR',
			type: 'error',
			});
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="ccruc"]').focus();
		return false;
	}else if($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="serie"]').val()=="" || $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="numerodocumento"]').val()==""){
		swal({
			title: 'DEBE INGRESAR SERIE Y NÚMERO',
			type: 'error',
			});
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="ccruc"]').focus();
		return false;
	}else if($(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="total"]').val()== 0){
		swal({
			title: 'EL TOTAL DEBE SER MAYOR QUE 0 SOLES , INGRESE PRODUCTOS AL DETALLE DE LA COMPRA',
			type: 'error',
			});
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="ccruc"]').focus();
		return false;
	}else{
		var total = $(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="total"]').val();
		total = total.replace(',','');
		var mensaje = '<h3 align = "center">Total = '+total+'</h3>';
		/*if (typeof mensajepersonalizado != 'undefined' && mensajepersonalizado !== '') {
			mensaje = mensajepersonalizado;
		}*/
		bootbox.confirm({
			message : mensaje,
			buttons: {
				'cancel': {
					label: 'Cancelar',
					className: 'btn btn-default btn-sm'
				},
				'confirm':{
					label: 'Aceptar',
					className: 'btn btn-success btn-sm'
				}
			}, 
			callback: function(result) {
				if (result) {
					var idformulario = IDFORMMANTENIMIENTO + entidad;
					var data         = submitForm(idformulario);
					var respuesta    = '';
					var listar       = 'NO';
					
					var btn = $(idboton);
					btn.button('loading');
					data.done(function(msg) {
						respuesta = msg;
					}).fail(function(xhr, textStatus, errorThrown) {
						respuesta = 'ERROR';
					}).always(function() {
						btn.button('reset');
						if(respuesta === 'ERROR'){
						}else{
							var dat = JSON.parse(respuesta);
				            if(dat[0]!==undefined){
				                resp=dat[0].respuesta;    
				            }else{
				                resp='VALIDACION';
				            }
				            
							if (resp === 'OK') {
								cerrarModal();
				                buscarCompaginado('', 'Accion realizada correctamente', entidad, 'OK');
				                /*if(dat[0].pagohospital!="0"){
				                    window.open('/juanpablo/ticket/pdfComprobante?ticket_id='+dat[0].ticket_id,'_blank')
				                }else{
				                    window.open('/juanpablo/ticket/pdfPrefactura?ticket_id='+dat[0].ticket_id,'_blank')
				                }*/
				                //alert('hola');
				                /*if (dat[0].ind == 1) {
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].venta_id,'_blank');
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].second_id,'_blank');
				                }else{
				                	window.open('/juanpablo/venta/pdfComprobante?venta_id='+dat[0].venta_id,'_blank');
				                }*/
				                
							} else if(resp === 'ERROR') {
								//bootbox.alert(dat[0].msg);
								swal({
									title: dat[0].msg,
									type: 'error',
									});
							} else {
								mostrarErrores(respuesta, idformulario, entidad);
							}
						}
					});
				};
			}            
		}).find("div.modal-content").addClass("bootboxConfirmWidth");
		setTimeout(function () {
			if (contadorModal !== 0) {
				$('.modal' + (contadorModal-1)).css('pointer-events','auto');
				$('body').addClass('modal-open');
			}
		},2000);
	}	
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
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