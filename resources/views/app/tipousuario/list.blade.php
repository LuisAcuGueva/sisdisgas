@if(count($lista) == 0)
<h3 class="text-warning">No se encontraron resultados.</h3>
@else
{!! $paginacion or '' !!}
<div class="table-responsive">
<table id="example1" class="table table-bordered table-hover" style="font-size: 13px;">
	<thead>
		<tr class="success" style="height: 35px;">
			@foreach($cabecera as $key => $value)
				<th @if((int)$value['numero'] > 1) colspan="{{ $value['numero'] }}" style="text-align: center;" @endif>{!! $value['valor'] !!}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		<?php
		$contador = $inicio + 1;
		?>
		@foreach ($lista as $key => $value)
		<tr>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["edit"], array($value->id, 'listar'=>'SI')).'\', \''.$titulo_modificar.'\', this);', 'class' => 'btn btn-sm btn-dark glyphicon glyphicon-pencil')) !!}</td>
			<td align="center">{!! Form::button('', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-dark glyphicon glyphicon-remove')) !!}</td>
			<td>{{ $value->name }}</td>
			<td align="center">{!! Form::button('<div class="glyphicon glyphicon-eye-open"></div> Acceso', array('onclick' => 'modal(\''.URL::route($ruta["permisos"], array('SI', $value->id)).'\', \'Permisos: '.$value->name.'\', this);', 'class' => 'btn btn-default btn-xs')) !!}</td>
			<td align="center">{!! Form::button('<div class="glyphicon glyphicon-list"></div> Operaciones', array('onclick' => 'modal(\''.URL::route($ruta["operaciones"], array('SI', $value->id)).'\', \'Operaciones: '.$value->name.'\', this);', 'class' => 'btn btn-default btn-xs')) !!}</td>
		</tr>
		<?php
		$contador = $contador + 1;
		?>
		@endforeach
	</tbody>
</table>
</div>
@endif