<?php
$hoy = date("Y-m-d");
use App\Detallepagos;
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
		@foreach ($lista as $key => $value)
		<?php
			$pagos = Detallepagos::where('pedido_id', $value->id)
			->leftjoin('movimiento', 'detalle_pagos.pedido_id', '=', 'movimiento.id')                       
			->where('movimiento.estado', 1)
			->get();
		?>
		<tr style ="background-color: {{ $value->estado == 1 ? '#ffffff' : '#ffc8cb' }} !important">
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			
			@if(date("Y-m-d",strtotime($value->fecha)) == $hoy && $value->estado == 1)
				@if( count( $pagos) >= 2)
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
				@else
					<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
				@endif
			@else
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
			@endif

			<td align="center">{{ date("d/m/Y h:i:s a",strtotime($value->fecha)) }}</td>	
			<td>{{ $value->persona->razon_social }}</td>
			<td>{{ $value->tipodocumento->abreviatura . '' .$value->num_compra }}</td>
			@if($value->estado == 1)
				<td> {{ $value->comentario ? $value->comentario : '-' }} </td>
			@elseif($value->estado == 0)
				<td> {{ $value->comentario }} | Anulado por: {{ $value->comentario_anulado }} </td>
			@endif
			<td align="center"> {{ $value->balon_a_cuenta == 1 ? 'SI' : 'NO' }}</td>
			<td align="center">{{ $value->total }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif