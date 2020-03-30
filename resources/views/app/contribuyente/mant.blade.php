<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($contribuyente, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('ruc', 'RUC:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-4 col-md-4 col-sm-4">
		{!! Form::text('ruc', null, array('class' => 'form-control input-xs', 'id' => 'rucc', 'placeholder' => 'Ingrese RUC')) !!}
	</div>
	<div class="col-lg-4 col-md-4 col-sm-4">
		{!! Form::button('<i class="glyphicon glyphicon-search"></i> Consulta RUC', array('class' => 'btn btn-info btn-sm', 'id' => 'btnConsultaRuc', 'onclick' => 'consultaRUC()')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('contribuyente', 'Contribuyente:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('contribuyente', null, array('class' => 'form-control input-xs', 'id' => 'contribuyentec', 'placeholder' => 'Ingrese contribuyente')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('telefono', 'Teléfono:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-4 col-md-4 col-sm-4">
		{!! Form::text('telefono', null, array('class' => 'form-control input-xs', 'id' => 'telefono', 'placeholder' => 'Ingrese teléfono')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('direccion', 'Dirección:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('direccion', null, array('class' => 'form-control input-xs', 'id' => 'direccion', 'placeholder' => 'Ingrese dirección')) !!}
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
		$(IDFORMMANTENIMIENTO + '{{ $entidad }} :input[id="rucc"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				consultaRUC();
			}
		});
		configurarAnchoModal('600');
		var personas = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'person/employeesautocompleting/%QUERY',
				filter: function (personas) {
					return $.map(personas, function (movie) {
						return {
							value: movie.value,
							id: movie.id
						};
					});
				}
			}
		});
		personas.initialize();
		$('#nombrepersona').typeahead(null,{
			displayKey: 'value',
			source: personas.ttAdapter()
		}).on('typeahead:selected', function (object, datum) {
			$('#person_id').val(datum.id);
		});


	}); 

	function consultaRUC(){
		var ruc = $('#rucc').val();
		var url = 'consulta_sunat.php';
		$('#btnConsultaRuc').html("Consultando...");
		$.ajax({
			type:'POST',
			url:url,
			data:'ruc='+ruc,
			success: function(datos_dni){
				var datos = eval(datos_dni);
				var nada ='nada';
				if(datos[0]==nada){
					console.log('DNI o RUC no válido o no registrado');
				}else{
					$('#btnConsultaRuc').html("<i class='glyphicon glyphicon-search'></i> Consulta RUC");
					$('#rucc').val(datos[0]);
					$('#contribuyentec').val(datos[1]);
					$('#direccion').val(datos[7]);
				}		
			}
		});
		return false;
	}
</script>