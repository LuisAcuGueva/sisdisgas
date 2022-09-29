<?php
use App\Detalleturnopedido;
use App\Sucursal;
?>
@if(count($lista) == 0)
<h3 class="text-warning">Seleccione repartidor.</h3>
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
			<tr>
				<?php
				$cierre_turno = Detalleturnopedido::where('turno_id', '=', $value->id)
												->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
												->join('concepto', 'movimiento.concepto_id', '=', 'concepto.id')
												->where(function($subquery)
													{
														$subquery->where('concepto.id','=', 14);
													})
												->first();
				$sucursal_cierre = Sucursal::find($cierre_turno->sucursal_id);
				?>
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalleturno"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.' - '.$sucursal_cierre->nombre.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
				<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->inicio )) }}</td>
				<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fin )) }}</td>
				<td> {{ $sucursal_cierre->nombre }} </td>
				<td> {{ number_format( $cierre_turno->total , 2) }} </td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif