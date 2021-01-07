<?php
use App\Detallepagos;
$totaldebe = 0;
$totalpago = 0;
$pedidos_deuda = 0;
?>

@if(count($lista) == 0)
<h3 class="text-warning">No hay pedidos a crédito.</h3>
@else
	@foreach ($lista as $key => $value)
		<?php
			$total_pagos = Detallepagos::where('pedido_id', '=', $value->id)
										->join('movimiento', 'detalle_pagos.pago_id', '=', 'movimiento.id')
										->where('estado',1)
										->sum('monto');
			if($total_pagos != $value->total){
				$totalpago += $total_pagos;
				$saldo = $value->total - $total_pagos;
				$totaldebe += $saldo;
				$pedidos_deuda++;
			}
		?>

	@endforeach
<h3 class="text-warning">Total de pedidos a crédito: {{ $pedidos_deuda }} </h3><br>
<h3 class="text-warning">Monto pagado: {{ $totalpago }} </h3><br>
<h3 class="text-warning">Monto deuda: {{ $totaldebe }} </h3>
@endif