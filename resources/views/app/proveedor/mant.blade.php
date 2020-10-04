<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($proveedor, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('ruc','RUC:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-4 col-md-4 col-sm-4">
		{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'ruc', 'placeholder' => 'Ingrese RUC')) !!}
		{!! Form::hidden('cantc', null, array('id' => 'cantc')) !!}
	</div>
	{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-primary waves-effect waves-light btn-sm', 'id' => 'btnConsultaDNI')) !!}
</div>

<div class="form-group">
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
		configurarAnchoModal('650');

		var cantc = $("#ruc").val();
		$('#btnConsultaDNI').prop('disabled',true);

		
		$("#ruc").keyup(function(){
			//hacer que aparezcan los inputs seguna la cantidad de largo del string 8 dni 11 ruc
			var cantc = $("#ruc").val();
			mostrarinputs(cantc.length);
			$("#cantc").val(cantc.length);
		}); 

		function mostrarinputs(cantc){
			if(cantc == 11){
				$('#btnConsultaDNI').prop('disabled',false);
			}else{
				$('#btnConsultaDNI').prop('disabled',true);
			}
		}


		$('#btnConsultaDNI').on('click', function(){
			var ruc = $("#ruc").val();
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
		});

}); 

</script>

