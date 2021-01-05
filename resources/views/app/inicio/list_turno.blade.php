@if(count($lista) == 0)
<h3 class="text-warning">Seleccione repartidor en turno.</h3>
@else
<div class="table-responsive">

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
            <th class="text-right"><div id ="ingresopedidos"> {{ $ingresos_repartidor }} </div></th>
        </tr>
		<tr>
            <th>INGRESOS DE PEDIDOS A CRÉDITO:</th>
            <th class="text-right"><div id ="ingresocredito"> {{ $ingresos_credito }} </div></th>
        </tr>
		<tr>
            <th>TOTAL INGRESOS:</th>
            <th class="text-right"><div id ="total_ingresos"> {{ $total_ingresos }} </div></th>
        </tr>
        <tr>
            <th>GASTOS DEL REPARTIDOR:</th>
            <th class="text-right"><div id ="gastos"> {{ $gastos_repartidor }} </div></th>
        </tr>
        <tr>
            <th>EGRESOS A CAJA:</th>
            <th class="text-right"><div id ="egresos"> {{ $egresos_repartidor }} </div></th>
        </tr>
        <tr>
            <th>SALDO :</th>
            <th class="text-right"><div id ="saldo"> {{ $saldo_repartidor }} </div></th>
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