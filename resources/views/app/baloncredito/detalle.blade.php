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
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="section-title">
				<h4 class="text-title">DATOS DEL PEDIDO</h4>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('sucursal', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('sucursal', $pedido->sucursal->nombre, array('class' => 'form-control input-sm', 'id' => 'sucursal', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('fecha', 'Fecha y hora:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('fecha', date("d/m/Y h:i:s a",strtotime($pedido->fecha )), array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4" style="margin-top: 10px; padding-left: 18px;">
				<b>Venta en sucursal:</b>
				@if($pedido->pedido_sucursal == 1)
					SI
				@else
					NO
				@endif
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				@if($pedido->pedido_sucursal == 0)
					{!! Form::label('repartidor', 'Repartidor:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('repartidor', $pedido->trabajador->nombres . ' ' . $pedido->trabajador->apellido_pat . ' ' . $pedido->trabajador->apellido_mat , array('class' => 'form-control input-sm', 'id' => 'repartidor', 'readOnly')) !!}
				@endif
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::label('tipodocumento', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('tipodocumento', $pedido->tipodocumento->descripcion , array('class' => 'form-control input-sm', 'id' => 'tipodocumento', 'readOnly')) !!}		
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('serieventa', $pedido->tipodocumento->abreviatura . $pedido->num_venta , array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'", 'readOnly')) !!}
				</div>
			</div>
			<div class="col-lg-7 col-md-7 col-sm-7">
				{!! Form::label('cliente', 'Cliente:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				@if($pedido->persona->razon_social)
					{!! Form::text('cliente', $pedido->persona->razon_social , array('class' => 'form-control input-sm', 'id' => 'cliente', 'readOnly')) !!}
					@else
					{!! Form::text('cliente', $pedido->persona->nombres . ' ' . $pedido->persona->apellido_pat . ' ' . $pedido->persona->apellido_mat , array('class' => 'form-control input-sm', 'id' => 'cliente', 'readOnly')) !!}
				@endif
			</div>
			<div class="col-lg-5 col-md-5 col-sm-5">
				{!! Form::label('celular', 'Celular:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('celular', $pedido->persona->celular, array('class' => 'form-control input-xs', 'id' => 'celular', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('cliente_direccion', 'Dirección:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::textarea('cliente_direccion', $pedido->persona->direccion, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'cliente_direccion', 'readOnly')) !!}
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				{!! Form::label('comentario', 'Comentario:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::textarea('comentario', $pedido->comentario, array('class' => 'form-control input-xs', 'rows' => '3','id' => 'comentario', 'readOnly')) !!}
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="section-title">
				<h4 class="text-title">PAGO</h4>
			</div>
			<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="margin: 0px; padding:0px !important;">
				<thead id="cabecera">
					<tr>
						<th width="30%">Fecha</th>
						<th width="40%">Método de pago</th>
						<th width="15%">Tipo</th>
						<th width="15%">Monto</th>
					</tr>
				</thead>
				<tbody id="detalle_pago">
					@foreach($detallespago  as $key => $value)
						<tr>
							<td>{{ date('d/m/Y h:i:s a' , strtotime($value->pedido->fecha)) }} </td>
							<td align="center">{{ $value->metodo_pago->nombre }} </td>
							<td align="center">{{ $value->tipo == 'R' ? 'Repartidor' : 'Sucursal' }} </td>
							<td align="center">{{ $value->monto }} </td>
						</tr>
					@endforeach
				</tbody>
			</table>
			<div class="col-lg-12 col-md-12 col-sm-12" style="padding: 10px 0px">
				<b>Pedido a crédito:</b>
				@if($pedido->balon_a_cuenta == 1)
					SI
					<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="margin: 0px; padding:0px !important;">
						<thead id="cabecera">
							<tr>
								<th width="30%">Fecha</th>
								<th width="40%">Método de pago</th>
								<th width="15%">Tipo</th>
								<th width="15%">Monto</th>
							</tr>
						</thead>
						<tbody id="detalle_pago">
							@foreach($detallespago_credito  as $key => $value)
								<tr>
									<td>{{ date('d/m/Y h:i:s a' , strtotime($value->pedido->fecha)) }} </td>
									<td align="center">{{ $value->metodo_pago->nombre }} </td>
									<td align="center">{{ $value->tipo == 'R' ? 'Repartidor' : 'Sucursal' }} </td>
									<td align="center">{{ $value->monto }} </td>
								</tr>
							@endforeach
						</tbody>
					</table>
				@else
					NO
				@endif
			</div>
			<?php
			$vale = "";
			$monto_vale = 0;
			?>
			@if($pedido->vale_balon_subcafae == 1 || $pedido->vale_balon_fise == 1 || $pedido->vale_balon_monto == 1)
				<?php
					if($pedido->vale_balon_subcafae == 1){
						$vale = "SUBCAFAE";
						$codigo_vale = $pedido->codigo_vale_subcafae;
					}else if($pedido->vale_balon_fise == 1){
						$vale = "FISE";
						$codigo_vale = $pedido->codigo_vale_fise;
						$monto_vale = $pedido->monto_vale_fise;
					}else if($pedido->vale_balon_monto == 1){
						$vale = "MONTO";
						$codigo_vale = $pedido->codigo_vale_monto;
						$monto_vale = $pedido->monto_vale_balon;
					}
				?>
				<div class="col-lg-4 col-md-4 col-sm-4">
					{!! Form::label('vale', 'Vale:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('vale', $vale , array('class' => 'form-control input-sm', 'id' => 'vale', 'readOnly')) !!}
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					{!! Form::label('codigo_vale', 'Código:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('codigo_vale', $codigo_vale , array('class' => 'form-control input-sm', 'id' => 'codigo_vale', 'readOnly')) !!}
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					@if($pedido->vale_balon_fise == 1 || $pedido->vale_balon_monto == 1)
						{!! Form::label('monto_dto', 'Monto dto S/.:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('monto_dto', $monto_vale , array('class' => 'form-control input-sm inputDetPedido', 'id' => 'monto_dto', 'readOnly')) !!}
					@endif
				</div>
			@endif
			<div class="col-lg-12 col-md-12 col-sm-12" style="padding: 0px;">
				<div class="col-lg-4 col-md-4 col-sm-4">
					{!! Form::label('total_pagado', 'Total pagado:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('total_pagado', $total_pagado , array('class' => 'form-control input-sm inputDetPedido', 'id' => 'total_pagado', 'readOnly')) !!}
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4">
					{!! Form::label('vuelto', 'Vuelto:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
					{!! Form::text('vuelto', $pedido->vuelto , array('class' => 'form-control input-sm inputDetPedido', 'id' => 'vuelto', 'readOnly')) !!}
				</div>
				@if($pedido->balon_a_cuenta == 1)
					<div class="col-lg-4 col-md-4 col-sm-4">
						{!! Form::label('monto_deuda', 'Monto deuda:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
						{!! Form::text('monto_deuda', $pedido->total -  $total_pagado, array('class' => 'form-control input-sm inputDetPedido', 'id' => 'monto_deuda', 'readOnly')) !!}
					</div>
				@endif
			</div>
		</div>
		
		<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px;">
			<div class="section-title">
				<h4 class="text-title">LISTA DE PRODUCTOS</h4>
			</div>
			<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12" style="padding: 0px 0px !important;">
				<thead id="cabecera">
					<tr>
						<th>Descripción</th>
						<th>Cantidad</th>
						<th>Precio S/.</th>
						<th>Cantidad Envases</th>
						<th>Precio Envase</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody id="detalle_prod">
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
						<td align="center">{!! Form::text('total', $total_productos, array('class' => 'form-control input-xs inputDetPedido', 'id' => 'total', 'readOnly', 'style' => 'width: 100px;')) !!}</td>
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
	configurarAnchoModal('850');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
}); 
</script>