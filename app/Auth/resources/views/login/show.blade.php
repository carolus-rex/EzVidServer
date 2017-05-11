@extends('vids.base')

@section('body')
	<form method="POST" action="{{ route('login') }}">
		{{ csrf_field() }}
		<input type="textinput" name="email">
		<input type="textinput" name="password">
		<input type="submit" name="submit_login" value="Ingresar">
	</form>
@endsection
