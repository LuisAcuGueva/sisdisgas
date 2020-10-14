<?php
	Use App\Tipodocumento;
?>
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
		@foreach ($lista as $key => $value)

		@if($value->estado == 1)
			<tr style ="background-color: #ffffff !important">
		@elseif($value->estado == 0)
			<tr style ="background-color: #ffc8cb !important">
		@endif

			<td align="center">{{ $fechaformato = date("d/m/Y h:m:s a",strtotime($value->fecha))}}</td>	

			@if($value->estado == 1)
				@if( $value->tipo == "I")
					<td align="center" style ="background-color: #acffaab0 !important">Ingreso</td>
				@else
					<td align="center" style ="background-color: #ffb6b6cc !important">Egreso</td>
				@endif
			@else
				@if( $value->tipo == "I")
					<td align="center">Ingreso</td>
				@else
					<td align="center">Egreso</td>
				@endif
			@endif

			<?php
			$tipodocumento_id = $value->tipodocumento_id;
			$tipodocumento = Tipodocumento::find($tipodocumento_id);
			?>
			@if( $value->tipo == "I")
				<td align="center"> {{ $tipodocumento->abreviatura }}-{{ $value->num_compra }}</td>
			@else
				<td align="center"> {{ $tipodocumento->abreviatura }}-{{ $value->num_venta }}</td> 
			@endif
			<td>{{ $value->descripcion }}</td>
			<td align="center">{{ $value->cantidad }}</td>
			@if( $value->tipo == "I")
				<td align="center">{{ $value->precio_compra }}</td>
			@else
				<td align="center">{{ $value->precio_venta }}</td>
			@endif
			<td align="center">{{ $value->stock_anterior }}</td>
			<td align="center">{{ $value->stock_actual }}</td>

		</tr>
		@endforeach
		</tbody>
	</table>
</div>

@endif