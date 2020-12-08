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
			{!! Form::label('tipo', 'Tipo:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
			{!! Form::select('tipo', $cboTipo, null, array('style' => 'background-color: #fcffd4;' ,'class' => 'form-control input-sm', 'id' => 'tipo')) !!}
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
				{!! Form::text('serie', null, array('class' => 'form-control input-sm', 'id' => 'serie', 'placeholder' => 'Serie', 'data-inputmask' => "'mask': '9999'")) !!} 
			</div> 
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::text('numerodocumento', null, array('class' => 'form-control input-sm', 'id' => 'numerodocumento', 'placeholder' => 'Número Documento', 'data-inputmask' => "'mask': '9999999'")) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('fecha', 'Fecha:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<div class='input-group input-group-sm' id='divfecha'>
					<input class="form-control input-sm" id="fecha" placeholder="Ingrese Fecha" name="fecha" type="date" value="{{ $hoy }}" readOnly="readOnly">
				</div>
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px; display:none;">
			{!! Form::label('total', 'Total:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('total', number_format(0, 2, '.', ''), array('style' => 'background-color: #FFEEC5;', 'readOnly' ,'class' => 'form-control input-sm', 'id' => 'total' )) !!}
			</div>
		</div>
		<div class="form-group" style="height: 12px; margin: 25px 0px;">
			{!! Form::label('comentario', 'Comentario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
			<div class="col-lg-8 col-md-8 col-sm-8">
				<textarea class="form-control input-xs" id="comentario" cols="10" rows="5" name="comentario"></textarea>
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8">
		<div class="form-group col-lg-6 col-md-6 col-sm-6" style="height: 12px; margin-top: 28px;">
			{!! Form::label('nombreproducto', 'Producto:', array('class' => 'col-lg-3 col-md-3 col-sm-3 control-label')) !!}
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::text('nombreproducto', null, array('class' => 'form-control input-sm', 'id' => 'nombreproducto', 'placeholder' => 'Ingrese nombre','onkeyup' => 'buscarProducto($(this).val());')) !!}
			</div>
			<div class="col-lg-0 col-md-0 col-sm-0">
                {!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-primary btn-sm', 'style' => 'height: 30px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO PRODUCTO' , 'onclick' => 'modalCaja (\''.URL::route('producto.create', array('listar'=>'SI','modo'=>'popup')).'\', \'Nuevo Producto\', this);', 'title' => 'Nuevo Producto')) !!}
    		</div>
			{!! Form::hidden('producto_id', null, array( 'id' => 'producto_id')) !!}
			{!! Form::hidden('stock', null, array('id' => 'stock')) !!}
			{!! Form::hidden('envases_vacios', null, array('id' => 'envases_vacios')) !!}
			{!! Form::hidden('recargable', null, array('id' => 'recargable')) !!}
		</div>
		
		<div class="col-lg-3 col-md-3 col-sm-3 divEnvaseBalon" style="display: none;">
			{!! Form::label('envases_sucursal', 'Envases por sucursal:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 18px;">
				{!! Form::text('envases_sucursal', null, array('class' => 'form-control input-sm', 'id' => 'envases_sucursal', 'readOnly')) !!}
			</div>		
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 divEnvaseBalon" style="display: none;">
			{!! Form::label('total_envases', 'Total de envases:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
			<div class="col-lg-6 col-md-6 col-sm-6" style="margin-top: 12px;">
				{!! Form::text('total_envases', null, array('class' => 'form-control input-sm', 'id' => 'total_envases', 'readOnly')) !!}
			</div>		
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12" id="divProductos" style="margin-top: 20px; overflow:auto; height:180px; padding-right:10px; border:1px outset">
			<table class='table-condensed table-hover' border='1'>
				<thead>
					<tr>
						<th class='text-center' style='width:300px;'><span style='display: block;'>Nombre</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Precio Compra</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Precio Venta</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Stock</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Recargable</span></th>
						<th class='text-center' style='width:110px;'><span style='display: block;'>Envases Vacíos</span></th>
					</tr>
				</thead>
				<tbody id='tablaProducto'>
					<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>
				</tbody>
			</table>
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12">

			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_compra', 'Precio Compra:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('precio_compra', null, array('class' => 'form-control input-sm', 'id' => 'precio_compra' )) !!}
				</div>
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_venta', 'Precio Venta:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('precio_venta', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta' )) !!}
				</div>
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('cantidad', 'Cantidad:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('cantidad', null, array('class' => 'form-control input-sm', 'id' => 'cantidad' )) !!}
				</div>
				<div class="col-lg-2 col-md-2 col-sm-2">
					{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array('class' => 'btn btn-success btn-sm', 'style' => 'height: 30px; margin-left: 10px; margin-bottom: 1px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'AGREGAR PRODUCTO', 'id' => 'agregarProducto')) !!}
				</div>
			</div>
		</div>

		<div class="form-group col-lg-12 col-md-12 col-sm-12 divEnvase" style="margin-top: -20px; display: none;">

			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_compra_envase', 'Precio Compra Envase:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('precio_compra_envase', null, array('class' => 'form-control input-sm', 'id' => 'precio_compra_envase', 'style' => 'margin-top: 13;' )) !!}
				</div>
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('precio_venta_envase', 'Precio Venta Envase:', array('class' => 'col-lg-6 col-md-6 col-sm-6 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('precio_venta_envase', null, array('class' => 'form-control input-sm', 'id' => 'precio_venta_envase', 'style' => 'margin-top: 13px;' )) !!}
				</div>
			</div>
			<div class="form-group col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('cantidad_envase', 'Cantidad Envases:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::text('cantidad_envase', null, array('class' => 'form-control input-sm', 'id' => 'cantidad_envase', 'style' => 'margin-top: 13px;' )) !!}
				</div>
			</div>
		</div>


		<hr>

		<div class="form-group">
			<div class="col-lg-12 col-md-12 col-sm-12 text-right">
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

$(document).ready(function() {
	$('#detallesCompra').html('');
	$('#cantproductos').val('0');
	configurarAnchoModal('1300');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$("#serie").inputmask({"mask": "9999"});
	$("#numerodocumento").inputmask({"mask": "9999999"});

	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

	$('#agregarProducto').on('click', function(){
		addpurchasecart(); 
		indice = -1;
	});

	$('#tipo').on('change', function(){
		$("#detallesCompra").html("");
		$('#nombreproducto').focus();
		calculatetotal();
		generarTipodoc();
	});

	$('#sucursal').on('change', function(){
		$("#nombreproducto").val("");
		$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
	});
	
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
	
}); 

function buscarProducto(valor){
    if(valor.length >= 3){
        $.ajax({
            type: "POST",
            url: "movalmacen/buscandoproducto",
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
	                    a +="<tr style='cursor:pointer' class='escogerFila' id='"+datos[c].idproducto+"' onclick=\"seleccionarProducto('"+datos[c].idproducto+"')\"><td><span style='display: block; font-size:.9em'>"+datos[c].nombre+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].precio_compra+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].precio_venta+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].stock+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].recargable+"</span></td><td align='center'><span style='display: block; font-size:.9em'>"+datos[c].envases_vacios+"</span></td></tr>";
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
	$.post('{{ URL::route("movalmacen.consultaproducto")}}', {idproducto: idproducto, sucursal_id: $('#sucursal').val() ,_token: _token} , function(data){
		//$('#divDetail').html(data);
		//calculatetotal();
		var datos = data.split('@');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="producto_id"]').val(datos[0]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_compra"]').val(datos[1]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_venta"]').val(datos[2]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precioventa"]').val(datos[2]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="stock"]').val(datos[3]); 
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="recargable"]').val(datos[4]);
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="envases_vacios"]').val(datos[7]);
		if(datos[4] == 1){
			$(".divEnvase").css('display','');
			$(".divEnvaseBalon").css('display','');
			$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_compra_envase"]').val(datos[5]);
			$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="precio_venta_envase"]').val(datos[6]);
			$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="envases_sucursal"]').val(datos[8]);
			$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="total_envases"]').val(datos[9]);
		}else{
			$(".divEnvase").css('display','none');
			$(".divEnvaseBalon").css('display','none');
		}
		/*if(datos[0] == 4 || datos[0] == 5){
			$(".divEnvaseBalon").css('display','');
		}else{
			$(".divEnvaseBalon").css('display','none');
		}*/
	});
	$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="cantidad"]').focus();
}

function addpurchasecart(){
	var cantidad = $('#cantidad').val();
	var cantidad_envase = $('#cantidad_envase').val();
	var envases_sucursal = $('#envases_sucursal').val();
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
		stock = parseInt( $('#stock').val() );
		cantidad = parseInt( $('#cantidad').val() );
		if( tipo == "E" ){
			if(stock < cantidad || stock < cantidad_envase){
				swal({
					type: 'error',
					title: 'NO HAY SUFICIENTE STOCK',
					});
				$('#cantidad').val('');
				$('#cantidad').focus();
			}else{
				$.post('{{ URL::route("movalmacen.agregarcarritocompra")}}', {cantidad: cantidad, cantidad_envase: cantidad_envase, precio_compra: precio_compra, precio_compra_envase: precio_compra_envase, producto_id: product_id, precio_venta: precio_venta, precio_venta_envase: precio_venta_envase, detalle: $('#detalle').val(),_token: _token} , function(data){
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
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_compra_envase"]').val('');
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_venta_envase"]').val('');
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="envases_sucursal"]').val('');
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="total_envases"]').val('');
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad_envase"]').val('');
						$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
						$('.escogerFila').css('background-color', 'white');
						$(".divEnvase").css('display','none');
						$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
					}
				});
			}
		}else{
			
			if( recargable == 1){
				if( cantidad > envases_vacios){
					swal({
					type: 'error',
					title: 'NO HAY SUFICIENTES ENVASES VACÍOS',
					});
					return false;
				}
				if( product_id == 4 || product_id == 5){
					if( cantidad_envase > (envases_sucursal - total_envases)){
						swal({
						type: 'error',
						title: 'SUPERA EL LÍMITE DE ENVASES POR SUCURSAL',
						});
						return false;
					}
				}
			}

			$.post('{{ URL::route("movalmacen.agregarcarritocompra")}}', {cantidad: cantidad, cantidad_envase: cantidad_envase, precio_compra: precio_compra, precio_compra_envase: precio_compra_envase, producto_id: product_id, precio_venta: precio_venta, precio_venta_envase: precio_venta_envase, detalle: $('#detalle').val(),_token: _token} , function(data){
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
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_compra_envase"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="precio_venta_envase"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="envases_sucursal"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="total_envases"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="cantidad_envase"]').val('');
					$(IDFORMMANTENIMIENTO+'{!! $entidad !!}' + ' :input[id="nombreproducto"]').focus();
					$('.escogerFila').css('background-color', 'white');
					$(".divEnvase").css('display','none');
					$("#tablaProducto").html("<tr><td align='center' colspan='8'>Digite más de 3 caracteres.</td></tr>");
				}
			});
		}
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
			confirmButtonText: 'Guardar Compra'
		}).then((result) => {
			if (result.value) {
				guardar("{{$entidad}}");
			}
		});
	}	
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function generarTipodoc() {
	var tipo = $("#tipo").val();
	var tiposdoc = "";
	$.ajax({
		"method": "POST",
		"url": "{{ url('/movalmacen/generartipodocumento') }}",
		"data": {
			"tipo" : tipo, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		tiposdoc = info;
	}).always(function(){

		var select = "";

		$.each(tiposdoc, function(i, item) {
			select =  select + '<option value="' + item.id + '">'+ item.abreviatura + ' - ' + item.descripcion + '</option>';
		});

		$("#tipodocumento_id").html(select);

	});
	
}

</script>