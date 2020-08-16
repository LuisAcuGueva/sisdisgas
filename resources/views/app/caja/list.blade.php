<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Person;
use App\User;
use App\Concepto;
use App\Movimiento;

$user = Auth::user();

$venta = "'venta'";
$container = "'container'";
?>

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

@endif
<input id="monto_apertura" name="monto_apertura" type="hidden" value="{{$montoapertura}}">
<input id="monto_vuelto" name="monto_vuelto" type="hidden" value="{{$monto_vuelto}}">
<input id="ingresos_efectivo" name="ingresos_efectivo" type="hidden" value="{{$ingresos_efectivo}}">
<input id="ingresos_visa" name="ingresos_visa" type="hidden" value="{{$ingresos_visa}}">
<input id="ingresos_master" name="ingresos_master" type="hidden" value="{{$ingresos_master}}">
<input id="ingresos_total" name="ingresos_total" type="hidden" value="{{$ingresos_total}}">
<input id="egresos" name="egresos" type="hidden" value="{{$egresos}}">
<input id="saldo" name="saldo" type="hidden" value="{{$saldo}}">
<input id="caja_efectivo" name="caja_efectivo" type="hidden" value="{{$monto_caja}}">

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
	@if($value->estado == 1)
		<tr style ="background-color: #ffffff !important">
	@elseif($value->estado == 0)
		<tr style ="background-color: #ffc8cb !important">
	@endif

		<?php
			$concepto = Concepto::find($value->concepto_id);
		?>

		<td align="center">
		@if($aperturaycierre == 1)	
			@if($value->estado == 1)
				@if($concepto->id == 1)
					-
				@elseif ($concepto->id == 2)
					-
				@else
					{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-danger btnEliminar' ,'activo' => 'si')) !!}
				@endif
			@elseif($value->estado == 0)
				{!! Form::button('<div class="glyphicon glyphicon-remove"></div>', array('onclick' => 'modal (\''.URL::route($ruta["delete"], array($value->id, 'SI')).'\', \''.$titulo_eliminar.'\', this);', 'class' => 'btn btn-sm btn-secondary btnEliminar' ,'disabled')) !!}
			@endif
		@elseif($aperturaycierre == 0)	
			-
		@endif
		</td>

		<td align="center">{{ $value->num_caja}}</td>
		
		<td align="center">{{ $fechaformato = date("d/m/Y h:i:s a",strtotime($value->fecha))}}</td>	
		
		<td>{{ $concepto->concepto}}</td>

		<?php
			$cliente = null;
			if(!$value->persona_id == null){
				$cliente = Person::find($value->persona_id);
			}
		?>

		@if($value->persona_id == null)
			<td align="center"> - </td>
		@else
			@if($cliente->razon_social != null)
				<td>{{ $cliente->razon_social }}</td>
			@else
				<td>{{ $cliente->nombres . ' ' .$cliente->apellido_pat. ' ' .$cliente->apellido_mat }}</td>
			@endif
		@endif

		<?php
			$trabajador = null;
			if(!$value->trabajador_id == null){
				$trabajador = Person::find($value->trabajador_id);
			}
		?>
		
		@if($value->trabajador_id == null)
			<td align="center"> - </td>
		@else
			<td>{{ $trabajador->nombres . ' ' .$trabajador->apellido_pat. ' ' .$trabajador->apellido_mat }}</td>
		@endif
		

		@if($concepto->tipo == 0)
		<td align="center" style="color:green;font-weight: bold;">{{ $value->total }}</td>
		<td align="center">0.00</td>
		@elseif($concepto->tipo == 1)
		<td align="center">0.00</td>
		<td align="center" style="color:red;font-weight: bold;">{{ $value->total }}</td>
		@endif

		@if( $value->comentario == null )
		<td align="center"> - </td>
		@else
		<td>{{ $value->comentario }}</td>
		@endif

		<?php
			$usuario = User::find($value->usuario_id);
			$persona_usuario = Person::find($usuario->person_id);
		?>

		<td>{{ $persona_usuario->nombres . ' ' .$persona_usuario->apellido_pat. ' ' .$persona_usuario->apellido_mat }}</td>

		</tr>
		@endforeach
	</tbody>
</table>
<table class="table-bordered table-striped table-condensed" align="center">
    <thead>
        <tr>
            <th class="text-center" colspan="2">RESUMEN DE CAJA</th>
        </tr>
    </thead>
    <tbody>
		<tr>
            <th>MONTO APERTURA :</th>
            <th class="text-right"><div id ="montoapertura"></div></th>
        </tr>
		<tr>
            <th>MONTO VUELTO :</th>
            <th class="text-right"><div id ="montovuelto"></div></th>
        </tr>
        <tr>
            <th>INGRESOS :</th>
            <th class="text-right"><div id ="ingresostotal"></div></th>
        </tr>
        <tr style="display: none;">
            <td>EFECTIVO :</td>
            <td align="right"><div id ="ingresosefectivo"></div></td>
        </tr>
        <tr style="display: none;">
            <td>VISA :</td>
            <td align="right"><div id ="ingresosvisa"></div></td>
        </tr>
		<tr style="display: none;">
            <td>MASTERCARD :</td>
            <td align="right"><div id ="ingresosmaster"></div></td>
        </tr>
        <tr>
            <th>EGRESOS :</th>
            <th class="text-right"><div id ="egreso"></div></th>
        </tr>
        <!--tr>
            <th>SALDO :</th>
            <th class="text-right"><div id ="saldoo"></div></th>
        </tr-->
		<tr>
            <th>CAJA :</th>
            <th class="text-right"><div id ="caja_efectivo2"></div></th>
        </tr>
    </tbody>
</table>
</div>

@endif

<script>
	var ingresos_total = {{$ingresos_total}};
	var ingresos_efectivo = {{$ingresos_efectivo}};
	var ingresos_visa = {{$ingresos_visa}};
	var ingresos_master = {{$ingresos_master}};
	var egresos = {{$egresos}};
	var saldo = {{$saldo}};
	var montoapertura = {{$montoapertura}};
	var monto_vuelto = {{$monto_vuelto}};
	var monto_caja = {{$monto_caja}}
	
	$(document).ready(function () {

		if($(".btnEliminar").attr('activo')=== 'no'){
			$('.btnEliminar').attr("disabled", true);
		}

		console.log("monto vuelto = " + monto_vuelto );

		$('#ingresostotal').html(ingresos_total.toFixed(2));
		$('#ingresosefectivo').html(ingresos_efectivo.toFixed(2));
		$('#ingresosvisa').html(ingresos_visa.toFixed(2));
		$('#ingresosmaster').html(ingresos_master.toFixed(2));
		$('#egreso').html(egresos.toFixed(2));
		$('#saldoo').html(saldo.toFixed(2));
		$('#montoapertura').html(montoapertura.toFixed(2));
		$('#montovuelto').html(monto_vuelto.toFixed(2));
		$('#caja_efectivo2').html(monto_caja.toFixed(2));
	});

</script>
