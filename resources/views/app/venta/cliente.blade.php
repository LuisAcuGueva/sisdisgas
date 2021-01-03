<?php
$dni = null;
$ruc = null;
if(!is_null($cliente)){
	if(!is_null($cliente->dni)){
		$dni = $cliente->dni;
	}else if(!is_null($cliente->ruc)){
		$ruc = $cliente->ruc;
	}
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($cliente, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('dni', 'DNI / RUC:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	@if(is_null($cliente))
		<div class="col-lg-4 col-md-4 col-sm-4">
			{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI / RUC')) !!}
			{!! Form::hidden('cantc', null, array('id' => 'cantc')) !!}
		</div>
	@else
		@if(!is_null($cliente->dni))
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::text('dni', $dni, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI / RUC')) !!}
				{!! Form::hidden('cantc', '8' , array('id' => 'cantc')) !!}
			</div>
		@elseif(!is_null($cliente->ruc))
			<div class="col-lg-4 col-md-4 col-sm-4">	
				{!! Form::text('dni', $ruc, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI / RUC')) !!}
				{!! Form::hidden('cantc', '11' , array('id' => 'cantc')) !!}
			</div>
		@endif
	@endif
	{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'style' => 'display: none;','id' => 'btnConsultaDNI')) !!}
</div>
<div class="form-group dni">
	{!! Form::label('nombres', 'Nombre:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombres')) !!}
	</div>
</div>
<div class="form-group dni">
	{!! Form::label('apellido_pat', 'Ap. Paterno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		{!! Form::text('apellido_pat', null, array('class' => 'form-control input-xs', 'id' => 'apellido_pat', 'placeholder' => 'Ingrese apellido paterno')) !!}
	</div>
</div>
<div class="form-group dni">
	{!! Form::label('apellido_mat', 'Ap. Materno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		{!! Form::text('apellido_mat', null, array('class' => 'form-control input-xs', 'id' => 'apellido_mat', 'placeholder' => 'Ingrese apellido materno')) !!}
	</div>
</div>

<div class="form-group ruc">
	{!! Form::label('razon_social', 'Razón Social:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('razon_social', null, array('class' => 'form-control input-xs', 'id' => 'razon_social', 'placeholder' => 'Ingrese razón social')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('celular', 'Celular:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-6 col-md-6 col-sm-6">
		{!! Form::text('celular', null, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'celular', 'placeholder' => 'Ingrese número de celular')) !!}
	</div>
</div>

<div class="form-group">
	{!! Form::label('direccion', 'Dirección:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::textarea('direccion', null, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'direccion', 'placeholder' => 'Ingrese dirección')) !!}
	</div>
</div>
<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardarCliente')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		$(".ruc").css("display","none");
		var cantc = $("#dni").val();
		$('#btnConsultaDNI').prop('disabled',true);
		if(cantc.length == 8){
			$(".dni").css("display","");
			$(".ruc").css("display","none");
		}else if(cantc.length == 11){
			$(".ruc").css("display","");
			$(".dni").css("display","none");
		}
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="usertype_id"]').focus();
		configurarAnchoModal('650');

		$("#dni").keyup(function(){
			//hacer que aparezcan los inputs seguna la cantidad de largo del string 8 dni 11 ruc
			var cantc = $("#dni").val();
			mostrarinputs(cantc.length);
			$("#cantc").val(cantc.length);
		}); 

		function mostrarinputs(cantc){
			if(cantc == 8){
				$(".dni").css("display","");
				$(".ruc").css("display","none");
				$('#btnConsultaDNI').prop('disabled',false);
			}else if(cantc == 11){
				$(".ruc").css("display","");
				$(".dni").css("display","none");
				$('#btnConsultaDNI').prop('disabled',false);
			}else{
				$('#btnConsultaDNI').prop('disabled',true);
			}
		}

		$('#btnGuardarCliente').on('click', function(){

			guardarcliente();

			setTimeout(function(){
				var cliente = mostrarultimo();
			},1000);

		});

		$('#btnConsultaDNI').on('click', function(){
			var cantc = $("#dni").val();
			if(cantc.length == 8){
				var dni = $('#dni').val();
				var url = 'reniec/consulta_reniec.php';
				$.ajax({
					type:'POST',
					url:url,
					data:'dni='+dni,
					beforeSend(){
						alert("Consultando DNI...");
			        },
					success: function(datos_dni){
						var datos = eval(datos_dni);
						//$('#mostrar_dni').text(datos[0]);
						$('#nombres').val(datos[1]);
						$('#apellido_pat').val(datos[2]);
						$('#apellido_mat').val(datos[3]);
					}
				});
				return false;
			}else if(cantc.length == 11){
				var ruc = $("#dni").val();
			    $.ajax({
			        type: 'GET',
			        url: "SunatPHP/demo.php",
			        data: "ruc="+ruc,
			        beforeSend(){
						alert("Consultando RUC...");
			        },
			        success: function (data, textStatus, jqXHR) {
			            $("#razon_social").val(data.RazonSocial);
			        }
			    });
			}
		});

}); 


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
</script>

