@php 
$nombrepersona = NULL;
if (!is_null($usuario)) {
	$person_id = $usuario->person->id;
	$nombrepersona = $usuario->person->apellido_pat.' '.$usuario->person->apellido_mat.' '.$usuario->person->nombres;
}
@endphp
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($usuario, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
<div class="form-group">
	{!! Form::label('usertype_id', 'Tipo de usuario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::select('usertype_id', $cboTipousuario, null, array('class' => 'form-control input-xs', 'id' => 'usertype_id')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('nombrepersona', 'Persona:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	{!! Form::hidden('person_id', null, array('id' => 'person_id')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		@if(!is_null($usuario))
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@else
		{!! Form::text('nombrepersona', $nombrepersona, array('class' => 'form-control input-xs', 'id' => 'nombrepersona', 'placeholder' => 'Seleccione persona')) !!}
		@endif
	</div>
</div>

@if (!is_null($usuario))
<div class="form-group">
	{!! Form::label('estado', 'Estado:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8" style="margin-top: 5px;">
		@if($usuario->state == 'H')
			<input name="estado" type="checkbox" id="estado" checked>
		@else
			<input name="estado" type="checkbox" id="estado">
		@endif
	</div>
</div>
@else
<div class="form-group">
	{!! Form::label('estado', 'Estado:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8" style="margin-top: 5px;">
		<input name="estado" type="checkbox" id="estado">
	</div>
</div>
@endif

<div class="form-group">
	{!! Form::label('loginm', 'Usuario:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::text('loginm', null, array('class' => 'form-control input-xs', 'id' => 'loginm', 'placeholder' => 'Ingrese login')) !!}
	</div>
</div>
<div class="form-group">
	{!! Form::label('password', 'Contraseña:', array('class' => 'col-lg-4 col-md-4 col-sm-4 control-label')) !!}
	<div class="col-lg-8 col-md-8 col-sm-8">
		{!! Form::password('password', array('class' => 'form-control input-xs', 'id' => 'password', 'placeholder' => 'Ingrese contraseña')) !!}
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
							id: movie.id,
							registro: movie.registro
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
			$('#loginm').val(datum.registro);
		});
	}); 

	$(document).ready(function(){
		$('input').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});
	});
</script>