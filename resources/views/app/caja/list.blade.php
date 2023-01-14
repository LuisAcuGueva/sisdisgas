<?php
use App\Person;
use App\Concepto;
use App\Movimiento;
use App\Detalleturnopedido;
use App\Turnorepartidor;
?>
<style>
	.secondary {
		background: #800080;
		color: white;
	}
</style>
<div>
@if($aperturaycierre == 0)
	@if($sucursal_id != 1 && $caja_principal == 0) 
		{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
	@elseif($sucursal_id != 1 && $caja_principal == 1)
		@if(!is_null($ultimo_cierre))
			@if($ultimo_cierre->ingreso_caja_principal == 1 )
				{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
			@else
				{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
			@endif
		@else
			{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
		@endif
	@else
		{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
	@endif

	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Iniciar Turno de Repartidor', array('class' => 'btn btn-primary waves-effect waves-light m-l-10 btn-sm btnTurno', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["turnoRepartidor"], array('listar'=>'SI')).'\', \''.$tituloTurnoRepartidor.'\', this);')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm btnNuevo', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Registrar Pedido', array('class' => 'btn btn-dark waves-effect waves-light m-l-10 btn-sm btnPedido', 'disabled', 'onclick' => 'cargarRutaMenu("venta", "container", 16)')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-sm btnCierre', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}

	@if($sucursal_id == 1)
		{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> Ingresar cierres de caja de otras sucursales', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnIngresarCierres', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["ingresarcierres"], array('listar'=>'SI')).'\', \''.$tituloIngresarCierres.'\', this);')) !!}
	@endif
	@if($maxapertura == null)
		{!! Form::button('<i class="glyphicon glyphicon-print"></i> Reporte Caja Actual', array('class' => 'btn btn-warning waves-effect waves-light m-l-10 btn-sm btnReporte', 'disabled', 'onclick' => 'imprimirDetalle();')) !!}
	@else
		{!! Form::button('<i class="glyphicon glyphicon-print"></i> Reporte Caja Actual', array('class' => 'btn btn-warning waves-effect waves-light m-l-10 btn-sm btnReporte', 'onclick' => 'imprimirDetalle();')) !!}
	@endif
	<!-- GERSON (13-01-23) -->
	{!! Form::button('<i class="fa fa-exchange"></i> Transferencia entre Sucursal', array('class' => 'btn secondary waves-effect waves-light m-l-10 btn-sm btnCierre', 'disabled', 'onclick' => 'modalCaja (\''.URL::route($ruta["transferenciaSucursal"], array('listar'=>'SI')).'\', \''.$tituloTransferencia.'\', this);')) !!}
	<!--  -->
@else
	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Apertura', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnApertura', 'disabled' , 'onclick' => 'modalCaja (\''.URL::route($ruta["apertura"], array('listar'=>'SI')).'\', \''.$titulo_apertura.'\', this);')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Iniciar Turno de Repartidor', array('class' => 'btn btn-primary waves-effect waves-light m-l-10 btn-sm btnTurno' , 'onclick' => 'modalCaja (\''.URL::route($ruta["turnoRepartidor"], array('listar'=>'SI')).'\', \''.$tituloTurnoRepartidor.'\', this);')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm btnNuevo', 'activo' => 'si' , 'onclick' => 'modalCaja (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-usd"></i> Registrar Pedido', array('class' => 'btn btn-dark waves-effect waves-light m-l-10 btn-sm btnPedido', 'onclick' => 'cargarRutaMenu("venta", "container", 16)')) !!}
	{!! Form::button('<i class="glyphicon glyphicon-remove-circle"></i> Cierre', array('class' => 'btn btn-danger waves-effect waves-light m-l-10 btn-sm btnCierre' , 'onclick' => 'modalCaja (\''.URL::route($ruta["cierre"], array('listar'=>'SI')).'\', \''.$titulo_cierre.'\', this);')) !!}
	@if($sucursal_id == 1)
		{!! Form::button('<i class="glyphicon glyphicon-download-alt"></i> Ingresar cajas de otras sucursales', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm btnIngresarCierres' , 'onclick' => 'modalCaja (\''.URL::route($ruta["ingresarcierres"], array('listar'=>'SI')).'\', \''.$tituloIngresarCierres.'\', this);')) !!}
	@endif
	{!! Form::button('<i class="glyphicon glyphicon-print"></i> Reporte Caja Actual', array('class' => 'btn btn-warning waves-effect waves-light m-l-10 btn-sm btnReporte', 'onclick' => 'imprimirDetalle();')) !!}
	<!-- GERSON (13-01-23) -->
	{!! Form::button('<i class="fa fa-exchange"></i> Transferencia entre Sucursal', array('class' => 'btn secondary waves-effect waves-light m-l-10 btn-sm btnCierre', 'onclick' => 'modalCaja (\''.URL::route($ruta["transferenciaSucursal"], array('listar'=>'SI')).'\', \''.$tituloTransferencia.'\', this);')) !!}
	<!--  -->
@endif
	
</div>

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
			<tr style ="background-color: {{ $value->estado == 1 ? '#ffffff' : '#ffc8cb'}} !important">
				<?php $concepto = Concepto::find($value->concepto_id); ?>
				
				@if($aperturaycierre == 1)	
					@if($value->estado == 1)
						@if( in_array($concepto->id, array(1, 2)) ) 
							<td align="center">-</td>
						@elseif( in_array($concepto->id, array(12, 13, 14, 15)) ) {{-- acciones repartidores --}}
							<?php
							$detalle_turno = Detalleturnopedido::where('pedido_id',$value->id)->first();
							$turno = Turnorepartidor::find($detalle_turno->turno_id);
							$ultimo_detalle_turno = Detalleturnopedido::where('turno_id',$turno->id)->orderBy('id', 'DESC')->first();
							$detalles_turno = Detalleturnopedido::where('turno_id',$turno->id)
											->join('movimiento', 'detalle_turno_pedido.pedido_id', '=', 'movimiento.id')
											->where('estado',1)
											->get();
							?>
							@if($turno->estado == "I" && (count($detalles_turno) <= 1 || $ultimo_detalle_turno->pedido_id == $value->id )) 
								<td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-danger btnEliminar')) !!}</td>
							@else
								<td align="center">-</td>
							@endif
						@else
							<td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-danger btnEliminar')) !!}</td>
						@endif
					@elseif($value->estado == 0)
						<td align="center">{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-secondary btnEliminar' ,'disabled')) !!}</td>
					@endif
				@elseif($aperturaycierre == 0)	
					<td align="center">-</td>
				@endif

				<td align="center">{{ $value->num_caja}}</td>
				<td align="center">{{ date("d/m/Y h:i:s a",strtotime($value->fecha)) }}</td>	
				<td>{{ $concepto->concepto}}</td>
				<?php $cliente = $value->persona_id ? Person::find($value->persona_id) : null; ?>
				@if($value->persona_id)
					<td>{{ $cliente->razon_social ? $cliente->razon_social : $cliente->nombres . ' ' .$cliente->apellido_pat. ' ' .$cliente->apellido_mat }}</td>
				@else
					<td align="center"> - </td>
				@endif
				<?php $trabajador = $value->trabajador_id ? Person::find($value->trabajador_id) : null; ?>
				@if($value->trabajador_id)
					<td>{{ $trabajador->nombres . ' ' .$trabajador->apellido_pat. ' ' .$trabajador->apellido_mat }}</td>
				@else
					<td align="center"> - </td>
				@endif
				
				@if($value->estado == 1)
					<td> {{ $value->comentario ? $value->comentario : '-' }} </td>
				@elseif($value->estado == 0)
					<td> {{ $value->comentario ? $value->comentario.' | ' : ''  }} ANULADO POR: {{ $value->comentario_anulado }} </td>
				@endif
				
				<?php
					if($value->venta && $value->concepto_id == 3){
						$total_ingreso = $value->venta->balon_a_cuenta == 1 ? $value->venta->total_pagado : $value->total;
					}else{
						$total_ingreso = $value->total;
					}
				?>
				@if($concepto->tipo == 0)
					<td align="center" style="color:green;font-weight: bold;">{{ $total_ingreso }}</td>
					<td align="center">0.00</td>
				@elseif($concepto->tipo == 1)
					<td align="center">0.00</td>
					<td align="center" style="color:red;font-weight: bold;">{{ $value->total }}</td>
				@endif
			</tr>
			@endforeach
		</tbody>
	</table>
	<table class="table-bordered table-striped table-condensed" align="center" style="width: 300px;">
		<thead>
			<tr>
				<th class="text-center" colspan="2">RESUMEN DE CAJA</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>MONTO APERTURA :</th>
				<th class="text-right">{{ number_format( $montoapertura ,2) }}</th>
			</tr>
			<tr>
				<th>INGRESOS :</th>
				<th class="text-right">{{ number_format( $ingresos_total ,2) }}</th>
			</tr>
			<tr>
				<th>EGRESOS :</th>
				<th class="text-right">{{ number_format( $egresos ,2) }}</th>
			</tr>
			<tr>
				<th>MONTO VUELTO :</th>
				<th class="text-right">{{ number_format( $monto_vuelto ,2) }}</th>
			</tr>
			<tr>
				<th>CAJA :</th>
				<th class="text-right">{{ number_format( $monto_caja ,2) }}</th>
			</tr>
		</tbody>
	</table>
</div>
@endif