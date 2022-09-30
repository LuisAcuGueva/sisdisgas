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
					<td>{{ date("d/m/Y h:i:s a",strtotime($value->fecha )) }}</td>
					<td>{{ $value->tipodocumento->abreviatura . $value->num_venta }}</td>

					@if (!is_null($value->persona))
						<td>{{ $value->persona->razon_social ? $value->persona->razon_social : $value->persona->nombres.' '.$value->persona->apellido_pat.' '.$value->persona->apellido_mat }}</td>
					@else
						<td align="center"> - </td>
					@endif

					<td>{{ $value->persona->id == 1 ? $value->comentario : $value->persona->direccion }}</td>

					@if($value->pedido_sucursal == 1)
						<td style ="background-color: #fdf8c1b0 !important">SUCURSAL: {{  $value->sucursal->nombre }} </td>
					@else
						<td style ="background-color: #b5e7ffb0 !important">{{ $value->trabajador->nombres.' '.$value->trabajador->apellido_pat.' '.$value->trabajador->apellido_mat }}</td>
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

					<td align="center"> {{ $value->balon_a_cuenta == 1 ? 'SI' : 'NO' }} </td>
					<td align="center" style="color:green;font-weight: bold;"> {{ $value->total }} </td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
@endif