<?php use App\Metodopago; ?> 
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
			@foreach ($lista as $key => $value)
				<tr style ="background-color: {{ $value->pedido->estado == 1 ? '#ffffff' : '#ffc8cb' }} !important">
				@if(in_array($value->pedido->tipomovimiento_id, array(2, 5)))
					<td align="center">{!! Form::button('', array('onclick' => 'modalCaja(\''.URL::route($ruta["detalle"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
				@elseif(in_array($value->pedido->tipomovimiento_id, array(1, 6))) 
					<td align="center"> - </td>
				@endif	

				<td>{{ date("d/m/Y h:i:s a",strtotime($value->pedido->fecha )) }}</td>
				<td> {{ $value->pedido->concepto->concepto }} </td>
				
				@if($value->pedido->tipomovimiento_id == 2)
					<td> {{ $value->pedido->tipodocumento->abreviatura . '' . $value->pedido->num_venta }} </td>
				@elseif($value->pedido->tipomovimiento_id == 5)
					<td> {{ $value->pedido->venta->tipodocumento->abreviatura . '' . $value->pedido->venta->num_venta }} </td>
				@else
					<td align="center"> - </td>
				@endif

				@if (!is_null($value->pedido->persona))
					<td>{{ $value->pedido->persona->razon_social? $value->pedido->persona->razon_social : $value->pedido->persona->apellido_pat.' '.$value->pedido->persona->apellido_mat.' '.$value->pedido->persona->nombres  }}</td>
					<td>{{ $value->pedido->persona->id == 1 ? $value->pedido->comentario : $value->pedido->persona->direccion }}</td>
				@else
					<td align="center"> - </td>
					<td align="center"> - </td>
				@endif

				@if($value->pedido->vale_balon_subcafae == 1)
					<td align="center"> SUBCAFAE </td>
				@elseif($value->pedido->vale_balon_fise == 1)
					<td align="center"> FISE </td>
				@elseif($value->pedido->vale_balon_monto == 1)
					<td align="center"> MONTO </td>
				@else
					<td align="center"> - </td>
				@endif
			
				@if(in_array($value->pedido->tipomovimiento_id, array(2, 5)) || ($value->pedido->tipomovimiento_id == 1 && in_array($value->pedido->concepto_id, array(12, 15)) ) )
					<td align="center" style="color:green;font-weight: bold;"> {{ $value->pedido->balon_a_cuenta == 1 ? $value->pedido->total_pagado : $value->pedido->total }} </td>
				@else
					<td align="center" style="color:red;font-weight: bold;"> {{ $value->pedido->total }} </td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
	<div class="row" style="padding: 0px !important; margin: 0px !important;">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<table class="table-bordered table-striped table-condensed" align="center">
				<thead>
					<tr>
						<th class="text-center" colspan="2">RESUMEN DE TURNO DEL REPARTIDOR</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>MONTO VUELTOS :</th>
						<th class="text-right">{{ number_format( $vueltos_repartidor ,2) }}</th>
					</tr>
					<tr>
						<th>INGRESOS DE PEDIDOS :</th>
						<th class="text-right">{{ number_format( $ingresos_repartidor ,2) }}</th>
					</tr>
					<tr>
						<th>INGRESOS DE PEDIDOS A CRÉDITO:</th>
						<th class="text-right">{{ number_format( $ingresos_credito,2) }}</th>
					</tr>
					<tr>
						<th>TOTAL INGRESOS + MONTO VUELTO:</th>
						<th class="text-right">{{ number_format( $total_ingresos,2) }}</th>
					</tr>
					<tr>
						<th>GASTOS DEL REPARTIDOR:</th>
						<th class="text-right">{{ number_format( $gastos_repartidor,2) }}</th>
					</tr>
					<tr>
						<th>EGRESOS A CAJA:</th>
						<th class="text-right">{{ number_format( $egresos_repartidor,2) }}</th>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-6">
			<table class="table-bordered table-striped table-condensed" align="center">
				<thead>
					<tr>
						<th class="text-center" colspan="2">DETALLE INGRESOS DE PEDIDOS</th>
					</tr>
				</thead>
				<tbody>
					<?php $metodos_pago = Metodopago::all(); ?>
					@foreach($metodos_pago as $key => $metodo_pago)
					<tr>
						<th>INGRESOS {{$metodo_pago->nombre}}:</th>
						<th class="text-right">{{ number_format( $ingresos_metodos[$metodo_pago->id] ,2) }}</th>
					</tr>
					@endforeach
					<tr>
						<th>TOTAL INGRESOS:</th>
						<th class="text-right">{{ number_format(array_sum($ingresos_metodos),2) }}</th>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<table class="table-bordered table-striped table-condensed" align="center" style="margin-top: 15px;">
		<thead>
			<tr>
				<th class="text-center" colspan="2">SALDO DE REPARTIDOR</th>
			</tr>
		</thead>
		<tbody>
			<tr style ="background-color: #acffaab0;">
				<th>MONTO VUELTOS:</th>
				<th class="text-right">{{ number_format( $vueltos_repartidor ,2) }}</th>
			</tr>
			<tr style ="background-color: #acffaab0;">
				<th>INGRESOS EFECTIVO:</th>
				<th class="text-right">{{ number_format( $ingresos_metodos[1] ,2) }}</th>
			</tr>
			<tr style ="background-color: #acffaab0;">
				<th>INGRESOS DE PEDIDOS A CRÉDITO:</th>
				<th class="text-right">{{ number_format( $ingresos_credito ,2) }}</th>
			</tr>
			<tr style ="background-color: #ffc8cb;">
				<th>GASTOS DEL REPARTIDOR:</th>
				<th class="text-right">{{ number_format( $gastos_repartidor,2) }}</th>
			</tr>
			<tr style ="background-color: #ffc8cb;">
				<th>EGRESOS A CAJA:</th>
				<th class="text-right">{{ number_format( $egresos_repartidor,2) }}</th>
			</tr>
			<tr>
				<th>SALDO:</th>
				<th class="text-right">{{ number_format( $saldo_repartidor,2) }}</th>
			</tr>
		</tbody>
	</table>
</div>
@endif