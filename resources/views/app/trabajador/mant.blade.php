<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($trabajador, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('dni', 'DNI:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-5 col-md-5 col-sm-5">
		{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI')) !!}
	</div>
	{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'id' => 'btnConsultaDNI')) !!}
</div>
<div class="form-group">
	{!! Form::label('nombres', 'Nombre:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombres')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('apellido_pat', 'Ap. Paterno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('apellido_pat', null, array('class' => 'form-control input-xs', 'id' => 'apellido_pat', 'placeholder' => 'Ingrese apellido paterno')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('apellido_mat', 'Ap. Materno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('apellido_mat', null, array('class' => 'form-control input-xs', 'id' => 'apellido_mat', 'placeholder' => 'Ingrese apellido materno')) !!}
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
		{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		&nbsp;
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		$(IDFORMMANTENIMIENTO + '{!! $entidad !!} :input[id="usertype_id"]').focus();
		configurarAnchoModal('600');

		var cantc = $("#dni").val();
		$('#btnConsultaDNI').prop('disabled',true);

		
		$("#dni").keyup(function(){
			//hacer que aparezcan los inputs seguna la cantidad de largo del string 8 dni 11 ruc
			var cantc = $("#dni").val();
			mostrarinputs(cantc.length);
			$("#cantc").val(cantc.length);
		}); 

		function mostrarinputs(cantc){
			if(cantc == 8){
				$('#btnConsultaDNI').prop('disabled',false);
			}else{
				$('#btnConsultaDNI').prop('disabled',true);
			}
		}

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
</script>