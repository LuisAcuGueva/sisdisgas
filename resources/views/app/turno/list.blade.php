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
				@if($value->pedido->tipomovimiento_id == 2 || $value->pedido->tipomovimiento_id == 5)
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
				@elseif($value->pedido->tipomovimiento_id == 1 || $value->pedido->tipomovimiento_id == 6) 
					<td align="center"> - </td>
				@endif	

				@if($value->pedido->tipomovimiento_id == 2 || $value->pedido->tipomovimiento_id == 5)
					@if($value->pedido->estado == 1)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloAnulacion.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
					@elseif($value->pedido->estado == 0)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloAnulacion.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>	
					@endif
				@elseif(($value->pedido->tipomovimiento_id == 1 || $value->pedido->tipomovimiento_id == 6) && ( $value->pedido->concepto_id == 12 || $value->pedido->concepto_id == 13 || $value->pedido->concepto_id == 5 || $value->pedido->concepto_id == 7 || $value->pedido->concepto_id == 8 || $value->pedido->concepto_id == 9  ) ) 
					@if($value->pedido->estado == 1)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloAnulacion.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
					@elseif($value->pedido->estado == 0)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->pedido->id, 'listar'=>'SI')).'\', \''.$tituloAnulacion.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>	
					@endif
				@else
					<td align="center"> - </td>
				@endif

				<td>{{ date("d/m/Y h:i:s a",strtotime($value->pedido->fecha )) }}</td>

				<td> {{ $value->pedido->concepto->concepto }} </td>
				
				@if($value->pedido->tipomovimiento_id == 2)
					@if($value->pedido->tipodocumento->abreviatura && $value->pedido->num_venta)
						<td> {{  $value->pedido->tipodocumento->abreviatura . '' . $value->pedido->num_venta }} </td>
					@else
						<td align="center"> - </td>
					@endif
				@elseif($value->pedido->tipomovimiento_id == 5)
					@if($value->pedido->venta->tipodocumento->abreviatura && $value->pedido->venta->num_venta)
						<td> {{  $value->pedido->venta->tipodocumento->abreviatura . '' . $value->pedido->venta->num_venta }} </td>
					@else
						<td align="center"> - </td>
					@endif
				@else
					<td align="center"> - </td>
				@endif

				@if (!is_null($value->pedido->persona))
					<td>{{ $value->pedido->persona->razon_social ? $value->pedido->persona->razon_social : $value->pedido->persona->apellido_pat.' '.$value->pedido->persona->apellido_mat.' '.$value->pedido->persona->nombres  }}</td>
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

				<td> {{ $value->pedido->sucursal->nombre }} </td>
			
				@if(($value->pedido->tipomovimiento_id == 1 && ($value->pedido->concepto_id == 12 || $value->pedido->concepto_id == 15) ) || $value->pedido->tipomovimiento_id == 2 || $value->pedido->tipomovimiento_id == 5 )
					<td align="center" style="color:green;font-weight: bold;"> {{ $value->pedido->balon_a_cuenta == 1 ? $value->pedido->total_pagado : $value->pedido->total }} </td>
				@else
					<td align="center" style="color:red;font-weight: bold;"> {{ $value->pedido->total }} </td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>

	<table class="table-bordered table-striped table-condensed" align="center">
		<thead>
			<tr>
				<th class="text-center" colspan="2">RESUMEN DE TURNO DEL REPARTIDOR</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>MONTO VUELTOS :</th>
				<th class="text-right"><div id ="montovuelto"> {{ $vueltos_repartidor }}</div></th>
			</tr>
			<tr>
				<th>INGRESOS DE PEDIDOS :</th>
				<th class="text-right"><div id ="ingresopedidos"> {{ number_format( $ingresos_repartidor ,2) }} </div></th>
			</tr>
			  
            <?php $metodos_pago = Metodopago::all(); ?>
			@foreach($metodos_pago as $key => $metodo_pago)
			<tr>
				<th>INGRESOS {{$metodo_pago->nombre}} :</th>
				<th class="text-right"><div id ="ingresopedidos"> {{ number_format( $ingresos_metodos[$metodo_pago->id] ,2) }} </div></th>
			</tr>
			@endforeach
			
			<tr>
				<th>INGRESOS DE PEDIDOS A CRÉDITO:</th>
				<th class="text-right"><div id ="ingresocredito"> {{ number_format( $ingresos_credito,2) }} </div></th>
			</tr>
			<tr>
				<th>TOTAL INGRESOS + MONTO VUELTO:</th>
				<th class="text-right"><div id ="total_ingresos"> {{ number_format( $total_ingresos,2) }} </div></th>
			</tr>
			<tr>
				<th>GASTOS DEL REPARTIDOR:</th>
				<th class="text-right"><div id ="gastos"> {{ number_format( $gastos_repartidor,2) }} </div></th>
			</tr>
			<tr>
				<th>EGRESOS A CAJA:</th>
				<th class="text-right"><div id ="egresos"> {{ number_format( $egresos_repartidor,2) }} </div></th>
			</tr>
			<tr>
				<th>SALDO :</th>
				<th class="text-right"><div id ="saldo"> {{ number_format( $saldo_repartidor,2) }} </div></th>
			</tr>
		</tbody>
	</table>
</div>

<script>
	var vueltos_repartidor = {{$vueltos_repartidor}};
	var ingresos_repartidor = {{$ingresos_repartidor}};
	var ingresos_credito = {{$ingresos_credito}};
	var total_ingresos = {{$total_ingresos}};
	var egresos_repartidor = {{$egresos_repartidor}};
	var gastos_repartidor = {{$gastos_repartidor}};
	var saldo_repartidor = {{$saldo_repartidor}};
	
	$(document).ready(function () {
		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}
		$('#montovuelto').html(vueltos_repartidor.toFixed(2));
		$('#ingresopedidos').html(ingresos_repartidor.toFixed(2));
		$('#ingresos_credito').html(ingresos_credito.toFixed(2));
		$('#egresos').html(egresos_repartidor.toFixed(2));
		$('#gastos').html(gastos_repartidor.toFixed(2));
		$('#saldo').html(saldo_repartidor.toFixed(2));
		$('#total_ingresos').html(total_ingresos.toFixed(2));
	});
</script>
@endif