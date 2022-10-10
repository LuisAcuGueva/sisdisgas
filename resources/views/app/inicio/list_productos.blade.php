<?php
use App\Detallemovalmacen;
use App\Movimiento;
?>
@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
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
			
			<td>{{ $value->descripcion }}</td>

			@php

				$cantidad = Movimiento::join('detalle_mov_almacen', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
					->join('producto', 'detalle_mov_almacen.producto_id', '=', 'producto.id')
					->where(function($subquery) use( $aperturaycierre, $maxima_apertura_id, $maximo_cierre_id)
		            {
						if (!is_null($maxima_apertura_id) && !is_null($maximo_cierre_id)) {
							if($aperturaycierre == 0){ //apertura y cierre iguales ---- no mostrar nada
								$subquery->Where('movimiento.id','>=', $maxima_apertura_id)->Where('movimiento.id','<=', $maximo_cierre_id);
							}else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura
								$subquery->Where('movimiento.id','>=', $maxima_apertura_id);
							}
						}else if(!is_null($maxima_apertura_id) && is_null($maximo_cierre_id)) {
							$subquery->Where('movimiento.id','>=', $maxima_apertura_id);
						}
					})
					->where('movimiento.sucursal_id', "=", $sucursal_id)
					->where('detalle_mov_almacen.producto_id', "=", $value->id)
					->where('movimiento.tipomovimiento_id', "=" , 2)
					->sum('detalle_mov_almacen.cantidad');

				$cantidad_envase = Movimiento::join('detalle_mov_almacen', 'detalle_mov_almacen.movimiento_id', '=', 'movimiento.id')
				->join('producto', 'detalle_mov_almacen.producto_id', '=', 'producto.id')
				->where(function($subquery) use( $aperturaycierre, $maxima_apertura_id, $maximo_cierre_id)
				{
					if (!is_null($maxima_apertura_id) && !is_null($maximo_cierre_id)) {
						if($aperturaycierre == 0){ //apertura y cierre iguales ---- no mostrar nada
							$subquery->Where('movimiento.id','>=', $maxima_apertura_id)->Where('movimiento.id','<=', $maximo_cierre_id);
						}else if($aperturaycierre == 1){ //apertura y cierre diferentes ------- mostrar desde apertura
							$subquery->Where('movimiento.id','>=', $maxima_apertura_id);
						}
					}else if(!is_null($maxima_apertura_id) && is_null($maximo_cierre_id)) {
						$subquery->Where('movimiento.id','>=', $maxima_apertura_id);
					}
				})
				->where('movimiento.sucursal_id', "=", $sucursal_id)
				->where('detalle_mov_almacen.producto_id', "=", $value->id)
				->where('movimiento.tipomovimiento_id', "=" , 2)
				->sum('detalle_mov_almacen.cantidad_envase');


			@endphp

			<td align="center">{{ $cantidad + $cantidad_envase }}</td>
			@if($value->recargable == 1)
				<td align="center">{{ $cantidad }}</td>
				<td align="center">{{ $cantidad_envase }}</td>
			@else
				<td align="center">-</td>
				<td align="center">-</td>
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
	/*var vueltos_repartidor = {{$vueltos_repartidor}};
	var ingresos_repartidor = {{$ingresos_repartidor}};
	var ingresos_credito = {{$ingresos_credito}};
	var total_ingresos = {{$total_ingresos}};
	var egresos_repartidor = {{$egresos_repartidor}};
	var saldo_repartidor = {{$saldo_repartidor}};
	
	$(document).ready(function () {

		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}

		$('#montovuelto').html(vueltos_repartidor.toFixed(2));
		$('#ingresopedidos').html(ingresos_repartidor.toFixed(2));
		$('#ingresos_credito').html(ingresos_credito.toFixed(2));
		$('#egresos').html(egresos_repartidor.toFixed(2));
		$('#saldo').html(saldo_repartidor.toFixed(2));
		$('#total_ingresos').html(total_ingresos.toFixed(2));
	});*/

</script>

@endif