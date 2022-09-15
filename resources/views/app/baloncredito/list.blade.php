<?php
use App\Detallepagos;
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
			@if(!is_null($value->persona->dni))
			<td>{{ $value->persona->apellido_pat.' '.$value->persona->apellido_mat.' '.$value->persona->nombres  }}</td>
			@else
			<td>{{ $value->persona->razon_social  }}</td>
			@endif

			@if($value->persona->id == 1)
			<td>{{ $value->comentario  }}</td>
			@else
			<td> {{  $value->persona->direccion }} </td>
			@endif
			
			<td> {{ $value->sucursal->nombre }} </td>
			<td align="center" style="color:black; font-weight: bold;">  {{ number_format($value->total,2) }} </td>
			<?php
				$total_pagos = Detallepagos::where('pedido_id', '=', $value->id)
											->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
											->where('estado',1)
											->sum('monto');
				round($total_pagos,2);
				$saldo = $value->total - $total_pagos;
				round($saldo,2);
			?>
			<td align="center" style="color:red; font-weight: bold;">  {{ number_format($saldo,2) }} </td>
			<td align="center" style="color:green; font-weight: bold;">  {{ number_format($total_pagos,2) }} </td>
			@if($total_pagos == 0)
			<td align="center">{!! Form::button('<i class="glyphicon glyphicon-th-list"></i> Detalle de pagos', array('onclick' => 'modal (\''.URL::route($ruta["pagos"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagos.'\', this);', 'class' => 'btn btn-sm btn-secondary ', 'disabled')) !!}</td>
			@else
				@if($value->estado == 1)
				<td align="center">{!! Form::button('<i class="glyphicon glyphicon-th-list"></i> Detalle de pagos', array('onclick' => 'modal (\''.URL::route($ruta["pagos"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagos.'\', this);', 'class' => 'btn btn-sm btn-warning ')) !!}</td>
				@elseif($value->estado == 0)
				<td align="center">{!! Form::button('<i class="glyphicon glyphicon-th-list"></i> Detalle de pagos', array('onclick' => 'modal (\''.URL::route($ruta["pagos"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagos.'\', this);', 'class' => 'btn btn-sm btn-secondary ', 'disabled')) !!}</td>
				@endif
			@endif
			@if($saldo == 0)
			<td align="center">{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Pagar', array('onclick' => 'modal (\''.URL::route($ruta["pagar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagar.'\', this);', 'class' => 'btn btn-sm btn-secondary' , 'disabled')) !!}</td>
			@else
				@if($value->estado == 1)
				<td align="center">{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Pagar', array('onclick' => 'modal (\''.URL::route($ruta["pagar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagar.'\', this);', 'class' => 'btn btn-sm btn-success')) !!}</td>
				@elseif($value->estado == 0)
				<td align="center">{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Pagar', array('onclick' => 'modal (\''.URL::route($ruta["pagar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloPagar.'\', this);', 'class' => 'btn btn-sm btn-secondary' , 'disabled')) !!}</td>
				@endif
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