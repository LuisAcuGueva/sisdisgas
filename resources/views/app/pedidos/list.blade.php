@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<div class="table-responsive">
<table id="example1" class="table table-bordered table-hover" style="font-size: 13px;">
	<thead>
		<tr class="success" style="height: 35px;">
			@foreach($cabecera as $key => $value)
				<th @if((int)$value["numero"] > 1) colspan="{{ $value['numero'] }}" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		@if($value->estado == 1)
			<tr style ="background-color: #ffffff !important">
		@elseif($value->estado == 0)
			<tr style ="background-color: #ffc8cb !important">
		@endif
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>

			<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha )) }}</td>

			<td> {{ $value->tipodocumento->abreviatura . $value->num_venta  }} </td>

			@if (!is_null($value->persona))
				@if(!is_null($value->persona->dni))
				<td>{{ $value->persona->apellido_pat.' '.$value->persona->apellido_mat.' '.$value->persona->nombres  }}</td>
				@else
				<td>{{ $value->persona->razon_social  }}</td>
				@endif
			@else
				<td align="center"> - </td>
			@endif

			@if($value->persona->id == 1)
			<td>{{ $value->comentario  }}</td>
			@else
			<td> {{  $value->persona->direccion }} </td>
			@endif

			@if($value->pedido_sucursal == 1)
				<td style ="background-color: #fdf8c1b0 !important">SUCURSAL: {{  $value->sucursal->nombre }} </td>
			@else
				<td style ="background-color: #b5e7ffb0 !important">{{ $value->trabajador->apellido_pat.' '.$value->trabajador->apellido_mat.' '.$value->trabajador->nombres  }}</td>
			@endif

			@if($value->vale_balon_subcafae == 1)
				<td align="center"> SUBCAFAE </td>
			@elseif($value->vale_balon_fise == 1)
				<td align="center"> FISE </td>
			@elseif($value->vale_balon_monto == 1)
				<td align="center"> MONTO </td>
			@else
				<td align="center"> - </td>
			@endif

			@if($value->balon_a_cuenta == 1)
				<td align="center"> SI </td>
			@else
				<td align="center"> NO </td>
			@endif
			
			@if($value->concepto->tipo != 0 || $value->concepto_id == 3 || $value->concepto_id == 16 )
				<td align="center" style="color:green;font-weight: bold;"> {{ $value->total }} </td>
			@else
				<td align="center" style="color:red;font-weight: bold;"> {{ $value->total }} </td>
			@endif

			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>


</div>


<script>
	

</script>

@endif