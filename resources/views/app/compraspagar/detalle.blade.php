<div id="divMensajeError{!! $entidad !!}"></div>
{!! Form::model($compra, $formData) !!}	
	{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}
	<div class="col-lg-12 col-md-12 col-sm-12">
		<div class="col-lg-3 col-md-3 col-sm-3" id="divDatosDocumento1">
			<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 40px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">DATOS DEL DOCUMENTO</h4>
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('sucursal', 'Sucursal:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('sucursal', $compra->sucursal->nombre, array('class' => 'form-control input-sm', 'id' => 'sucursal', 'readOnly')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('tipodocumento', 'Tipo de Documento:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('tipodocumento', $compra->tipodocumento->descripcion , array('class' => 'form-control input-sm', 'id' => 'tipodocumento', 'readOnly')) !!}		
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('serieventa', 'Número:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('serieventa', $compra->tipodocumento->abreviatura . $compra->num_compra , array('class' => 'form-control input-sm', 'id' => 'serieventa', 'data-inputmask' => "'mask': '9999-9999999'", 'readOnly')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('fecha', 'Fecha:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('fecha', date("d/m/Y",strtotime($compra->fecha )), array('class' => 'form-control input-sm', 'id' => 'fecha', 'readOnly')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('proveedor', 'Proveedor:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				@if(!is_null($compra->persona->dni))
				{!! Form::textarea('proveedor', $compra->persona->apellido_pat.' '.$compra->persona->apellido_mat.' '.$compra->persona->nombres, array('class' => 'form-control input-sm','rows' => '2', 'id' => 'proveedor', 'readOnly')) !!}
				@else
				{!! Form::textarea('proveedor', $compra->persona->razon_social , array('class' => 'form-control input-sm','rows' => '2', 'id' => 'proveedor', 'readOnly')) !!}
				@endif
				{!! Form::hidden('cliente_id',null,array('id'=>'cliente_id')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('celular', 'Celular:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::text('celular', $compra->persona->celular, array('class' => 'form-control input-xs','id' => 'celular', 'readOnly')) !!}
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
				{!! Form::label('cliente_direccion', 'Dirección:' ,array('class' => 'input-sm', 'style' => 'margin-bottom: -8px;'))!!}
				{!! Form::textarea('cliente_direccion', $compra->persona->direccion, array('class' => 'form-control input-xs', 'rows' => '2','id' => 'cliente_direccion', 'readOnly')) !!}
			</div>
		</div>

		
		<div class="col-lg-6 col-md-6 col-sm-6">
			<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 40px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 class="page-venta" style="padding-top: 1px;  font-weight: 600;">DETALLE DE COMPRA</h4>
				<table class="table table-striped table-bordered col-lg-12 col-md-12 col-sm-12 " style="margin-top: 15px; padding: 0px 0px !important;">
					<thead id="cabecera"><tr><th style="font-size: 13px !important;">Descripción</th><th style="font-size: 13px !important;">Cantidad</th><th style="font-size: 13px !important;">Precio Compra</th><th style="font-size: 13px !important;">Subtotal</th></tr></thead>
					<tbody id="detalle">
						@foreach($detalles  as $key => $value)
							<tr>
							<td>{{ $value->producto->descripcion }} </td>
							<td align="center">{{ $value->cantidad }} </td>
							<td align="center">{{ $value->precio }} </td>
							<td align="center">{{ number_format($value->cantidad * $value->precio, 2)}} </td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			
		</div>

		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="col-lg-12 col-md-12 col-sm-12" style=" border: solid 1px; border-radius: 5px; height: 40px; margin-bottom: 10px; text-align: center; color: #ffffff; border-color: #2a3f54; background-color: #2a3f54; ">
				<h4 clas="page-venta" style="padding-top: 1px;  font-weight: 600;">PAGO</h4>
			</div>

			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15">
					
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="display: none;">
					<div  class="col-lg-4 col-md-4 col-sm-4">
						<img src="assets/images/efectivo.png" style="width: 60px; height: 60px">
					</div>
					<div  class="col-lg-8 col-md-8 col-sm-8">
						{!! Form::text('montoefectivo', number_format($compra->total + $compra->vuelto,2) , array('class' => 'form-control input-lg montos', 'id' => 'montoefectivo', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00' , 'readOnly')) !!}
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="display: none;">
					<div  class="col-lg-4 col-md-4 col-sm-4">
						<img src="assets/images/visa.png" style="width: 60px; height: 60px">
					</div>
					<div  class="col-lg-8 col-md-8 col-sm-8">
						{!! Form::text('montovisa', '', array('class' => 'form-control input-lg montos', 'id' => 'montovisa', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="display: none;">
					<div  class="col-lg-4 col-md-4 col-sm-4">
						<img src="assets/images/master.png" style="width: 60px; height: 40px">
					</div>
					<div  class="col-lg-8 col-md-8 col-sm-8">
						{!! Form::text('montomaster', '', array('class' => 'form-control input-lg montos', 'id' => 'montomaster', 'style' => 'text-align: right; font-size: 30px;', 'placeholder' => '0.00')) !!}
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 m-b-15" style="margin-top: 10px;">
					{!! Form::label('total', 'Total:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::text('total', $compra->total, array('class' => 'form-control input-lg', 'id' => 'total', 'readOnly', 'style' => 'text-align: right; font-size: 30px; margin-top: 25px;')) !!}
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top: 10px; display: none;">
					{!! Form::label('vuelto', 'Vuelto:' ,array('class' => 'input-md', 'style' => 'margin-bottom: -30px;'))!!}
					{!! Form::text('vuelto', $compra->vuelto , array('class' => 'form-control input-lg', 'id' => 'vuelto', 'readOnly', 'style' => 'text-align: right; font-size: 30px; margin-top: 25px;', 'placeholder' => '0.00')) !!}
				</div>

				</div>
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
	configurarAnchoModal('1200');
	init(IDFORMMANTENIMIENTO+'{!! $entidad !!}', 'M', '{!! $entidad !!}');

	$('input').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});
}); 
</script>