@extends('form', ['form_title' => 'Iniciar Sesión',
				  'form_route' => 'login'])

@section('form')

	<div class="form-group">
		<input type="email" class="form-control" name="email" placeholder="@lang('Correo')">
	</div>
	<div class="form-group">
		<input type="password" class="form-control" name="password" placeholder="@lang('Contraseña')">
	</div>

	<input type="submit" class="btn btn-primary" name="submit_login" value="@lang('Ingresar')">

@endsection
