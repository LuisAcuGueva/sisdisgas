<?php
$hoy = date("Y-m-d");
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
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
			<tr style ="background-color: {{ $value->estado == 1 ? '#ffffff' : '#ffc8cb' }} !important">
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["detalle"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloDetalle.'\', this);', 'class' => 'btn btn-sm btn-primary glyphicon glyphicon-eye-open')) !!}</td>
			@if($value->estado == 1 && date("Y-m-d",strtotime($value->fecha)) == $hoy )
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
			@else
				<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'listar'=>'SI')).'\', \''.$tituloEliminar.'\', this);', 'disabled', 'class' => 'btn btn-sm btn-secondary glyphicon glyphicon-remove')) !!}</td>
			@endif
			<td align="center">{{ date("d/m/Y h:i:s a",strtotime($value->fecha))}}</td>	
			<td align="center" style ="background-color: {{ $value->concepto_id == 11 ? '#acffaab0' : '#ffb6b6cc' }} !important">{{ $value->concepto->concepto }}</td>
			<td>{{ $value->tipodocumento->abreviatura . '' .$value->num_compra }}</td>
			@if($value->estado == 1)
				<td> {{ $value->comentario ? $value->comentario : '-' }} </td>
			@else
				<td> {{ $value->comentario }} | Anulado por: {{ $value->comentario_anulado }} </td>
			@endif
			<td align="center">{{ $value->total }}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif
<script>
	$(document).ready(function () {
		if($(".btnEditar").attr('activo')=== 'no'){
			$('.btnEditar').attr("disabled", true);
		}
		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}
	});
</script>
