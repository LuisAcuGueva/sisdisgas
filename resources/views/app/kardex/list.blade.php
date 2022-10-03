<?php
	Use App\Tipodocumento;
	Use App\Tipomovimiento;
	Use App\Movimiento;
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

			<td align="center">{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha))}}</td>	

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
			$tipomovimiento = Tipomovimiento::find($value->tipomovimiento_id);
			$mov_anul_id = '';
			$tipomov_anul = '';
			if($value->tipomovimiento_id == 7){
				$mov_anul = Movimiento::find($value->venta_id ? $value->venta_id : $value->compra_id);
				$tipomov_anul = Tipomovimiento::find($mov_anul->tipomovimiento_id);
				$tipomov_anul = $tipomov_anul->descripcion;
			}
			$tipomovimiento = $tipomov_anul ? $tipomovimiento->descripcion . ' '. $tipomov_anul : $tipomovimiento->descripcion;
			?>
			<td align="center">{{ $tipomovimiento }}</td>
			<?php
			$tipodocumento = Tipodocumento::find($value->tipodocumento_id);
			if($value->tipomovimiento_id == 7){
				$tipodoc_anul = Tipodocumento::find($mov_anul->tipodocumento_id);
				$value->venta_id
					? $tipodoc_anul = $tipodoc_anul->abreviatura.'-'.$mov_anul->num_venta
					: $tipodoc_anul = $tipodoc_anul->abreviatura.'-'.$mov_anul->num_compra;
			}
			?>
			@if( $value->tipo == "I")
				@if($value->tipomovimiento_id == 7)
					<td align="center"> {{ $tipodoc_anul }} </td> 
				@else 
					<td align="center"> {{ $tipodocumento->abreviatura }}-{{ $value->num_compra }}</td>
				@endif
			@else
				@if($value->tipomovimiento_id == 4)
					<td align="center"> {{ $tipodocumento->abreviatura }}-{{ $value->num_compra }}</td> 
				@elseif($value->tipomovimiento_id == 2)
					<td align="center"> {{ $tipodocumento->abreviatura }}-{{ $value->num_venta }}</td> 
				@else 
					<td align="center"> - </td> 
				@endif
			@endif
			<td>{{ $value->descripcion }}</td>

			@if($value->cantidad != 0)
				<td align="center">{{ $value->cantidad }}</td>
				@if( $value->tipo == "I")
					<td align="center">{{ $value->tipomovimiento_id == 7 ? $value->precio_venta : $value->precio_compra }}</td>
				@else
					<td align="center">{{ $value->tipomovimiento_id == 7 ? $value->precio_compra : $value->precio_venta }}</td>
				@endif
			@else
				<td align="center"> - </td>
				<td align="center"> - </td>
			@endif

			@if($value->cantidad_envase != 0)
				<td align="center">{{ $value->cantidad_envase }}</td>
				@if( $value->tipo == "I")
					<td align="center">{{ $value->tipomovimiento_id == 7 ? $value->precio_venta_envase : $value->precio_compra_envase }}</td>
				@else
					<td align="center">{{ $value->tipomovimiento_id == 7 ? $value->precio_compra_envase : $value->precio_venta_envase }}</td>
				@endif
			@else
				<td align="center"> - </td>
				<td align="center"> - </td>
			@endif

			<td align="center">{{ $value->stock_anterior }}</td>

			<td align="center">{{ $value->stock_actual }}</td>

		</tr>
		@endforeach
		</tbody>
	</table>
</div>

@endif