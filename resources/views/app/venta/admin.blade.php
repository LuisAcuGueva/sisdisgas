<!-- Page-Title -->
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
use App\Movimiento;

$user = Auth::user();
/*
SELECT operacion_menu.operacion_id
FROM  operacion_menu 
inner join permiso_operacion
on permiso_operacion.operacionmenu_id = operacion_menu.id
where po.usertype_id = 4 and om.menuoption_id = 9
*/
/*$operaciones = DB::table('operacion_menu')
					->join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.operacion_id')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', 6 ],
					])->get();*/
$opcionmenu = Menuoption::where('link','=',$entidad)->orderBy('id','ASC')->first();
$operaciones = OperacionMenu::join('permiso_operacion','operacion_menu.id','=','permiso_operacion.operacionmenu_id')
					->select('operacion_menu.*')
					->where([
						['permiso_operacion.usertype_id','=',$user->usertype_id],
						['operacion_menu.menuoption_id','=', $opcionmenu->id ],
					])->get();					
$operacionesnombres = array();
foreach($operaciones as $key => $value){
	$operacionesnombres[] = $value->operacion_id;
}
/*
operaciones 
1 nuevo
2 editar
3 eliminar
*/
?>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> {{ $title }}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">

			{!! Form::open(['route' => $ruta["guardarventa"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'IDFORMMANTENIMIENTO'.$entidad]) !!}
			<div class="col-lg-6 col-md-6 col-sm-6">
				<div style="    border: solid 1px; border-radius: 5px; height: 35px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54;">
					<h4 class="page-venta" style ="margin-top: 8px;  font-weight: 600;">SELECCIONE REPARTIDOR</h4>
				</div>

				@if(!empty($empleados))
				<h4 class="page-ventaa" style ="margin: 10px 0px;  font-weight: 600; text-align: center;"></h4>
				<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-x: scroll; border-style: groove;">
					@foreach($empleados  as $key => $value)
						<div class="empleado" id="{{ $value->id}}" style="margin: 5px; width: 120px; height: 110px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;" >
							<img src="assets/images/empleado.png" style="width: 50px; height: 50px">
							<label style="font-size: 11px;  color: #2a3f54;">{{ $value->razon_social ? $value->razon_social : $value->nombres.' '.$value->apellido_pat.' '.$value->apellido_mat}}</label>
						</div>
					@endforeach
				</div>
				{!! Form::hidden('empleado_id',null,array('id'=>'empleado_id')) !!}
				{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
				@else
				<h4 class="page-ventaa" style ="margin: 10px 0px;  font-weight: 600; text-align: center; color: red;"> NO HAY REPARTIDORES EN TURNO</h4>
				<div id="empleados" style=" margin: 10px 0px; display: -webkit-inline-box; width: 100%; overflow-x: scroll; border-style: groove;">
				</div>
				{!! Form::hidden('empleado_id',null,array('id'=>'empleado_id')) !!}
				{!! Form::hidden('empleado_nombre',null,array('id'=>'empleado_nombre')) !!}
				@endif
			</div>

			<div class="col-lg-6 col-md-6 col-sm-6">

				<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 35px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
					<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">DATOS ADICIONALES DEL PEDIDO</h4>
				</div>

				<div class="col-lg-4 col-md-4 col-sm-4 m-b-15 vales">
					<div class="col-lg-12 col-md-12 col-sm-12 vales" style="margin-bottom: 15px;" disabled>
						{!! Form::label('balon_nuevo', 'Balón nuevo:' ,array('class' => 'input-lg', 'style' => 'margin-bottom: -13px;'))!!}
						<input class="balon" name="balon_nuevo" type="checkbox" id="balon_nuevo">
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 vales">
						{!! Form::label('balon_a_cuenta', 'Pedido a crédito:' ,array('class' => 'input-lg', 'style' => 'margin-bottom: -13px;'))!!}
						<input class="balon" name="balon_a_cuenta" type="checkbox" id="balon_a_cuenta">
						{!! Form::hidden('credito', 0 ,array('id'=>'credito')) !!}
					</div>
				</div>

				<div class="col-lg-8 col-md-8 col-sm-8 m-b-15 vales">
					{!! Form::hidden('activar_checkbox', 0 ,array('id'=>'activar_checkbox')) !!}
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="col-lg-4 col-md-4 col-sm-2 vales" style="margin-top: 10px;">
							{!! Form::label('', 'Vale FISE:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -13px;'))!!}
							<input class="balon"  name="vale_balon_fise" type="checkbox" id="vale_balon_fise" disabled>
						</div>
						<div class="col-lg-5 col-md-5 col-sm-5" style="margin-top: 10px; margin-bottom: 15px;">
							{!! Form::text('codigo_vale_fise', '', array('class' => 'form-control input-sm montos balon', 'id' => 'codigo_vale_fise','placeholder' => 'Código FISE', 'readOnly', 'data-inputmask' => "'mask': '99999999*************'")) !!}
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3" style="margin-top: 10px; ">
							{!! Form::text('monto_vale_fise', '', array('class' => 'form-control input-sm montos balon', 'id' => 'monto_vale_fise', 'style' => 'text-align: right; font-size: 23px;', 'placeholder' => '0.00', 'readOnly')) !!}
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="col-lg-5 col-md-5 col-sm-5 vales" style="margin-top: 10px;">
							{!! Form::label('', 'Vale SUBCAFAE:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -13px;'))!!}
							<input class="balon" name="vale_balon_subcafae" type="checkbox" id="vale_balon_subcafae" disabled>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px; margin-bottom: 15px;">
							{!! Form::text('codigo_vale_subcafae', '', array('class' => 'form-control input-sm montos balon', 'id' => 'codigo_vale_subcafae' ,'placeholder' => 'Código SUBCAFAE', 'readOnly', 'data-inputmask' => "'mask': '99999'")) !!}
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="col-lg-4 col-md-4 col-sm-4 vales" style="margin-top: 8px;">
							{!! Form::label('', 'Vale Monto (S/.):' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -13px;'))!!}
							<input class="balon"  name="vale_balon_monto" type="checkbox" id="vale_balon_monto" disabled>
						</div>
						<div class="col-lg-1 col-md-1 col-sm-1"></div>
						<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 8px; margin-bottom: 15px;">
							{!! Form::text('codigo_vale_monto', '', array('class' => 'form-control input-sm montos balon', 'id' => 'codigo_vale_monto' ,'placeholder' => 'Código vale monto', 'readOnly')) !!}
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3" style="margin-top: 8px; ">
							{!! Form::text('monto_vale_balon', '', array('class' => 'form-control input-sm montos balon', 'id' => 'monto_vale_balon', 'style' => 'text-align: right; font-size: 23px;', 'placeholder' => '0.00' , 'readOnly')) !!}
						</div>
					</div>
				</div>

			</div>

			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="col-lg-3 col-md-3 col-sm-3" id="divDatosDocumento1">
					<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 35px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
						<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">DATOS DEL DOCUMENTO</h4>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						{!! Form::label('sucursal_id', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id' , 'onchange' => 'generarNumeroSerie(); permisoRegistrar(); actualizarPreciosVales(); generarEmpleados();generarProductos();')) !!}		
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						{!! Form::label('tipodocumento_id', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::select('tipodocumento_id', $cboTipoDocumento, null, array('class' => 'form-control input-sm', 'id' => 'tipodocumento_id', 'onchange' => 'generarNumeroSerie();')) !!}		
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('serieventa', '', array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'")) !!}
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						{!! Form::label('fecha', 'Fecha:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('fecha', '', array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="margin-top: 10px;">
						<div class="col-lg-3 col-md-3 col-sm-3" style="margin-left:-10px;">
							{!! Form::label('cliente', 'Cliente:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 20px;">
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i>', array( 'id' => 'btnclientenuevo' , 'class' => 'btn btn-success waves-effect waves-light btn-sm btnCliente', 'onclick' => 'modal (\''.URL::route($ruta["cliente"], array('listar'=>'SI')).'\', \''.$titulo_cliente.'\', this);', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'NUEVO')) !!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 10px; display:none;">
							{!! Form::button('<i class="glyphicon glyphicon-user"></i>', array('id' => 'btnclientevarios' , 'class' => 'btn btn-primary waves-effect waves-light btn-sm btnDefecto', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'VARIOS')) !!}
						</div>
						<div class="col-lg-2 col-md-2 col-sm-2" style="margin-left: 10px;">
							{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnclienteborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-sm btnBorrar' , 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'BORRAR')) !!}
						</div>
						{!! Form::text('cliente', '', array('class' => 'form-control input-sm', 'id' => 'cliente', 'style' => 'background-color: white;')) !!}
						{!! Form::hidden('cliente_id',null,array('id'=>'cliente_id')) !!}
						{!! Form::hidden('ultimo_cliente',null,array('id'=>'ultimo_cliente')) !!}
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						{!! Form::label('cliente_direccion', 'Dirección:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::textarea('cliente_direccion', null, array('class' => 'form-control input-xs', 'rows' => '2','id' => 'cliente_direccion', 'readOnly')) !!}
					</div>
				</div>

				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 35px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
						<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">SELECCIONE PRODUCTOS</h4>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div id="servicios_frecuentes" class="col-lg-12 col-md-12 col-sm-12" style="margin: 10px; border-style: groove; width: 100%; height: 180px; overflow-y: scroll;">
							@foreach($productos  as $key => $value)
								<div class="servicio_frecuente col-lg-3 col-md-3 col-sm-3" id="{{ $value->id}}"  precio="{{ $value->precio_venta }}" descripcion="{{ $value->descripcion }}" editable="{{ $value->editable }}" stock="{{ $value->cantidad }}" style="margin: 5px; width: 85px; height: 75px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;" >
									<label style="font-size: 9.5px; color: #2a3f54; padding-top: 13px;">{{ $value->descripcion}}</label>
									<label style="font-size: 9.5px; color: #2a3f54;">STOCK: {{$value->cantidad}}</label>
								</div>
							@endforeach
						</div>
					</div>

					<div class="form-group col-lg-12 col-md-12 col-sm-12">
						{!! Form::hidden('cant',null,array('id'=>'cant', 'value' => '0')) !!}
						<h4 align="center" class="col-lg-12 col-md-12 col-sm-12 m-t-30" style="color: #2a3f54; font-weight: 600;">LISTA DE PRODUCTOS</h4>
						<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="font-size: 90%; padding: 0px 0px !important;">
							<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cant</th><th style="font-size: 13px !important;">Precio Unit</th><th style="font-size: 13px !important;">Precio Acum</th><th style="font-size: 13px !important;">Eliminar</th></tr></thead>
							<tbody id="detalle"></tbody>
						</table>
					</div>
				</div>

				<div class="col-lg-3 col-md-3 col-sm-3">
					<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 35px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
						<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">PAGO</h4>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
						<div  class="col-lg-4 col-md-4 col-sm-4">
							<img src="assets/images/efectivo.png" style="width: 60px; height: 60px">
						</div>
						<div  class="col-lg-8 col-md-8 col-sm-8">
							{!! Form::text('montoefectivo', '', array('class' => 'form-control input-lg montos', 'id' => 'montoefectivo', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="display: none;">
						<div  class="col-lg-4 col-md-4 col-sm-4">
							<img src="assets/images/visa.png" style="width: 60px; height: 60px">
						</div>
						<div  class="col-lg-8 col-md-8 col-sm-8">
							{!! Form::text('montovisa', '', array('class' => 'form-control input-lg montos', 'id' => 'montovisa', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="display: none;">
						<div  class="col-lg-4 col-md-4 col-sm-4">
							<img src="assets/images/master.png" style="width: 60px; height: 40px">
						</div>
						<div  class="col-lg-8 col-md-8 col-sm-8">
							{!! Form::text('montomaster', '', array('class' => 'form-control input-lg montos', 'id' => 'montomaster', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="margin-top: 10px;">
						{!! Form::label('total', 'Total:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
						{!! Form::text('total', '', array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 30px; margin-top: 25px;')) !!}
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
						{!! Form::label('vuelto', 'Vuelto:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
						{!! Form::text('vuelto', '', array('class' => 'form-control input-lg', 'id' => 'vuelto', 'readOnly', 'style' => 'text-align: right; font-size: 30px; color: red;  margin-top: 25px;', 'placeholder' => '0.00')) !!}
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="text-align:right">
						{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array( 'class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md btnGuardar', 'id' => 'btnGuardar' , 'style' => 'margin-top: 23px;' )) !!}
					</div>
				</div>

			{!! Form::close() !!}
			<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 15px;">
				<div id="divMensajeError{!! $entidad !!}"></div>
			</div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

	//$('#vale_balon_monto').prop('checked', false);
	$('#vale_balon_subcafae').prop('disabled', true);
	//$('#vale_balon_monto').prop('checked', false);
	$('#vale_balon_monto').prop('disabled', true);
	$('#codigo_vale_monto').prop('disabled', true);
	$('#monto_vale_balon').prop('disabled', true);
	//$('#vale_balon_sisfoh').prop('checked', false);
	$('#vale_balon_fise').prop('disabled', true);
	$('#codigo_vale_fise').prop('disabled', true);
	$('#monto_vale_fise').prop('disabled', true);

	$('#balon_nuevo').prop('disabled', true);
	//$('#balon_a_cuenta').prop('disabled', true);

	$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'}); 

	//colocar total 0.00
	$("#total").val((0).toFixed(2));
	$("#vuelto").val((0).toFixed(2));

	//cant = 0
	$("#cant").val(0);

	//cantidad = 1 servicio o producto
	//$("#cantidad").val(1);

	$("#tipodocumento_id").val(3);

	$("#serieventa").inputmask({"mask": "9999-9999999"});
	$("#codigo_vale_subcafae").inputmask({"mask": "99999"});
	$("#codigo_vale_fise").inputmask({"mask": "99999999*************"});

	$("#monto_vale_balon").keyup(function(){
		if( $("#monto_vale_balon").val() != ""){
			if( is_numeric( $("#monto_vale_balon").val())){
				var total = 0;
				$("#detalle tr").each(function(){
					var cantidad = parseInt($(this).attr('cantidad'));
					var precio = parseFloat($(this).attr('precio'));
					total += precio*cantidad;
				});
				$("#total").val(total.toFixed(2));
				var monto_vale_balon = parseFloat($("#monto_vale_balon").val());
				if(monto_vale_balon < 0 ||  monto_vale_balon > total){
					$("#monto_vale_balon").val("");
				}else{
					calcularTotal();
				}
			}else{
				$("#monto_vale_balon").val("");
			}
		}else{
			calcularTotal();
		}
	}); 

	// a continuacion creamos la fecha en la variable date
	var date = new Date()
	// Luego le sacamos los datos año, dia, mes 
	// y numero de dia de la variable date
	var año = date.getFullYear()
	var mes = date.getMonth()
	var ndia = date.getDate()
	//Damos a los meses el valor en número
	mes+=1;
	if(mes<10) mes="0"+mes;
	if(ndia<10) ndia="0"+ndia;
	//juntamos todos los datos en una variable
	var fecha = ndia + "/" + mes + "/" + año
	$('#fecha').val(fecha);

	$('#activar_checkbox').val(0);
	var activar_checkbox = $('#activar_checkbox').val();
	clickVales();

	mostrarultimo();

	$('.btnBorrar').on('click', function(){
		$('#cliente_id').val("");
		$('#cliente').val("");
		$('#cliente_direccion').val("");
		$("#cliente").prop('disabled',false);
	});

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$("#montoefectivo").keyup(function(){

		var credito = $("#credito").val();

		if( credito == 1){

			if( $("#montoefectivo").val() == ""){
				//$('#total').val(saldo.toFixed(2));
			}else{ 

				if( is_numeric($("#montoefectivo").val()) == true){

					var monto = parseFloat($("#montoefectivo").val());
					var total = parseFloat($("#total").val());
					if(monto < 0 ||  monto > total){
						$("#montoefectivo").val("");
					}

				}else{

					$("#montoefectivo").val("");
					$("#vuelto").val((0).toFixed(2));

				}
			}
		}else{

			if( is_numeric($("#montoefectivo").val()) == true){

			}else{

			$("#montoefectivo").val("");
			$("#vuelto").val((0).toFixed(2));

			}

		}
	}); 

	generarNumeroSerie();

	permisoRegistrar();

	$(".montos").blur(function() {
		if($('#montoefectivo').val() != ""){
			var montoefectivo = parseFloat($('#montoefectivo').val());
		}else{
			var montoefectivo = 0.00;
		}
		if($('#montovisa').val() != ""){
			var montovisa = parseFloat($('#montovisa').val());
		}else{
			var montovisa = 0.00;
		}
		if($('#montomaster').val() != ""){
			var montomaster = parseFloat($('#montomaster').val());
		}else{
			var montomaster = 0.00;
		}
		var total = parseFloat($("#total").val());
		var vuelto = montoefectivo + montovisa + montomaster - total;
		if(vuelto < 0){
			vuelto =0.00;
		}
		$('#vuelto').val(vuelto.toFixed(2));

		if( !$("#balon_a_cuenta").parent().hasClass('checked') ){

			if(montoefectivo - vuelto  + montovisa + montomaster != total){
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual o mayor al TOTAL.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				$('#btnGuardar').prop('disabled', true);
			}else{
				$('#divMensajeErrorVenta').html("");
				$('#btnGuardar').prop('disabled', false);
			}

		}
	});

	$(document).on('mouseover', function(){
		$('.servicio_frecuente').css('background', 'rgb(255,255,255)');
	});

	agregarProducto();

	$('#btnGuardar').on('click', function(){
		var sucursal = document.getElementById("sucursal_id");
		var empleado = $('#empleado_id').val();
		var cliente = $('#cliente_id').val();
		var cant = parseInt($("#cant"). val());
		var tipo = $('#tipodocumento_id').val();
		var total = parseFloat($("#total").val());

		var montoefectivo = "";
		if( $("#montoefectivo").val() == "" ){
			montoefectivo = 0;
		}else{
			montoefectivo = parseFloat($("#montoefectivo").val());
		}

		var letra = "";
		if(tipo == 1){
			letra ="BV";
		}else if(tipo == 2){
			letra ="FV";
		}else if(tipo == 3){
			letra ="TK";
		}


		if(!empleado || cant==0 || !cliente || ( $("#vale_balon_fise").parent().hasClass('checked') && $("#codigo_vale_fise").val() == "" ) || ( $("#vale_balon_subcafae").parent().hasClass('checked') && $("#codigo_vale_subcafae").val() == "" ) || ( $("#vale_balon_monto").parent().hasClass('checked') && ( $("#codigo_vale_monto").val() == "" || $("#monto_vale_balon").val()  == "" ))  ){  // $("balon_a_cuenta").prop('checked') 
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
			if(!empleado){
				cadenaError += ' <li> El campo Empleado es obligatorio.</li>';
			}
			if(!cliente){
				cadenaError += ' <li> El campo Cliente es obligatorio.</li>';
			}
			if(cant ==0){
				cadenaError += '<li>Debe agregar mínimo un producto.</li>';
			}
			if( $("#vale_balon_fise").parent().hasClass('checked') && $("#codigo_vale_fise").val() == "" ){
				cadenaError += '<li>Ingresa Código de vale FISE.</li>';
			}
			if( $("#vale_balon_subcafae").parent().hasClass('checked') && $("#codigo_vale_subcafae").val() == "" ){
				cadenaError += '<li>Ingresa Código de vale SUBCAFAE.</li>';
			}
			if( $("#vale_balon_monto").parent().hasClass('checked') && ( $("#codigo_vale_monto").val() == "" || $("#monto_vale_balon").val()  == "" )){
				if($("#codigo_vale_monto").val() == "" ){
				cadenaError += '<li>Ingresa Código de vale monto.</li>';
				}
				if($("#monto_vale_balon").val() == "" ){
				cadenaError += '<li>Ingresa Monto de descuento de vale monto.</li>';
				}
			}
			cadenaError += "</ul></div>";
			$('#divMensajeErrorVenta').html(cadenaError);
		}else{

			var mensaje = "<div style='text-align: left; padding: 20px; font-size: 15;'><p><label>Sucursal:  </label>  "+ sucursal.options[sucursal.selectedIndex].text 
							+"</p><p><label>N° Venta: </label>  "+ letra+ $('#serieventa').val() ;

			if( $("#vale_balon_fise").prop('checked') ){
				mensaje += "</p><p><label>Vale:  </label>  "+ "FISE"
				+"</p><p><label>Código FISE:  </label>  "+ $('#codigo_vale_fise').val()
			}
			if( $("#vale_balon_subcafae").prop('checked') ){
				mensaje += "</p><p><label>Vale:  </label>  "+ "SUBCAFAE"
				+"</p><p><label>Código SUBCAFAE:  </label>  "+ $('#codigo_vale_subcafae').val()
			}
			if( $("#vale_balon_monto").prop('checked') ){
				mensaje += "</p><p><label>Vale:  </label>  "+ "VALE MONTO"
				+"</p><p><label>Código MONTO:  </label>  "+ $('#codigo_vale_monto').val()
				+"</p><p><label>Monto del Vale:  </label>  S/."+ parseFloat($('#monto_vale_balon').val()).toFixed(2)
			}

			mensaje +="</p><p><label>Empleado:  </label>  "+ $('#empleado_nombre').val()
					+"</p><p><label>Cliente:  </label>  "+ $('#cliente').val()
					+"</p><p><label>Dirección:  </label>  "+ $('#cliente_direccion').val()
					+"</p><p><label>Total:  </label>  S/."+  total.toFixed(2);

			
			if( $("#balon_a_cuenta").prop('checked') ){
				mensaje += "</p><p><label>Pedido a crédito:  </label>  "+ "SI"
						+ "</p><p><label>Monto a cobrar:  </label>  S/."+  montoefectivo.toFixed(2)
						+ "</p><p><label>Cuenta por cobrar:  </label>  S/."+  (total-montoefectivo).toFixed(2);
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
						guardarventa();
						setTimeout(function(){
							cargarRutaMenu('pedidos', 'container', '15');
						},1000);
					}
				});
		}
	
	});

	$('.vales .iCheck-helper').on('click', function(){
		var divpadre = $(this).parent();
		var input = divpadre.find('input');

		if( input.attr('id') == 'balon_a_cuenta'){ //codigo_vale_subcafae
			if( divpadre.hasClass('checked')) { 
				console.log("credito");
				$("#credito").val("1");

				$("#montoefectivo").val("");
				$("#vuelto").val((0).toFixed(2));

				$("#btnGuardar").prop('disabled',false);
				$('#balon_a_cuenta').prop('checked',true);
				$('#divMensajeErrorVenta').html("");

			}else{

				console.log("sin credito");

				$("#montoefectivo").val("");
				$("#credito").val("0");

				$("#btnGuardar").prop('disabled',true);
				$('#balon_a_cuenta').prop('checked',false);
			}
		}
		calcularTotal();

	});


});

function generarEmpleados(){

	var empleados = null;

	var tabla = "";

	var sucursal_id = $('#sucursal_id').val();

	$.ajax({
		"method": "POST",
		"url": "{{ url('/turno/cargarempleados') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		empleados = info;
	}).always(function(){

		if( empleados != ""){
			$('.page-ventaa').html("SELECCIONE REPARTIDOR");
			$('.page-ventaa').css("display","none");
			$('.page-ventaa').css("color","black");
		}else{
			$('.page-ventaa').html("NO HAY REPARTIDOR EN TURNO");
			$('.page-ventaa').css("display","");
			$('.page-ventaa').css("color","red");
		}

		$.each(empleados, function(i, item) {
			tabla =  tabla +'<div class="empleado" id="' + item.id + '" style="margin: 5px; width: 120px; height: 110px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;"><img src="assets/images/empleado.png" style="width: 50px; height: 50px"><label style="font-size: 11px;  color: #2a3f54;">' + item.nombres + ' ' + item.apellido_pat  + ' ' + item.apellido_mat +'</label></div>';   
		});

		$('#empleados').html(tabla);

		$(".empleado").on('click', function(){
			var idempleado = $(this).attr('id');
			$(".empleado").css('background', 'rgb(255,255,255)');
			$(this).css('background', 'rgb(179,188,237)');
			$('#empleado_id').attr('value',idempleado);
			$("#empleado_nombre").val($(this).children('label').html());
		});
	});

}

function agregarProducto(){
	//AGREGAR SERVICIO
	$(".servicio_frecuente").on('click', function(){
		var elemento = this;
		var idservicio_frecuente = $(this).attr('id');
		var precio = parseFloat($(this).attr('precio'));
		var descripcion = $(this).attr('descripcion');
		var editable = $(this).attr('editable');
		var stock = $(this).attr('stock');
		var cant = $("#cant").val();

		$(this).css('background', 'rgb(179,188,237)');
		
		var existe = false;

		if(idservicio_frecuente == 4 || idservicio_frecuente == 5 ){
			$('#activar_checkbox').val(1);
			activar_checkbox = $('#activar_checkbox').val();
			clickVales();

			$("#detalle tr").each(function(){
				var id = parseInt($(this).attr('id'));
				var cantidad = $(this).attr('cantidad');
				if( id == "5" ){
					$(this).attr('precio', (37).toFixed(2));
					var trprecio = $(this).find('.precioeditable');
					$(trprecio).val((37).toFixed(2));
					var trprecioacumulado = $(this).find('.precioacumulado');
					$(trprecioacumulado).html((37*cantidad).toFixed(2));
					var btneliminar = $(this).find('.btnEliminar');
					$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
					calcularTotal();
				}else if( id == "4" ){
					$(this).attr('precio', (36).toFixed(2));
					var trprecio = $(this).find('.precioeditable');
					$(trprecio).val((36).toFixed(2));
					var trprecioacumulado = $(this).find('.precioacumulado');
					$(trprecioacumulado).html((36*cantidad).toFixed(2));
					var btneliminar = $(this).find('.btnEliminar');
					$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
					calcularTotal();
				}
			});
			$('#balon_nuevo').prop('disabled', false);
			//$('#balon_a_cuenta').prop('disabled', false);
			$('#vale_balon_subcafae').prop('disabled', false);
			$('#vale_balon_monto').prop('disabled', false);
			$('#codigo_vale_monto').prop('disabled', false);
			$('#monto_vale_balon').prop('disabled', false);
			$('#vale_balon_fise').prop('disabled', false);
			$('#codigo_vale_fise').prop('disabled', false);
			$('#monto_vale_fise').prop('disabled', false);
			$('#vale_balon_subcafae').prop('checked', false);
			$('#vale_balon_monto').prop('checked', false);
			$('#vale_balon_fise').prop('checked', false)
			$('#vale_balon_subcafae').parent().removeClass('disabled');
			$('#vale_balon_monto').parent().removeClass('disabled');
			$('#vale_balon_fise').parent().removeClass('disabled');
			$('#vale_balon_subcafae').parent().removeClass('checked');
			$('#vale_balon_monto').parent().removeClass('checked');
			$('#vale_balon_fise').parent().removeClass('checked');
			$('#balon_nuevo').parent().removeClass('disabled');
			//$('#balon_a_cuenta').parent().removeClass('disabled');
			$('#balon_nuevo').parent().removeClass('checked');
			
			//$('#balon_a_cuenta').parent().removeClass('checked');
			
			$('#balon_nuevo').prop('checked', false);
			//$('#balon_a_cuenta').prop('checked', false)
			$('#codigo_vale_monto').prop('readOnly', true);
			$('#codigo_vale_subcafae').prop('readOnly', true);
			$('#codigo_vale_fise').prop('readOnly', true);
			$('#monto_vale_balon').prop('readOnly', true);
			$('#monto_vale_fise').prop('readOnly', true);
			$('#monto_vale_balon').val('');
			$('#monto_vale_fise').val('');
			$('#codigo_vale_monto').val('');
			$('#codigo_vale_subcafae').val('');
			$('#codigo_vale_fise').val('');
		}

		if(cant != 0){
			$("#detalle tr").each(function(){
				if(idservicio_frecuente == this.id){
					if($(this).attr('class') == "DetalleServicio"){
						existe = true;
						var cantidadfila = parseInt($(this).attr('cantidad'));
						var stockfila = parseInt($(this).attr('stock'));
						var precioactual = parseFloat($(this).attr('precio'));
						if(stockfila > cantidadfila){
							cantidadfila++;
							$(this).attr('cantidad',cantidadfila);
							if(editable == 0){
								var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;"><input class="form-control input-xs cantidadeditable" style="text-align: right; width: 70px;" type="text" value="'+ cantidadfila +'"></td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;">'+ (precio*cantidadfila).toFixed(2) +'</td><td class="precioacumulado" style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto='+ idservicio_frecuente +' precio='+ (precio*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
							}else if(editable == 1){
								var nuevafila = '<td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;"><input class="form-control input-xs cantidadeditable" style="text-align: right; width: 70px;" type="text" value="'+ cantidadfila +'"></td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precioactual).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precioactual*cantidadfila).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto='+ idservicio_frecuente +' precio='+ (precioactual*cantidadfila).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td>';
							}
							$(this).html(nuevafila);
							calcularTotal();
						}else{
							swal({
								title: 'NO HAY STOCK SUFICIENTE',
								type: 'error',
							});
						}
					}
				}
			});
		}

		if(!existe){
			if(editable == 0){
				var fila =  '<tr align="center" class="DetalleServicio" id="'+ idservicio_frecuente +'" cantidad="'+ 1 +'" precio='+ (precio).toFixed(2) +' stock='+ stock +'><td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;"><input class="form-control input-xs cantidadeditable" style="text-align: right; width: 70px;" type="text" value="'+ 1 +'"></td><td style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td class="precioacumulado" style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto='+ idservicio_frecuente +' precio='+ (precio).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
			}else if(editable == 1){
				var fila =  '<tr align="center" class="DetalleServicio" id="'+ idservicio_frecuente +'" cantidad="'+ 1 +'" precio='+ (precio).toFixed(2) +' stock='+ stock +'><td style="vertical-align: middle; text-align: left;">'+ descripcion +'</td><td style="vertical-align: middle;"><input class="form-control input-xs cantidadeditable" style="text-align: right; width: 70px;" type="text" value="'+ 1 +'"></td><td style="vertical-align: middle;"><input class="form-control input-xs precioeditable" style="text-align: right; width: 70px;" type="text" value="'+ (precio).toFixed(2) +'"></td><td class="precioacumulado" style="vertical-align: middle;">'+ (precio).toFixed(2) +'</td><td style="vertical-align: middle;"><a onclick="eliminarDetalle(this)" class="btn btn-xs btn-danger btnEliminar" idproducto='+ idservicio_frecuente +' precio='+ (precio).toFixed(2) +' type="button"><div class="glyphicon glyphicon-remove"></div> Eliminar</a></td></tr>';
			}
			$("#detalle").append(fila);
			cant++;
			$("#cant").val(cant);
			calcularTotal();
		}

		$(".precioeditable").blur(function() {
			var elemento = this;
			var precionuevo = parseFloat($(this).val());
			if( is_numeric(precionuevo) ){
				if(precionuevo > 0){
					var tr = $(this).parent().parent();
					var precioactual = $(tr).attr('precio');
					var cantidad = $(tr).attr('cantidad');
					$(tr).attr('precio',(precionuevo).toFixed(2));
					var trprecioacumulado = $(tr).find('.precioacumulado');
					$(trprecioacumulado).html((precionuevo*cantidad).toFixed(2));
					var btneliminar = $(tr).find('.btnEliminar');
					$(btneliminar).attr('precio',(precionuevo*cantidad).toFixed(2));
					calcularTotal();
					$("#montoefectivo").val("");
					$("#montovisa").val("");
					$("#montomaster").val("");
					$("#vuelto").val((0).toFixed(2));
					$('#divMensajeErrorVenta').html("");
					//$('#btnGuardar').prop('disabled', false);
				}else{
					var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
					cadenaError += '<li>Los campos de precios editables deben ser montos positivos.</li></ul></div>';
					$('#divMensajeErrorVenta').html(cadenaError);
					//$('#btnGuardar').prop('disabled', true);
					var tr = $(this).parent().parent();
					var precioactual = $(tr).attr('precio');
					$(this).val(precioactual);
				}
			}else{
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>Los campos de precios editables deben ser númericos.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				//$('#btnGuardar').prop('disabled', true);
				var tr = $(this).parent().parent();
				var precioactual = $(tr).attr('precio');
				$(this).val(precioactual);
			}
		});
		
		$(".cantidadeditable").blur(function() {
			var elemento = this;
			var cantidadnueva = parseInt($(this).val());
			if( is_numeric(cantidadnueva) ){
				if(cantidadnueva > 0){
					var tr = $(this).parent().parent();
					var precioactual = parseFloat($(tr).attr('precio'));
					var stockactual = $(tr).attr('stock');
					console.log( stockactual + ' < ' + cantidadnueva);
					if(stockactual >= cantidadnueva ){
						//var cantidad = $(tr).attr('cantidad');
						$(tr).attr('precio',precioactual);
						$(tr).attr('cantidad',cantidadnueva);
						var trprecioacumulado = $(tr).find('.precioacumulado');
						$(trprecioacumulado).html((precioactual*cantidadnueva).toFixed(2));
						var btneliminar = $(tr).find('.btnEliminar');
						$(btneliminar).attr('precio',(precioactual*cantidadnueva).toFixed(2));
						calcularTotal();
						$("#montoefectivo").val("");
						$("#montovisa").val("");
						$("#montomaster").val("");
						$("#vuelto").val((0).toFixed(2));
						$('#divMensajeErrorVenta').html("");
						//$('#btnGuardar').prop('disabled', false);
					}else{
						swal({
							title: 'NO HAY STOCK SUFICIENTE',
							type: 'error',
						});
						var tr = $(this).parent().parent();
						var cantidadactual = $(tr).attr('cantidad');
						$(this).val(cantidadactual);
					}
				}else{
					var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
					cadenaError += '<li>Los campos de cantidad deben ser números positivos.</li></ul></div>';
					$('#divMensajeErrorVenta').html(cadenaError);
					//$('#btnGuardar').prop('disabled', true);
					var tr = $(this).parent().parent();
					var cantidadactual = $(tr).attr('cantidad');
					$(this).val(cantidadactual);
				}
			}else{
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>Los campos de cantidad deben ser númericos.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				//$('#btnGuardar').prop('disabled', true);
				var tr = $(this).parent().parent();
				var cantidadactual = $(tr).attr('cantidad');
				$(this).val(cantidadactual);
			}
		});
		
		$("#montoefectivo").val("");
		$("#montovisa").val("");
		$("#montomaster").val("");
		$("#vuelto").val((0).toFixed(2));

		if($('#montoefectivo').val() != ""){
			var montoefectivo = parseFloat($('#montoefectivo').val());
		}else{
			var montoefectivo = 0.00;
		}
		if($('#montovisa').val() != ""){
			var montovisa = parseFloat($('#montovisa').val());
		}else{
			var montovisa = 0.00;
		}
		if($('#montomaster').val() != ""){
			var montomaster = parseFloat($('#montomaster').val());
		}else{
			var montomaster = 0.00;
		}
		var total = parseFloat($("#total").val());
		var vuelto = montoefectivo + montovisa + montomaster - total;
		if(vuelto < 0){
			vuelto =0.00;
		}
		$('#vuelto').val(vuelto.toFixed(2));

		if( !$("#balon_a_cuenta").parent().hasClass('checked') ){
			
			if(montoefectivo - vuelto  + montovisa + montomaster != total){
				var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
				cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual o mayor al TOTAL.</li></ul></div>';
				$('#divMensajeErrorVenta').html(cadenaError);
				$('#btnGuardar').prop('disabled', true);
			}else{
				$('#divMensajeErrorVenta').html("");
				$('#btnGuardar').prop('disabled', false);
			}

		}

	});
}

function clickVales(){

	activar_checkbox = $('#activar_checkbox').val();
	console.log("activar checks = "+ activar_checkbox);	

	$('.vales .iCheck-helper').on('click', function(){

		var divpadre = $(this).parent();
		var input = divpadre.find('input');

		if(activar_checkbox == 1){
			if( input.attr('id') == 'vale_balon_monto' ){
				if( divpadre.hasClass('checked')) { 
					//console.log('seleccionar balon monto');
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "5" ){
							$(this).attr('precio', (37).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((37).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((37*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
							calcularTotal();
						}else if( id == "4" ){
							$(this).attr('precio', (36).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((36).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((36*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
							calcularTotal();
						}
					});
					//activar vale monto
					$('#vale_balon_monto').prop('checked',true);
					$('#monto_vale_balon').prop('readOnly',false);
					$('#codigo_vale_monto').prop('readOnly',false);
					//desactivar demas vales
					$('#monto_vale_fise').prop('readOnly',true);
					$('#codigo_vale_fise').prop('readOnly',true);
					$('#codigo_vale_subcafae').prop('readOnly',true);
					$('#monto_vale_fise').val('');
					$('#codigo_vale_fise').val('');
					$('#codigo_vale_subcafae').val('');
					$('#vale_balon_fise').parent().removeClass('checked');
					$('#vale_balon_fise').prop('checked',false);
					$('#vale_balon_subcafae').parent().removeClass('checked');
					$('#vale_balon_subcafae').prop('checked',false);
				}else {
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "5" ){
							$(this).attr('precio', (37).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((37).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((37*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
							calcularTotal();
						}else if( id == "4" ){
							$(this).attr('precio', (36).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((36).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((36*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
							calcularTotal();
						}
					});
					$('#monto_vale_balon').prop('readOnly',true);
					$('#codigo_vale_monto').prop('readOnly',true);
					$('#monto_vale_balon').val('');
					//console.log('deseleccionar balon monto');
					//divpadre.addClass('checked');
					$('#vale_balon_fise').parent().removeClass('checked');
					$('#vale_balon_subcafae').parent().removeClass('checked');
				}
			}else if( input.attr('id') == 'vale_balon_fise'){
				if( divpadre.hasClass('checked')) { 
					//console.log('seleccionar balon fise');
					//modificar precio del balon normal
					var sucursal_id = $("#sucursal_id").val();
					var primero = true;
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "4" || id == "5"){
							if( sucursal_id == 1 && primero == true){
								$(this).attr('precio', (36).toFixed(2));
								var trprecio = $(this).find('.precioeditable');
								$(trprecio).val((36).toFixed(2));
								var trprecioacumulado = $(this).find('.precioacumulado');
								$(trprecioacumulado).html((36*cantidad).toFixed(2));
								var btneliminar = $(this).find('.btnEliminar');
								$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
								primero = false;
								calcularTotal();
								var total = $("#total").val();
								$("#total").val( (total - 16).toFixed(2) );
							}else if( sucursal_id == 2 && primero == true){
								$(this).attr('precio', (35).toFixed(2));
								var trprecio = $(this).find('.precioeditable');
								$(trprecio).val((35).toFixed(2));
								var trprecioacumulado = $(this).find('.precioacumulado');
								$(trprecioacumulado).html((35*cantidad).toFixed(2));
								var btneliminar = $(this).find('.btnEliminar');
								$(btneliminar).attr('precio',(35*cantidad).toFixed(2));
								primero = false;
								calcularTotal();
								var total = $("#total").val();
								$("#total").val( (total - 16).toFixed(2) );
							}
						}
					});
					//activar vale sisfoh
					$('#vale_balon_fise').prop('checked',true);
					$('#monto_vale_fise').prop('readOnly',false);
					$('#codigo_vale_fise').prop('readOnly',false);
					//desactivar demas vales
					$('#monto_vale_balon').prop('readOnly',true);
					$('#monto_vale_fise').prop('readOnly',true);
					$('#codigo_vale_monto').prop('readOnly',true);
					$('#codigo_vale_subcafae').prop('readOnly',true);
					$('#monto_vale_balon').val('');
					$('#codigo_vale_monto').val('');
					$('#codigo_vale_subcafae').val('');
					$('#monto_vale_fise').val((16).toFixed(2));
					$('#vale_balon_monto').parent().removeClass('checked');
					$('#vale_balon_monto').prop('checked',false);
					$('#vale_balon_subcafae').parent().removeClass('checked');
					$('#vale_balon_subcafae').prop('checked',false);
				}else {
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "5" ){
							$(this).attr('precio', (37).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((37).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((37*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
							calcularTotal();
						}else if( id == "4" ){
							$(this).attr('precio', (36).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((36).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((36*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
							calcularTotal();
						}
					});
					$('#monto_vale_fise').prop('readOnly',true);
					$('#codigo_vale_fise').prop('readOnly',true);
					$('#monto_vale_fise').val('');
					//console.log('deseleccionar balon sisfoh');
					//divpadre.addClass('checked');
					$('#vale_balon_monto').parent().removeClass('checked');
					$('#vale_balon_monto').prop('checked',false);
					$('#vale_balon_subcafae').parent().removeClass('checked');
					$('#vale_balon_subcafae').prop('checked',false);
				}
			}else if( input.attr('id') == 'vale_balon_subcafae'){ //codigo_vale_subcafae
				if( divpadre.hasClass('checked')) { 
					//console.log('seleccionar balon subcafae');
					var primero = true;
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "5"  && primero == true){
							$(this).attr('precio', (36).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((36).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((36*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
							primero = false;
							calcularTotal();
							var total = $("#total").val();
							$("#total").val( (total - 36).toFixed(2) );
						}else if( id == "4"  && primero == true ){
							$(this).attr('precio', (37).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((37).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((37*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
							primero = false;
							calcularTotal();
							var total = $("#total").val();
							$("#total").val( (total - 37).toFixed(2) );
						}
					});
					$('#codigo_vale_subcafae').prop('readOnly',false);
					$('#codigo_vale_subcafae').prop('disabled',false);
					$('#codigo_vale_fise').prop('readOnly',true);
					$('#codigo_vale_monto').prop('readOnly',true);
					$('#vale_balon_fise').parent().removeClass('checked');
					$('#vale_balon_fise').prop('checked',false);
					$('#vale_balon_monto').parent().removeClass('checked');
					$('#vale_balon_monto').prop('checked',false);
					$('#monto_vale_balon').prop('readOnly',true);
					$('#monto_vale_fise').prop('readOnly',true);
					divpadre.addClass('checked');
					$('#monto_vale_balon').val('');
					$('#monto_vale_fise').val('');
					$('#codigo_vale_monto').val('');
					$('#codigo_vale_fise').val('');
					$('#divMensajeErrorVenta').html("");
					//$('#btnGuardar').prop('disabled', false);
				}else {
					$("#detalle tr").each(function(){
						var id = parseInt($(this).attr('id'));
						var cantidad = $(this).attr('cantidad');
						if( id == "5" ){
							$(this).attr('precio', (37).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((37).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((37*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
							calcularTotal();
						}else if( id == "4" ){
							$(this).attr('precio', (36).toFixed(2));
							var trprecio = $(this).find('.precioeditable');
							$(trprecio).val((36).toFixed(2));
							var trprecioacumulado = $(this).find('.precioacumulado');
							$(trprecioacumulado).html((36*cantidad).toFixed(2));
							var btneliminar = $(this).find('.btnEliminar');
							$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
							calcularTotal();
						}
					});
					$('#monto_vale_fise').prop('readOnly',true);
					$('#codigo_vale_fise').prop('readOnly',true);
					$('#codigo_vale_monto').prop('readOnly',true);
					$('#codigo_vale_subcafae').prop('readOnly',true);
					//console.log('deseleccionar balon lleno');
					$('#codigo_vale_subcafae').val('');
					//divpadre.addClass('checked');
					$('#vale_balon_monto').parent().removeClass('checked');
					$('#vale_balon_monto').prop('checked',false);
					$('#vale_balon_subcafae').parent().removeClass('checked');
				}
			}/*else if( input.attr('id') == 'balon_a_cuenta'){ //codigo_vale_subcafae
				if( divpadre.hasClass('checked')) { 
					console.log("credito");
					$("#credito").val("1");

					$("#montoefectivo").val("");
					$("#vuelto").val((0).toFixed(2));

					$("#btnGuardar").prop('disabled',false);
					$('#balon_a_cuenta').prop('checked',true);
					$('#divMensajeErrorVenta').html("");

				}else{

					console.log("sin credito");

					$("#montoefectivo").val("");
					$("#credito").val("0");

					$("#btnGuardar").prop('disabled',true);
					$('#balon_a_cuenta').prop('checked',false);
				}
			}*/
			calcularTotal();
		}
	});
}

function generarProductos(){

	var productos = null;

	var tabla = "";

	var sucursal_id = $('#sucursal_id').val();

	$.ajax({
		"method": "POST",
		"url": "{{ url('/venta/cargarproductos') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		productos = info;
	}).always(function(){

		$.each(productos, function(i, item) {
			tabla =  tabla +'<div class="servicio_frecuente col-lg-3 col-md-3 col-sm-3" id="' + item.id  + '"  precio="' + item.precio_venta + '" descripcion="' + item.descripcion + '" editable="' + item.editable + '" stock="' + item.cantidad + '" style="margin: 5px; width: 85px; height: 75px; text-align: center; border-style: solid; border-color: #2a3f54; border-radius: 10px;" ><label style="font-size: 9.5px; color: #2a3f54; padding-top: 13px;">' + item.descripcion + '</label><label style="font-size: 9.5px; color: #2a3f54;">STOCK: ' + item.cantidad + '</label></div>';
		});

		$('#servicios_frecuentes').html(tabla);

		agregarProducto();

		$("#detalle").html("");

		$("#cant").val(0);

		desactivarVales();

		$('#activar_checkbox').val(0);
		activar_checkbox = $('#activar_checkbox').val();
		clickVales();

		calcularTotal();

	});

}

function mostrarultimo(){
	var cliente = null;
	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/cliente/ultimocliente') }}",
		"data": {
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		cliente = info;
	}).always(function(){
		if( $("#ultimo_cliente").val() == "" ){
			$("#ultimo_cliente").val(cliente.id);
		}else{
			if( $("#ultimo_cliente").val() != cliente.id){
				if(cliente.dni != null){
					$('#cliente').val(cliente.apellido_pat + " " + cliente.apellido_mat + " " + cliente.nombres);
				}else{
					$('#cliente').val(cliente.razon_social);
				}
				$('#cliente_id').val(cliente.id);
				$('#cliente_direccion').val(cliente.direccion);
				$('#ultimo_cliente').val('');
				$("#cliente").prop('disabled',true);
			}
		}
	});
}

function generarNumeroSerie(){
	var serieventa = null;

	var sucursal_id = $('#sucursal_id').val();

	$('#empleado_id').val("");

	var tipodocumento_id = $('#tipodocumento_id').val();

	var serieajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/serieventa') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"tipodocumento_id" : tipodocumento_id,
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		serieventa = info;
	}).always(function(){
		$('#serieventa').val(serieventa);
	});
}

function permisoRegistrar(){

	var aperturaycierre = null;

	var sucursal_id = $('#sucursal_id').val();

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/permisoRegistrar') }}",
		"data": {
			"sucursal_id" : sucursal_id, 
			"_token": "{{ csrf_token() }}",
			}
	}).done(function(info){
		aperturaycierre = info;
	}).always(function(){
		if(aperturaycierre == 0){
			$('form').find('input, textarea, button').prop('disabled',true);
			$("#tipodocumento_id").prop('disabled',true);

			$(".empleado").css('background', 'rgb(255,255,255)');

			$(".empleado").on('click', function(){
				$(".empleado").css('background', 'rgb(255,255,255)');
			});

			$('#divMensajeErrorVenta').html("");

			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul><li>Aperturar caja de la sucursal escogida</li></ul></div>';

			var surcursal_id = $('#sucursal_id').val();

			if(sucursal_id != null){
				$('#divMensajeErrorVenta').html(cadenaError);
			}


		}else if(aperturaycierre == 1){
			$('form').find('input, textarea, button').prop('disabled',false);
			$("#tipodocumento_id").prop('disabled',false);
			//$("#cliente").prop('disabled',true);
			

			$(".empleado").css('background', 'rgb(255,255,255)');

			$(".empleado").on('click', function(){
				var idempleado = $(this).attr('id');
				$(".empleado").css('background', 'rgb(255,255,255)');
				$(this).css('background', 'rgb(179,188,237)');
				$('#empleado_id').attr('value',idempleado);
				$("#empleado_nombre").val($(this).children('label').html());

				$('#divMensajeErrorVenta').html("");
			});

			$('#divMensajeErrorVenta').html("");

		}
	});

	return aperturaycierre;
}

function desactivarVales(){
	//desactibar checkbox
	$('#vale_balon_subcafae').prop('disabled', true);
	$('#vale_balon_monto').prop('disabled', true);
	$('#vale_balon_fise').prop('disabled', true);

	$('#vale_balon_subcafae').prop('checked', false);
	$('#vale_balon_monto').prop('checked', false);
	$('#vale_balon_fise').prop('checked', false);

	//desactivar inputs
	$('#monto_vale_balon').val('');
	$('#monto_vale_fise').val('');
	$('#codigo_vale_monto').val('');
	$('#codigo_vale_fise').val('');
	$('#codigo_vale_subcafae').val('');

	$('#codigo_vale_subcafae').prop('disabled', true);
	$('#codigo_vale_monto').prop('disabled', true);
	$('#codigo_vale_fise').prop('disabled', true);
	$('#monto_vale_balon').prop('disabled', true);
	$('#monto_vale_fise').prop('disabled', true);

	$('#vale_balon_subcafae').parent().removeClass('checked');
	$('#vale_balon_monto').parent().removeClass('checked');
	$('#vale_balon_fise').parent().removeClass('checked');

	$('#vale_balon_subcafae').parent().addClass('disabled');
	$('#vale_balon_monto').parent().addClass('disabled');
	$('#vale_balon_fise').parent().addClass('disabled');

	$('#balon_nuevo').prop('disabled', true);
	//$('#balon_a_cuenta').prop('disabled', true);

	$('#balon_nuevo').parent().removeClass('checked');
	//$('#balon_a_cuenta').parent().removeClass('checked');
	$('#balon_nuevo').parent().addClass('disabled');

	$('#balon_nuevo').prop('checked', false);
	$('#balon_a_cuenta').prop('checked', false);

}

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

function eliminarDetalle(comp){
	$("#detalle tr").each(function(){
		var id = parseInt($(this).attr('id'));
		var cantidad = $(this).attr('cantidad');
		if( id == "5" ){
			$(this).attr('precio', (37).toFixed(2));
			var trprecio = $(this).find('.precioeditable');
			$(trprecio).val((37).toFixed(2));
			var trprecioacumulado = $(this).find('.precioacumulado');
			$(trprecioacumulado).html((37*cantidad).toFixed(2));
			var btneliminar = $(this).find('.btnEliminar');
			$(btneliminar).attr('precio',(37*cantidad).toFixed(2));
			calcularTotal();
		}else if( id == "4" ){
			$(this).attr('precio', (36).toFixed(2));
			var trprecio = $(this).find('.precioeditable');
			$(trprecio).val((36).toFixed(2));
			var trprecioacumulado = $(this).find('.precioacumulado');
			$(trprecioacumulado).html((36*cantidad).toFixed(2));
			var btneliminar = $(this).find('.btnEliminar');
			$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
			calcularTotal();
		}
	});
	var precioeliminar = parseFloat($(comp).attr('precio'));
	var idproducto = $(comp).attr('idproducto');
	if(idproducto == 4 || idproducto == 5 ){
			//$('#activar_checkbox').val(0);
			$('#activar_checkbox').val(0);
			activar_checkbox = $('#activar_checkbox').val();
			clickVales();
			//desactibar checkbox
			$('#vale_balon_subcafae').prop('disabled', true);
			$('#vale_balon_monto').prop('disabled', true);
			$('#vale_balon_fise').prop('disabled', true);

			$('#vale_balon_subcafae').prop('checked', false);
			$('#vale_balon_monto').prop('checked', false);
			$('#vale_balon_fise').prop('checked', false);

			//desactivar inputs
			$('#codigo_vale_subcafae').prop('disabled', true);
			$('#codigo_vale_monto').prop('disabled', true);
			$('#monto_vale_balon').prop('disabled', true);
			$('#codigo_vale_fise').prop('disabled', true);
			$('#monto_vale_fise').prop('disabled', true);

			$('#vale_balon_subcafae').parent().addClass('disabled');
			$('#vale_balon_monto').parent().addClass('disabled');
			$('#vale_balon_fise').parent().addClass('disabled');

			$('#vale_balon_subcafae').parent().removeClass('checked');
			$('#vale_balon_monto').parent().removeClass('checked');
			$('#vale_balon_fise').parent().removeClass('checked');

			$('#balon_nuevo').prop('disabled', true);
			//$('#balon_a_cuenta').prop('disabled', true);

			$('#balon_nuevo').parent().removeClass('checked');
			//$('#balon_a_cuenta').parent().removeClass('checked'); // aqui

			$('#balon_nuevo').parent().addClass('disabled');

			$('#balon_nuevo').prop('checked', false);
			$('#balon_a_cuenta').prop('checked', false);

			$('#monto_vale_balon').val('');
			$('#monto_vale_fise').val('');
			$('#codigo_vale_monto').val('');
			$('#codigo_vale_fise').val('');
			$('#codigo_vale_subcafae').val('');
		}
	var cant = $("#cant"). val();
	cant--;
	$("#cant").val(cant);
	var total = parseFloat($("#total").val());
	total -= precioeliminar;
	$("#total").val(total.toFixed(2));
	
	$("#montoefectivo").val("");
	$("#montovisa").val("");
	$("#montomaster").val("");
	$("#vuelto").val((0).toFixed(2));

	(($(comp).parent()).parent()).remove();

	
	$('#monto_vale_balon').val('');
	$('#monto_vale_fise').val('');
	$('#codigo_vale_monto').val('');
	$('#codigo_vale_fise').val('');
	$('#codigo_vale_subcafae').val('');
			

	calcularTotal();
	
	$("#detalle tr").each(function(){
		var element = $(this); // <-- en la variable element tienes tu elemento
		var id = element.attr('id');

		if(id == 4 || id == 5 ){
			$('#activar_checkbox').val(1);
			activar_checkbox = $('#activar_checkbox').val();
			clickVales();
			//console.log(activar_checkbox);
			$('#balon_nuevo').prop('disabled', false);
			//$('#balon_a_cuenta').prop('disabled', false);
			$('#vale_balon_subcafae').prop('disabled', false);
			$('#vale_balon_monto').prop('disabled', false);
			$('#codigo_vale_monto').prop('disabled', false);
			$('#codigo_vale_subcafae').prop('disabled', false);
			$('#monto_vale_balon').prop('disabled', false);
			$('#vale_balon_fise').prop('disabled', false);
			$('#codigo_vale_fise').prop('disabled', false);
			$('#monto_vale_fise').prop('disabled', false);

			$('#codigo_vale_monto').prop('readOnly', true);
			$('#codigo_vale_fise').prop('readOnly', true);
			$('#codigo_vale_subcafae').prop('readOnly', true);
			$('#monto_vale_balon').prop('readOnly', true);
			$('#monto_vale_fise').prop('readOnly', true);
			
			$('#balon_nuevo').parent().removeClass('checked');
			//$('#balon_a_cuenta').parent().removeClass('checked');
			$('#vale_balon_subcafae').parent().removeClass('checked');
			$('#vale_balon_monto').parent().removeClass('checked');
			$('#vale_balon_fise').parent().removeClass('checked');

			$('#vale_balon_subcafae').parent().removeClass('disabled');
			$('#vale_balon_monto').parent().removeClass('disabled');
			$('#vale_balon_fise').parent().removeClass('disabled');
			$('#balon_nuevo').parent().removeClass('disabled');

		}

	});

	if($('#montoefectivo').val() != ""){
		var montoefectivo = parseFloat($('#montoefectivo').val());
	}else{
		var montoefectivo = 0.00;
	}
	if($('#montovisa').val() != ""){
		var montovisa = parseFloat($('#montovisa').val());
	}else{
		var montovisa = 0.00;
	}
	if($('#montomaster').val() != ""){
		var montomaster = parseFloat($('#montomaster').val());
	}else{
		var montomaster = 0.00;
	}
	var total = parseFloat($("#total").val());
	var vuelto = montoefectivo + montovisa + montomaster - total;
	if(vuelto < 0){
		vuelto =0.00;
	}
	$('#vuelto').val(vuelto.toFixed(2));

	if( !$("#balon_a_cuenta").parent().hasClass('checked') ){

		if(montoefectivo - vuelto  + montovisa + montomaster != total){
			var cadenaError = '<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Por favor corrige los siguentes errores:</strong><ul>';
			cadenaError += '<li>La SUMA de los montos EFECTIVO, VISA y MASTERCARD debe ser igual o mayor al TOTAL.</li></ul></div>';
			$('#divMensajeErrorVenta').html(cadenaError);
			$('#btnGuardar').prop('disabled', true);
		}else{
			$('#divMensajeErrorVenta').html("");
			$('#btnGuardar').prop('disabled', false);
		}
		
	}
}

function detalleventa(){
	var data = [];
	$("#detalle tr").each(function(){
		var element = $(this); // <-- en la variable element tienes tu elemento
		
		var id = element.attr('id');
		var cantidad = element.attr('cantidad');
		var precio = element.attr('precio');
	
		data.push(
			{"id": id , "cantidad": cantidad, "precio": precio }
		);

	});
	var detalle = {"data": data};
	var json = JSON.stringify(detalle);
	var respuesta = "";
	var sucursal_id = $('#sucursal_id').val();
	var cantidad = $('#cant').val();

	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/venta/guardardetalle') }}",
		"data": {
			"_token": "{{ csrf_token() }}",
			"sucursal_id" : sucursal_id, 
			"json": json,
			"cantidad": cantidad,
			}
	}).done(function(info){
		respuesta = info;
	}).always(function() {
		if (respuesta === 'OK') {
			$("#IDFORMMANTENIMIENTOVenta")[0].reset();

			//colocar total 0.00
			$("#total").val((0).toFixed(2));

			//cant = 0
			$("#cant").val(0);

			$("#tipodocumento_id").val(3);

			// a continuacion creamos la fecha en la variable date
			var date = new Date()
			// Luego le sacamos los datos año, dia, mes 
			// y numero de dia de la variable date
			var año = date.getFullYear()
			var mes = date.getMonth()
			var ndia = date.getDate()
			//Damos a los meses el valor en número
			mes+=1;
			if(mes<10) mes="0"+mes;
			if(ndia<10) ndia="0"+ndia;
			//juntamos todos los datos en una variable
			var fecha = ndia + "/" + mes + "/" + año
			$('#fecha').val(fecha);
			
			$("#detalle").html("");

			generarNumeroSerie();

			permisoRegistrar();

			$('#empleado_id').val("");
			$(".empleado").css('background', 'rgb(255,255,255)');
		}
	});
}

function actualizarPreciosVales(){
	var sucursal_id = $("#sucursal_id").val();
	if( $("#vale_balon_fise").prop("checked") ){
		var primero = true;
		$("#detalle tr").each(function(){
			var id = parseInt($(this).attr('id'));
			var cantidad = $(this).attr('cantidad');
			if( id == "4" || id == "5"){
				if( sucursal_id == 1 && primero == true){
					$(this).attr('precio', (36).toFixed(2));
					var trprecio = $(this).find('.precioeditable');
					$(trprecio).val((36).toFixed(2));
					var trprecioacumulado = $(this).find('.precioacumulado');
					$(trprecioacumulado).html((36*cantidad).toFixed(2));
					var btneliminar = $(this).find('.btnEliminar');
					$(btneliminar).attr('precio',(36*cantidad).toFixed(2));
					primero = false;
					calcularTotal();
					var total = $("#total").val();
					$("#total").val( (total - 16).toFixed(2) );
				}else if( sucursal_id == 2 && primero == true){
					$(this).attr('precio', (35).toFixed(2));
					var trprecio = $(this).find('.precioeditable');
					$(trprecio).val((35).toFixed(2));
					var trprecioacumulado = $(this).find('.precioacumulado');
					$(trprecioacumulado).html((35*cantidad).toFixed(2));
					var btneliminar = $(this).find('.btnEliminar');
					$(btneliminar).attr('precio',(35*cantidad).toFixed(2));
					primero = false;
					calcularTotal();
					var total = $("#total").val();
					$("#total").val( (total - 16).toFixed(2) );
				}
			}
		});
	}
}

function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

function calcularTotal(){
	var total = 0;
	$("#detalle tr").each(function(){
		var cantidad = parseInt($(this).attr('cantidad'));
		var precio = parseFloat($(this).attr('precio'));
		total += precio*cantidad;
	});
	var fise = parseFloat($('#monto_vale_fise').val());
	var monto = parseFloat($('#monto_vale_balon').val());
	var subcafae = null;
	var checksubcafae = $('#vale_balon_subcafae').parent();
	if( checksubcafae.hasClass('checked')) { 
		var primero = true;
		$("#detalle tr").each(function(){
			var id = parseInt($(this).attr('id'));
			if( id == "5"  && primero == true){
				subcafae = 36;
				primero = false;
			}else if( id == "4"  && primero == true ){
				subcafae = 37;
				primero = false;
			}
		});
	}
	if(is_numeric(fise)){
		total -= fise;
	}
	if(is_numeric(monto)){
		total -= monto;
	}
	if(is_numeric(subcafae)){
		total -= subcafae;
	}
	/**/
	if(total == 0){
		$('#btnGuardar').prop('disabled', false);
	}else{
		if($("#credito").val() == 1){
			$('#btnGuardar').prop('disabled', false);
		}else{
			$('#btnGuardar').prop('disabled', true);
		}
	}
	$("#total").val(total.toFixed(2));
}

</script>


