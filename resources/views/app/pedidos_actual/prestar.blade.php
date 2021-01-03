<?php
use App\Producto;
?>
<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	{!! Form::hidden('pedido_id',$pedido->id,array('id'=>'pedido_id')) !!}
	{!! Form::hidden('envases_a_prestar','',array('id'=>'envases_a_prestar')) !!}
	{!! Form::hidden('data','',array('id'=>'data')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-12 col-md-12 col-sm-12">
			<div style=" border: solid 1px; border-radius: 5px; height: 40px; margin-top: 10px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">LISTA DE PRODUCTOS</h4>
				<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
					<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cantidad</th><th style="font-size: 13px !important;">Envases a prestar</th></tr></thead>
					<tbody id="detalle">
						@foreach($detalles  as $key => $value)
							<tr>
								<td>{{ $value->producto->descripcion }} </td>
								<td align="center">{{ $value->cantidad }} </td>
								@if($value->producto->recargable ==1)
									@if($value->producto_id == 4 || $value->producto_id == 5)
										<td align="center">
										@php
											$idinput = "cant_".$value->id;
										@endphp
										@if( $detalles_prestamos != null)
											@if( $detalles_prestamos[$value->id] != null )
												{!! Form::number($idinput, $detalles_prestamos[$value->id] , array('class' => 'cantidades form-control input-xs', 'readOnly' ,'style' => 'width:80px; ', 'id' => $idinput, 'max' => $value->cantidad, 'min' => '0')) !!}
											@else
												{!! Form::number($idinput, '' , array('class' => 'cantidades form-control input-xs', 'style' => 'width:80px; ', 'id' => $idinput, 'max' => $value->cantidad, 'min' => '0')) !!}
											@endif
										@else
											{!! Form::number($idinput, '' , array('class' => 'cantidades form-control input-xs', 'style' => 'width:80px; ', 'id' => $idinput, 'max' => $value->cantidad, 'min' => '0')) !!}
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
		@if( count($detalles_prestamos) > 0 )
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'style' => 'display:none;' , 'disabled','onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		@else
			{!! Form::button('<i class="fa fa-check fa-lg"></i> '.$boton, array('class' => 'btn btn-success btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		@endif
			{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal();')) !!}
		</div>
	</div>
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
		if(data.length == 0){
			$("#envases_a_prestar").val("");
		}else{
			$("#envases_a_prestar").val(1);
			$("#data").val(JSON.stringify(data));
		}
	});
}); 
</script>