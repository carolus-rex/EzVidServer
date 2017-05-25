@extends('vids.base')

@section('body')

	@section('navbar')
		<div class="navbar-left">
			@include('changelocale')
		</div>
	@endsection
	
	<div class="container">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<div class="panel panel-default">

					<div class="panel-heading">
						<h1>@lang('Regístrate')</h1>
					</div>


					<div class="panel-body">
						<form method="POST" action="{{ route('register') }}" class="form-horizontal">
							
							{{ csrf_field() }}
							<div class="form-group">
								@include('validation.form_error', ['form_field' => 'name'])
								<input type="textinput" class="form-control" name="name" placeholder="@lang('Nombre')" value="{{ session()->getOldInput('name') }}">
							</div>
							<div class="form-group">
								@include('validation.form_error', ['form_field' => 'email'])
								<input type="email" class="form-control" name="email" placeholder="@lang('Correo')" value="{{ session()->getOldInput('email') }}">
							</div>
							<div class="form-group">
								@include('validation.form_error', ['form_field' => 'password'])
								<input type="password" class="form-control" name="password" placeholder="@lang('Contraseña')">
							</div>
							<div class="form-group">
								@include('validation.form_error', ['form_field' => 'password_confirmation'])
								<input type="password" class="form-control" name="password_confirmation" placeholder="@lang('Confirma la contraseña')">
							</div>
								
							<input type="submit" class="btn btn-primary" name="submit_login" value="@lang('Registrar')">
						
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>
@endsection
