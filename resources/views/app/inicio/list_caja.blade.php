<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Person;
use App\User;
use App\Concepto;
use App\Movimiento;
use App\Detalleturnopedido;
use App\Turnorepartidor;

$user = Auth::user();

$venta = "'venta'";
$container = "'container'";
?>

<div>

<input id="monto_apertura" name="monto_apertura" type="hidden" value="{{$montoapertura}}">
<input id="monto_vuelto" name="monto_vuelto" type="hidden" value="{{$monto_vuelto}}">
<input id="ingresos_efectivo" name="ingresos_efectivo" type="hidden" value="{{$ingresos_efectivo}}">
<input id="ingresos_visa" name="ingresos_visa" type="hidden" value="{{$ingresos_visa}}">
<input id="ingresos_master" name="ingresos_master" type="hidden" value="{{$ingresos_master}}">
<input id="ingresos_total" name="ingresos_total" type="hidden" value="{{$ingresos_total}}">
<input id="egresos" name="egresos" type="hidden" value="{{$egresos}}">
<input id="saldo" name="saldo" type="hidden" value="{{$saldo}}">
<input id="caja_efectivo" name="caja_efectivo" type="hidden" value="{{$monto_caja}}">

</div>


@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
<div class="table-responsive">
<table class="table-bordered table-striped table-condensed" align="center">
    <thead>
        <tr>
            <th class="text-center" colspan="2">RESUMEN DE CAJA</th>
        </tr>
    </thead>
    <tbody>
		<tr>
            <th>MONTO APERTURA :</th>
            <th class="text-right"><div id ="montoapertura"></div></th>
        </tr>
		<tr>
            <th>MONTO VUELTO :</th>
            <th class="text-right"><div id ="montovuelto"></div></th>
        </tr>
        <tr>
            <th>INGRESOS :</th>
            <th class="text-right"><div id ="ingresostotal"></div></th>
        </tr>
        <tr style="display: none;">
            <td>EFECTIVO :</td>
            <td align="right"><div id ="ingresosefectivo"></div></td>
        </tr>
        <tr style="display: none;">
            <td>VISA :</td>
            <td align="right"><div id ="ingresosvisa"></div></td>
        </tr>
		<tr style="display: none;">
            <td>MASTERCARD :</td>
            <td align="right"><div id ="ingresosmaster"></div></td>
        </tr>
        <tr>
            <th>EGRESOS :</th>
            <th class="text-right"><div id ="egreso"></div></th>
        </tr>
        <!--tr>
            <th>SALDO :</th>
            <th class="text-right"><div id ="saldoo"></div></th>
        </tr-->
		<tr>
            <th>CAJA :</th>
            <th class="text-right"><div id ="caja_efectivo2"></div></th>
        </tr>
    </tbody>
</table>
</div>

@endif

<script>
	var ingresos_total = {{$ingresos_total}};
	var ingresos_efectivo = {{$ingresos_efectivo}};
	var ingresos_visa = {{$ingresos_visa}};
	var ingresos_master = {{$ingresos_master}};
	var egresos = {{$egresos}};
	var saldo = {{$saldo}};
	var montoapertura = {{$montoapertura}};
	var monto_vuelto = {{$monto_vuelto}};
	var monto_caja = {{$monto_caja}}
	
	$(document).ready(function () {

		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}

		console.log("monto vuelto = " + monto_vuelto );

		$('#ingresostotal').html(ingresos_total.toFixed(2));
		$('#ingresosefectivo').html(ingresos_efectivo.toFixed(2));
		$('#ingresosvisa').html(ingresos_visa.toFixed(2));
		$('#ingresosmaster').html(ingresos_master.toFixed(2));
		$('#egreso').html(egresos.toFixed(2));
		$('#saldoo').html(saldo.toFixed(2));
		$('#montoapertura').html(montoapertura.toFixed(2));
		$('#montovuelto').html(monto_vuelto.toFixed(2));
		$('#caja_efectivo2').html(monto_caja.toFixed(2));
	});

</script>
