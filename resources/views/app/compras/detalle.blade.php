<style>
	.section-title{
		border: solid 1px; 
		border-radius: 5px; 
		height: 35px; 
		margin-bottom: 10px; 
		text-align: center; 
		color: #ffffff; 
		border-color: #2a3f54; 
		background-color: #2a3f54;
	}
	.text-title{
		margin-top: 8px; 
		font-weight: 600;
	}
	#detalle_prod td, #detalle_pago td{
		vertical-align: middle;
		text-align: center; 
	}
	#cabecera th{
		font-size: 13px !important; 
		text-align: center;
	}
	.inputDetPedido{
		font-size: 18px; 
		text-align: right;
	}
</style>

<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="section-title">
				<h4 class="text-title">DATOS DE LA COMPRA</h4>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('sucursal', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('sucursal', $compra->sucursal->nombre, array('class' => 'form-control input-sm', 'id' => 'sucursal', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('tipodocumento', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('tipodocumento', $compra->tipodocumento->descripcion , array('class' => 'form-control input-sm', 'id' => 'tipodocumento', 'readOnly')) !!}		
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('serieventa', $compra->tipodocumento->abreviatura . $compra->num_compra , array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'", 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('fecha', 'Fecha y hora:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('fecha', date("d/m/Y h:i:s a",strtotime($compra->fecha )), array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				{!! Form::label('proveedor', 'Proveedor:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				@if(!is_null($compra->persona->dni))
				{!! Form::text('proveedor', $compra->persona->apellido_pat.' '.$compra->persona->apellido_mat.' '.$compra->persona->nombres, array('class' => 'form-control input-sm','rows' => '2', 'id' => 'proveedor', 'readOnly')) !!}
				@else
				{!! Form::text('proveedor', $compra->persona->razon_social , array('class' => 'form-control input-sm','rows' => '2', 'id' => 'proveedor', 'readOnly')) !!}
				@endif
				{!! Form::hidden('cliente_id',null,array('id'=>'cliente_id')) !!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4">
				{!! Form::label('celular', 'Celular:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('celular', $compra->persona->celular, array('class' => 'form-control input-xs','id' => 'celular', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('cliente_direccion', 'Dirección:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::textarea('cliente_direccion', $compra->persona->direccion, array('class' => 'form-control input-xs', 'rows' => '4','id' => 'cliente_direccion', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('comentario', 'Comentario:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::textarea('comentario', $compra->comentario, array('class' => 'form-control input-xs', 'rows' => '4','id' => 'comentario', 'readOnly')) !!}
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="section-title">
				<h4 class="text-title">PAGO</h4>
			</div>
			<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="margin: 0px; padding:0px !important;">
				<thead id="cabecera">
					<tr>
						<th width="60%">Fecha</th>
						<th width="40%">Monto</th>
					</tr>
				</thead>
				<tbody id="detalle_pago">
					@foreach($detallespago  as $key => $value)
						<tr>
							<td>{{ date('d/m/Y h:i:s a' , strtotime($value->pedido->fecha)) }} </td>
							<td align="center">{{ $value->monto }} </td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="col-lg-12 col-md-12 col-sm-12" style="padding: 10px 0px">
				<b>Compra a crédito:</b>
				@if($compra->balon_a_cuenta == 1)
					SI
					<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="margin: 0px; padding:0px !important;">
						<thead id="cabecera">
							<tr>
								<th width="60%">Fecha</th>
								<th width="40%">Monto</th>
							</tr>
						</thead>
						<tbody id="detalle_pago">
							@foreach($detallespago_credito  as $key => $value)
								<tr>
									<td>{{ date('d/m/Y h:i:s a' , strtotime($value->pedido->fecha)) }} </td>
									<td align="center">{{ $value->monto }} </td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					NO
				@endif
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
			<div class="section-title">
				<h4 class="text-title">LISTA DE PRODUCTOS</h4>
			</div>
			<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
				<thead id="cabecera">
					<tr>
						<th>Descripción</th>
						<th>Cantidad</th>
						<th>Precio</th>
						<th>Cantidad Envases</th>
						<th>Precio Envase</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody id="detalle">
					@foreach($detalles  as $key => $value)
						<tr>
							<td>{{ $value->producto->descripcion }} </td>
							<td align="center">{{ ($value->cantidad*100) % 100 != 0 ? $value->cantidad : round($value->cantidad) }} </td>
							<td align="center">{{ $value->precio }} </td>
							<td align="center">{{ $value->cantidad_envase ? $value->cantidad_envase : '-' }} </td>
							<td align="center">{{ $value->cantidad_envase ? $value->precio_envase : '-' }} </td>
							@if( $value->cantidad_envase == null )
								<td align="center">{{ number_format($value->cantidad * $value->precio, 2)}} </td>
							@else
								<td align="center">{{ number_format($value->cantidad * $value->precio, 2)}} + {{ number_format($value->cantidad_envase * $value->precio_envase, 2)}} = {{  number_format(($value->cantidad * $value->precio) + ($value->cantidad_envase * $value->precio_envase) ,2) }} </td>
							@endif
						</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr>
						<td align="center" colspan="5">TOTAL</td>
						<td align="center">{!! Form::text('total', $compra->total, array('class' => 'form-control input-xs inputDetPedido', 'id' => 'total', 'readOnly', 'style' => 'width: 100px;')) !!}</td>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('875');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
}); 
</script>