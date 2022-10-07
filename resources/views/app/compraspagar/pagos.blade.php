<style>
	#cabecera th{
		font-size: 18px;
	}
</style>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<?php $cont = 1; ?>
	<div class="col-lg-12 col-md-12 col-sm-12">
		<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
			<thead id="cabecera">
				<tr>
					<th>#</th>
					<th>Fecha</th>
					<th>Monto</th>
				</tr>
			</thead>
			<tbody id="detalle">
				@foreach($detalles  as $key => $value)
					<tr>
						<td>{{ $cont }} </td>
						<td>{{ date("d/m/Y h:i:s a",strtotime($value->pedido->fecha )) }}</td>
						<td>{{ $value->monto }} </td>
					</tr>
					<?php $cont++; ?>
				@endforeach
			</tbody>
		</table>
	</div>
	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
}); 
</script>