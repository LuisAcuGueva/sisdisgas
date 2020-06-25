<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($cliente, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('dni', 'DNI:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('dni', null, array('class' => 'form-control input-xs', 'id' => 'dni', 'placeholder' => 'Ingrese DNI / RUC')) !!}
	</div>
</div>
<div class="form-group dni">
	{!! Form::label('nombres', 'Nombre:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('nombres', null, array('class' => 'form-control input-xs', 'id' => 'nombres', 'placeholder' => 'Ingrese nombres')) !!}
	</div>
</div>
<div class="form-group dni">
	{!! Form::label('apellido_pat', 'Ap. Paterno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('apellido_pat', null, array('class' => 'form-control input-xs', 'id' => 'apellido_pat', 'placeholder' => 'Ingrese apellido paterno')) !!}
	</div>
</div>
<div class="form-group dni">
	{!! Form::label('apellido_mat', 'Ap. Materno:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
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
		configurarAnchoModal('400');
		var personas = new Bloodhound({
			datumTokenizer: function (d) {
				return Bloodhound.tokenizers.whitespace(d.value);
			},
			queryTokenizer: Bloodhound.tokenizers.whitespace,
			remote: {
				url: 'trabajador/trabajadorautocompleting/%QUERY',
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

		$("#dni").keyup(function(){
			//hacer que aparezcan los inputs seguna la cantidad de largo del string 8 dni 11 ruc
			var cant = $("#dni").val();
			mostrarinputs(cant.length);
		}); 

		function mostrarinputs(cant){
			if(cant == 8){
				console.log("DNI");
				$(".dni").css("display","");
				$(".ruc").css("display","none");

			}else if(cant == 11){
				console.log("RUC");
				$(".ruc").css("display","");
				$(".dni").css("display","none");
			}
		}

	}); 
</script>