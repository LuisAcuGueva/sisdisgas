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
		@if($value->pedido->estado == 1)
			<tr style ="background-color: #ffffff !important">
		@elseif($value->estado == 0)
			<tr style ="background-color: #ffc8cb !important">
		@endif
			@if($value->pedido->tipomovimiento_id == 2 || $value->pedido->tipomovimiento_id == 5)
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			@elseif($value->pedido->tipomovimiento_id == 1) 
				<td align="center"> - </td>
			@endif
			<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->pedido->fecha )) }}</td>

			<td> {{  $value->pedido->concepto->concepto }} </td>

			@if (!is_null($value->pedido->persona))
				@if(!is_null($value->pedido->persona->dni))
				<td>{{ $value->pedido->persona->apellido_pat.' '.$value->pedido->persona->apellido_mat.' '.$value->pedido->persona->nombres  }}</td>
				@else
				<td>{{ $value->pedido->persona->razon_social  }}</td>
				@endif
				<td>{{ $value->pedido->persona->direccion  }}</td>
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
			
			@if($value->pedido->estado == 1)
				@if (!is_null($value->pedido->comentario))
					<td> {{ $value->pedido->comentario }} </td>
				@else
					<td align="center"> - </td>
				@endif
			@elseif($value->estado == 0)
				<td> {{ $value->pedido->comentario }} | Anulado por: {{ $value->pedido->comentario_anulado }} </td>
			@endif
			
			
			@if($value->pedido->concepto->tipo != 0 || $value->pedido->concepto_id == 3 || $value->pedido->concepto_id == 16 )
				<td align="center" style="color:green;font-weight: bold;"> {{ $value->pedido->total }} </td>
			@else
				<td align="center" style="color:red;font-weight: bold;"> {{ $value->pedido->total }} </td>
			@endif

			
		</tr>
		<?php
		$contador = $contador + 1;
		?>
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
            <th>MONTO VUELTOS:</th>
            <th class="text-right"><div id ="montovuelto"> {{ $vueltos_repartidor }}</div></th>
        </tr>
        <tr>
            <th>INGRESOS DE PEDIDOS:</th>
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
            <th>EGRESOS A CAJA:</th>
            <th class="text-right"><div id ="egresos"> {{ $egresos_repartidor }} </div></th>
        </tr>
        <tr style="display:none;">
            <th>SALDO:</th>
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
	});

</script>

@endif