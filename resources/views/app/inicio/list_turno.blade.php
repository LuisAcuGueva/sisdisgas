@if(!isset($trabajador))
<h3 class="text-warning">Seleccione un repartidor.</h3>
@else
<div class="row">
    <div class="col-12">
    {!! Form::open(['class' => 'form-inline']) !!}
    {!! Form::label('repartidor_sucursal_id', 'Sucursal:') !!}
    {!! Form::select('repartidor_sucursal_id', $cboSucursal, $trabajador->sucursal_id, array('class' => 'form-control input-sm', 'id' => 'repartidor_sucursal_id')) !!}
    {!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-md', 'id' => 'btnGuardarSucursal', 'onclick' => 'guardarSucursalRepartidor();')) !!}
    {!! Form::close() !!}
    </div>
</div>

<script>

function guardarSucursalRepartidor(){
	var cliente = null;
	var ajax = $.ajax({
		"method": "POST",
		"url": "{{ url('/inicio/guardarSucursalRepartidor') }}",
		"data": {
            "repartidor_sucursal_id" : $("#repartidor_sucursal_id").val(),
            "trabajador_id" : <?php echo $trabajador->id; ?>,
			"_token": "{{ csrf_token() }}",
        }
	}).done(function(info){
        console.log(info);
	}).always(function(){
		mostrarMensaje('Accion realizada correctamente', 'OK');
	});
}

</script>
@endif