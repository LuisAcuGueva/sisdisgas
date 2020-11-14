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
		<tr>

			<td>{{ $contador }}</td>
			<td>{{ $value->descripcion }}</td>
			<td align="center">{{ $value->precio_venta }}</td>
			<td align="center">{{ $value->precio_compra }}</td>
			<td align="center">{{ $value->cantidad }}</td>
			@if( $value->recargable == 1)
			<td align="center">{{ $value->envases_total }}</td>
			<td align="center">{{ $value->envases_llenos }}</td>
			<td align="center">{{ $value->envases_vacios }}</td>
			<td align="center">{{ $value->envases_prestados }}</td>
			@else
			<td align="center"> - </td>
			<td align="center"> - </td>
			<td align="center"> - </td>
			<td align="center"> - </td>
			@endif

		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
		</tbody>
	</table>
</div>

@endif