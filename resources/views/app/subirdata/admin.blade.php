<div class="row">
	<div class="col-lg-1 col-md-1"></div>
	<div class="col-lg-10 col-md-10 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> Cargar Data</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["search"], 'method' => 'POST' ,'onsubmit' => 'return false;', 'class' => 'form-inline', 'role' => 'form', 'autocomplete' => 'off', 'id' => 'formCargarData']) !!}
				{!! Form::hidden('page', 1, array('id' => 'page')) !!}
				{!! Form::hidden('accion', 'listar', array('id' => 'accion')) !!}
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
					{!! Form::label('datae', 'Archivo Esquelas:') !!}
					{!! Form::file('datae', '', array('class' => 'form-control input-sm', 'id' => 'datae')) !!}
					<div style="text-align: center;">
						{!! Form::button('<i class="glyphicon glyphicon-open"></i> Cargar Data', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => '')) !!}
					</div>
				</div>
				<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
					{!! Form::label('datac', 'Archivo Cartas:') !!}
					{!! Form::file('datac', '', array('class' => 'form-control input-sm', 'id' => 'datac')) !!}
					<div style="text-align: center;">
						{!! Form::button('<i class="glyphicon glyphicon-open"></i> Cargar Data', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => '')) !!}
					</div>
				</div>
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
	<div class="col-lg-1 col-md-1"></div>
</div>

<script>
	$(document).ready(function () {
		
	});
</script>