<?php
use App\Producto;
$sum_dev = 0 ;
if($sum_devuelto->devueltos!=null){
	$sum_dev = $sum_devuelto->devueltos;
}
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('pedido_id',$pedido->id,array('id'=>'pedido_id')) !!}
	{!! Form::hidden('devolver_envases','',array('id'=>'devolver_envases')) !!}
	{!! Form::hidden('data','',array('id'=>'data')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div style=" border: solid 1px; border-radius: 5px; height: 40px; margin-top: 10px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">LISTA DE PRODUCTOS</h4>
				<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
					<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cantidad</th><th style="font-size: 13px !important;">Envases prestados</th><th style="font-size: 13px !important;">Devolver envases</th></tr></thead>
					<tbody id="detalle">
						@foreach($detalles  as $key => $value)
							<tr>
								<td>{{ $value->producto->descripcion }} </td>
								<td align="center">{{ intval($value->cantidad) }} </td>
								@if($value->producto->recargable ==1)
									@if($value->producto_id == 4 || $value->producto_id == 5)
										<td align="center">
										@php
											$idinput = "cant_".$value->id;
										@endphp
										@if( $detalles_prestamos != null)
											@if( $detalles_prestamos[$value->id] != null )
												{{ $detalles_prestamos[$value->id] }}
											@else
												-
											@endif
										@else
											-
										@endif
										</td>
									@else
										<td align="center"> - </td>
									@endif
								@else
									<td align="center"> - </td>
								@endif

								@if($value->producto->recargable ==1)
									@if($value->producto_id == 4 || $value->producto_id == 5)
										<td align="center">
										@php
											$idinput = "cant_".$value->id;
											$cant_max = 0;
											if($sum_dev > 0){
												$cant_max = $detalles_prestamos[$value->id] - $sum_dev;
											}else{
												$cant_max = $detalles_prestamos[$value->id];
											}
										@endphp
										@if( $detalles_prestamos[$value->id] == $detalles_devuelto[$value->id] )
											{!! Form::number($idinput, '0', array('class' => 'cantidades form-control input-xs','style' => 'width:80px; ', 'readonly', 'id' => $idinput, 'max' => $cant_max, 'min' => '0')) !!}
										@else
											{!! Form::number($idinput, '0', array('class' => 'cantidades form-control input-xs', 'style' => 'width:80px; ', 'id' => $idinput, 'max' => $cant_max, 'min' => '0')) !!}
										@endif
										</td>
									@else
										<td align="center"> - </td>
									@endif
								@else
									<td align="center"> - </td>
								@endif

							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-12 col-md-12 col-sm-12 text-right">
			@if(in_array(true,$guardar))
				{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
			@endif
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>

	<!-- GERSON (09-11-22) -->
	<hr>
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div style=" border: solid 1px; border-radius: 5px; height: 40px; margin-top: 10px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">DEVOLUCIONES</h4>
				<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
					<thead id="cabecera">
						<tr>
							<th style="font-size: 13px !important;">#</th>
							<th style="font-size: 13px !important;">Producto</th>
							<th style="font-size: 13px !important;">Fecha de devolución</th>
							<th style="font-size: 13px !important;">Cantidad</th>
						</tr>
					</thead>
					<tbody id="">
						@php $cont = 1; @endphp
						@foreach($detalles  as $key => $v)
							@foreach($balones_devueltos[$v->id]  as $key => $b)
								<tr>
									<td>{{ $cont }} </td>
									<td>{{ $b->descripcion }} </td>
									<td>{{ date('d/m/Y H:i:s',strtotime($v->created_at)) }}</td>
									<td align="center">{{ $b->cantidad }} </td>
								</tr>
								@php $cont++; @endphp
							@endforeach
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!--  -->

{!! Form::close() !!}
<script type="text/javascript">
$(document).ready(function() {
	configurarAnchoModal('600');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$(".cantidades").blur(function(){
		var max = $(this).attr('max');
		var val = $(this).val();
		var data = [];
		$(".cantidades").each(function(){
			var element = $(this); // <-- en la variable element tienes tu elemento
			var id = element.attr('id');
			id = id.replace("cant_", "");
			var cantidad = $(this).val();
			if( cantidad != ""){
				data.push(
					{"id": id , "cantidad": cantidad }
				);
			}
		});
		if(max < val){
			$(this).val("");
			swal({
				title: 'INGRESE VALOR MENOR A LA CANTIDAD DEL PRODUCTO',
				type: 'error',
			});
		}else{
			data = [];
			$(".cantidades").each(function(){
				var element = $(this); // <-- en la variable element tienes tu elemento
				var id = element.attr('id');
				id = id.replace("cant_", "");
				var cantidad = $(this).val();
				if( cantidad != ""){
					data.push(
						{"id": id , "cantidad": cantidad }
					);
				}
			});
		}
		console.log(data);
		if(data.length == 0){
			$("#devolver_envases").val("");
		}else{
			$("#devolver_envases").val(1);
			$("#data").val(JSON.stringify(data));
		}
	});
}); 
</script>