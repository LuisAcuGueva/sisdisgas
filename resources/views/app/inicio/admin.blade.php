<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> {{ $title }}</h2>
				<div class="clearfix"></div>
			</div>
			
			<div id="divMensajeError{!! $entidad !!}"></div>
			<div class="x_content">
				{!! Form::open(['route' => $ruta["save"], 'method' => 'POST' , 'class' => 'form-horizontal form-label-left', 'autocomplete' => 'off', 'id' => 'formMantenimiento'.$entidad]) !!}
				{!! Form::hidden('listar', $listar, array('id' => 'listar')) !!}

				<div class="form-group">
					{!! Form::label('nombres', 'Nombres:') !!}
					{!! Form::text('nombres', $person->nombres, array('class' => 'form-control input-sm', 'id' => 'nombres' , 'readOnly')) !!}
				</div>
				
				<div class="row">
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
						{!! Form::label('apellido_pat', 'Apellido Paterno:') !!}
						{!! Form::text('apellido_pat', $person->apellido_pat, array('class' => 'form-control input-sm', 'id' => 'apellido_pat', 'readOnly')) !!}
					</div>
					
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
						{!! Form::label('apellido_mat', 'Apellido Materno:') !!}
						{!! Form::text('apellido_mat', $person->apellido_mat, array('class' => 'form-control input-sm', 'id' => 'apellido_mat', 'readOnly')) !!}
					</div>
				</div>

				<div class="row">
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12" style="display:none;">
						{!! Form::label('registro', 'Registro:') !!}
						{!! Form::text('registro', $person->registro, array('class' => 'form-control input-sm', 'id' => 'registro', 'readOnly')) !!}
					</div>
					
					<div class="form-group col-lg-6 col-md-6 col-sm-12 col-xs-12">
						{!! Form::label('dni', 'DNI:') !!}
						{!! Form::text('dni', $person->dni, array('class' => 'form-control input-sm', 'id' => 'dni', 'readOnly')) !!}
					</div>
				</div>

				<!--div class="row" style="text-align: center;">
					{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnGuardar', 'onclick' => 'guardarperfil(this)')) !!}
				</div-->
				{!! Form::close() !!}
				
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-bottom: 45px;">
		<div class="x_panel">
			<div class="x_title">
				<h2><i class="fa fa-gears"></i> Cambiar Contraseña</h2>
				<div class="clearfix"></div>
			</div>
			<div id="divMensajeErrorPassword"></div>
			<div class="x_content">
				{!! Form::model($user, $formData) !!}	

				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
					<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
						{!! Form::label('mypassword', 'Contraseña actual:') !!}
						<input class="form-control input-sm" id="mypassword" name="mypassword" type="password" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
				</div>
				
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
					<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
						{!! Form::label('password', 'Contraseña nueva:') !!}
						<input class="form-control input-sm" id="password" name="password" type="password" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
				</div>

				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
					<div class="form-group col-lg-8 col-md-8 col-sm-12 col-xs-12">
						{!! Form::label('password_confirmation', 'Confirmación de contraseña:') !!}
						<input class="form-control input-sm" id="password_confirmation" name="password_confirmation" type="password" value="">
					</div>
					<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12"></div>
				</div>

				<div class="row" style="text-align: center;">
					{!! Form::button('<i class="glyphicon glyphicon-floppy-disk"></i> Guardar', array('class' => 'btn btn-success waves-effect waves-light m-l-10 btn-sm', 'style' => 'margin-top: 5px;' , 'id' => 'btnBuscar', 'onclick' => 'cambiarpassword(this)')) !!}
				</div>
				{!! Form::close() !!}
				
			</div>

		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		
	});
</script>