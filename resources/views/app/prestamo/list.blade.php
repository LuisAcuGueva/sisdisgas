<?php
use App\Detallemovalmacen;
use App\Detalleprestamo;
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
			@if($value->concepto_id == 22)
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			@else
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			@endif

			@php
				$detalles = Detallemovalmacen::where('movimiento_id',$value->id)->get();
				$balones = false;
				$prestados = Detalleprestamo::join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
												->where('detalle_mov_almacen.movimiento_id',$value->id)
												->where('detalle_prestamo.tipo','P')
												->sum('detalle_prestamo.cantidad');
				$devueltos = Detalleprestamo::join('detalle_mov_almacen', 'detalle_mov_almacen.id', '=', 'detalle_prestamo.detalle_mov_almacen_id')
												->where('detalle_mov_almacen.movimiento_id',$value->id)
												->where('detalle_prestamo.tipo','D')
												->sum('detalle_prestamo.cantidad');
				foreach($detalles as $i => $det_mov){
					
					if($det_mov->producto_id == 4 || $det_mov->producto_id == 5){
						$balones = true;
					}
				}
			@endphp

			@if($value->estado == 1)
				@if($balones)
					@if($devueltos != $prestados)
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-warning glyphicon glyphicon-download-alt')) !!}</td>
					@else
						<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-dark glyphicon glyphicon-download-alt')) !!}</td>
					@endif
				@else
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'disabled' ,'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-download-alt')) !!}</td>
				@endif
			@elseif($value->estado == 0)
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["prestar"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'disabled' ,'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-download-alt')) !!}</td>
			@endif

			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$tituloAnulacion.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>

			<td>{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha )) }}</td>

			<td align="center">{{ $value->num_venta ? $value->tipodocumento->abreviatura . $value->num_venta : '-' }} </td>

			@if (!is_null($value->persona))
				<td>{{ $value->persona->razon_social ? $value->persona->razon_social : $value->persona->nombres.' '.$value->persona->apellido_pat.' '.$value->persona->apellido_mat }}</td>
			@else
				<td align="center"> - </td>
			@endif

			<td> {{  $value->persona->direccion }} </td>

			@if($value->pedido_sucursal == 1)
				<td style ="background-color: #fdf8c1b0 !important">SUCURSAL: {{  $value->sucursal->nombre }} </td>
			@elseif($value->concepto_id == 22)
				<td style ="background-color: #e4c1fdb0 !important"> DEVOLUCION PENDIENTE </td>
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
	

</script>

@endif