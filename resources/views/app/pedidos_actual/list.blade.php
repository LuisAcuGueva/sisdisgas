<?php
use App\Detallemovalmacen;
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

			@php
				$detalles = Detallemovalmacen::where('movimiento_id',$value->id)->get();
				$balones = false;
					foreach($detalles as $i => $det_mov){
						if($det_mov->producto_id == 4 || $det_mov->producto_id == 5){
							$balones = true;
						}
					}
			@endphp

			@if($value->estado == 1)
				@if($balones)
					@if($value->balon_prestado == 1)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-dark glyphicon glyphicon-download-alt')) !!}</td>
					@else
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-warning glyphicon glyphicon-download-alt')) !!}</td>
					@endif
				@else
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'disabled' ,'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-download-alt')) !!}</td>
				@endif
			@elseif($value->estado == 0)
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'disabled' ,'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-download-alt')) !!}</td>
			@endif
			<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha )) }}</td>

			<td> {{ $value->tipodocumento->abreviatura . $value->num_venta  }} </td>

			@if (!is_null($value->persona))
				@if(!is_null($value->persona->dni))
				<td>{{ $value->persona->apellido_pat.' '.$value->persona->apellido_mat.' '.$value->persona->nombres  }}</td>
				@else
				<td>{{ $value->persona->razon_social  }}</td>
				@endif
			@else
				<td align="center"> - </td>
			@endif

			<td> {{  $value->persona->direccion }} </td>

			@if($value->pedido_sucursal == 1)
				<td style ="background-color: #fdf8c1b0 !important">SUCURSAL: {{  $value->sucursal->nombre }} </td>
			@else
				<td style ="background-color: #b5e7ffb0 !important">{{ $value->trabajador->apellido_pat.' '.$value->trabajador->apellido_mat.' '.$value->trabajador->nombres  }}</td>
			@endif

			@if($value->vale_balon_subcafae == 1)
				<td align="center"> SUBCAFAE </td>
			@elseif($value->vale_balon_fise == 1)
				<td align="center"> FISE </td>
			@elseif($value->vale_balon_monto == 1)
				<td align="center"> MONTO </td>
			@else
				<td align="center"> - </td>
			@endif

			@if($value->balon_a_cuenta == 1)
				<td align="center"> SI </td>
			@else
				<td align="center"> NO </td>
			@endif
			
			@if($value->concepto->tipo != 0 || $value->concepto_id == 3 || $value->concepto_id == 16 )
				<td align="center" style="color:green;font-weight: bold;"> {{ $value->total }} </td>
			@else
				<td align="center" style="color:red;font-weight: bold;"> {{ $value->total }} </td>
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