@extends('form', ['form_title' => 'Regístrate',
				  'form_route' => 'register'])

@section('form')

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

@endsection
