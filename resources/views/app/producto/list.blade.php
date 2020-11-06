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
		<tr>

			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-warning glyphicon glyphicon-pencil')) !!}</td>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>

			<td>{{ $value->descripcion }}</td>
			<td align="center">{{ $value->precio_compra }}</td>
			<td align="center">{{ $value->precio_venta }}</td>

			@if($value->frecuente == 0)
			<td align="center">&#10008;</td>
			@elseif($value->frecuente == 1)
			<td align="center">&#10004;</td>
			@endif

			@if($value->editable == 0)
			<td align="center">&#10008;</td>
			@elseif($value->editable == 1)
			<td align="center">&#10004;</td>
			@endif

			@if($value->recargable == 0)
			<td align="center">&#10008;</td>
			@elseif($value->recargable == 1)
			<td align="center">&#10004;</td>
			@endif

		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
		</tbody>
	</table>
</div>

@endif