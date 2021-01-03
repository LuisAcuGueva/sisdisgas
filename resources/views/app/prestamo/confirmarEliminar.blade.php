<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($pedido, $formData) !!}
{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

<div class="col-lg-12 col-md-12 col-sm-12 tipopago">
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div style=" border: solid 1px; border-radius: 5px; height: 40px; margin-top: 10px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
			<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">LISTA DE PRODUCTOS</h4>
			<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
				<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cantidad</th><th style="font-size: 13px !important;">Envases prestados</th><th style="font-size: 13px !important;">Envases devueltos</th></tr></thead>
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
									@endphp
									@if( $detalles_devuelto != null)
										@if( $detalles_devuelto[$value->id] != null )
											{{ $detalles_devuelto[$value->id] }}
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

						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6">
	@if(count($detalles_prestamos) > 0)
		<input class="balon" name="anul_prestamo" value="P" type="radio" id="anul_prestamo" checked>
	@else
		<input class="balon" name="anul_prestamo" value="P" type="radio" id="anul_prestamo" disabled checked>
	@endif
		{!! Form::label('', 'Prestamo' ,array('class' => 'input-lg', 'style' => 'margin-top: 5px;'))!!}
	</div>
	<div class="col-lg-6 col-md-6 col-sm-6">
	@if(count($detalles_devuelto) > 0)
		<input class="balon" name="anul_prestamo" value="D" type="radio" id="anul_devolucion" checked>
	@else
		<input class="balon" name="anul_prestamo" value="D" type="radio" id="anul_devolucion" disabled>
	@endif
		{!! Form::label('', 'Devolución' ,array('class' => 'input-lg', 'style' => 'margin-top: 5px;'))!!}
	</div>
</div>

<div class="form-group">
	<div class="col-lg-12 col-md-12 col-sm-12 text-right">
		{!! Form::button('<i class="glyphicon glyphicon-remove"></i> '.$boton, array('class' => 'btn btn-danger btn-sm', 'id' => 'btnGuardar', 'onclick' => 'guardar(\''.$entidad.'\', this)')) !!}
		{!! Form::button('<i class="fa fa-exclamation fa-lg"></i> Cancelar', array('class' => 'btn btn-dark btn-sm', 'id' => 'btnCancelar'.$entidad, 'onclick' => 'cerrarModal((contadorModal - 1));')) !!}
	</div>
</div>
{!! Form::close() !!}
<script type="text/javascript">
	$(document).ready(function() {
		init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');
		configurarAnchoModal('500');
		
		$('input').iCheck({
			checkboxClass: 'icheckbox_flat-green',
			radioClass: 'iradio_flat-green'
		});

		$('.tipopago .iCheck-helper').on('click', function(){
			var divpadre = $(this).parent();
			var input = divpadre.find('input');
			if( input.attr('id') == 'pago_sucursal'){
				if( divpadre.hasClass('checked')) { 
					$(".empleado").css('background', 'rgb(255,255,255)');
					$(".repartidor").css('display','none');
					$(".sucursal").css('display','');
					$('#repartidor').val('');
				}
			}else if( input.attr('id') == 'pago_repartidor'){ //codigo_vale_subcafae
				if( divpadre.hasClass('checked')) { 
					$(".sucursal").css('display','none');
					$(".repartidor").css('display','');
				}
			}
		});
	}); 
</script>