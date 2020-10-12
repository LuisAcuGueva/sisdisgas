<!-- Page-Title -->
<?php

$hasta = date("Y-m-d");
$desde = strtotime ( '-30 day' , strtotime ( $hasta ) ) ;
$desde = date( 'Y-m-d' , $desde );

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Menuoption;
use App\OperacionMenu;
use App\Sucursal;

?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> {{ $title }}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
					{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formBusqueda'.$entidad]) !!}
					{!! Form::hidden('page', 1, array('id' => 'page')) !!}
					{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
					<div class="col-sm-12">
						<div class="form-group col-sm-2">
							{!! Form::label('sucursal_id', 'Sucursal:') !!}
							{!! Form::select('sucursal_id', $cboSucursal, null, array('class' => 'form-control input-sm', 'id' => 'sucursal_id' , 'onchange' => ' buscar(\''.$entidad.'\');')) !!}
						</div>
					</div>
					<div class="col-sm-12" style="margin-top:10px;">
						{!! Form::label('proveedor', 'Proveedor:') !!}
						<div class="form-group">
							{!! Form::text('proveedor', '', array('class' => 'form-control input-sm', 'id' => 'proveedor')) !!}
							{!! Form::hidden('proveedor_idb', '', array('id' => 'proveedor_idb')) !!}
						</div>
						{!! Form::button('<i class="glyphicon glyphicon-trash"></i>', array('id' => 'btnproductoborrar' , 'class' => 'btn btn-danger waves-effect waves-light btn-sm btnBorrar' , 'style' => 'height: 30px; margin-top: 5px;', 'data-toggle' => 'tooltip', 'data-placement' => 'top' ,  'title' => 'Borrar')) !!}
					</div>
					<div class="col-sm-12" style="margin-top:10px;">
						<div class="form-group">
							{!! Form::label('fechai', 'Fecha Inicio:') !!}
							{!! Form::date('fechai', $desde, array('class' => 'form-control input-sm', 'id' => 'fechai')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('fechaf', 'Fecha Fin:') !!}
							{!! Form::date('fechaf', $hasta, array('class' => 'form-control input-sm', 'id' => 'fechaf')) !!}
						</div>
						<div class="form-group">
							{!! Form::label('filas', 'Filas a mostrar:')!!}
							{!! Form::selectRange('filas', 1, 30, 10, array('class' => 'form-control input-xs', 'onchange' => 'buscar(\''.$entidad.'\')')) !!}
						</div>
							{!! Form::button('<i class="glyphicon glyphicon-search"></i> Buscar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'id' => 'btnBuscar', 'style' => 'margin-top: 5px;', 'onclick' => 'buscar(\''.$entidad.'\')')) !!}
							{!! Form::button('<i class="glyphicon glyphicon-plus"></i> Nuevo', array('class' => 'btn btn-info waves-effect waves-light m-l-10 btn-sm btnNuevo', 'activo' => 'si' , 'style' => 'margin-top: 5px;', 'onclick' => 'modal (\''.URL::route($ruta["create"], array('listar'=>'SI')).'\', \''.$titulo_registrar.'\', this);')) !!}
					</div>
					{!! Form::close() !!}
            </div>
			<div id="listado{{ $entidad }}"></div>
            <table id="datatable" class="table table-striped table-bordered">
            </table>
        </div>
    </div>
</div>
<script>
	$(document).ready(function () {
		$('[data-toggle="tooltip"]').tooltip({trigger: 'hover'});

		$('.btnBorrar').on('click', function(){
			$('#proveedor_id').val("");
			$('#proveedor').val("");
			$("#proveedor").prop('disabled',false);
			$('#proveedor').focus();
			buscar('{{ $entidad }}');
		});

		buscar('{{ $entidad }}');
		init(IDFORMBUSQUEDA+'{{ $entidad }}', 'B', '{{ $entidad }}');
		$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').keyup(function (e) {
			var key = window.event ? e.keyCode : e.which;
			if (key == '13') {
				buscar('{{ $entidad }}');
			}
		});
	});

	function pdf(entidad){
			window.open('tipousuario/pdf?descripcion='+$(IDFORMBUSQUEDA + '{{ $entidad }} :input[id="name"]').val());
	}

	var personas = new Bloodhound({
		datumTokenizer: function (d) {
			return Bloodhound.tokenizers.whitespace(d.value);
		},
		queryTokenizer: Bloodhound.tokenizers.whitespace,
		limit:10,
		remote: {
			url: 'proveedor/proveedorautocompleting/%QUERY',
			filter: function (personas) {
				return $.map(personas, function (proveedor) {
					return {
						value: proveedor.razon_social,
						id: proveedor.id,
					};
				});
			}
		}
	});
	personas.initialize();
	$('#proveedor').typeahead(null,{
		displayKey: 'value',
		limit:10,
		source: personas.ttAdapter()
	}).on('typeahead:selected', function (object, datum) {
		$('#proveedor_idb').val(datum.id);
		$('#proveedor').val(datum.razon_social);
		$('#proveedor').prop('disabled', true);
		buscar('{{ $entidad }}');
	});


</script>