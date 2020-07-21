@if(count($lista) == 0)
<h3 class="text-warning">Seleccione repartidor en turno.</h3>
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
			@if($value->pedido->tipomovimiento_id == 2)
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
				@elseif($value->pedido->tipomovimiento_id == 1)
				<td align="center"> - </td>
			@endif	
			<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->pedido->fecha )) }}</td>
			@if($value->pedido->tipomovimiento_id == 2)
				<td> PEDIDO </td>
			@elseif($value->pedido->tipomovimiento_id == 1)
				<td> MONTO VUELTO </td>
			@endif

			@if (!is_null($value->pedido->persona))
				@if(!is_null($value->pedido->persona->dni))
				<td>{{ $value->pedido->persona->apellido_pat.' '.$value->pedido->persona->apellido_mat.' '.$value->pedido->persona->nombres  }}</td>
				@else
				<td>{{ $value->pedido->persona->razon_social  }}</td>
				@endif
			@else
				<td align="center"> - </td>
			@endif
			<td> {{ $value->pedido->sucursal->nombre }} </td>
			<td align="center"> {{ $value->pedido->total }} </td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
</div>

@endif