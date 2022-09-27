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
		@if($value->id != 1)
		<tr>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-warning glyphicon glyphicon-pencil')) !!}</td>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-danger glyphicon glyphicon-remove')) !!}</td>
			<td>{{ $value->dni ? $value->dni : $value->RUC }}</td>
			<td>{{ $value->razon_social ? $value->razon_social : $value->nombres .' '. $value->apellido_pat.' '.$value->apellido_mat }}</td>
			<td>{{ $value->direccion  }}</td>
			<td>{{ $value->celular  }}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endif
		@endforeach
	</tbody>
</table>
</div>

@endif