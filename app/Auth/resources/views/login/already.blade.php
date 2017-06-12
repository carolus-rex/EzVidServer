@extends('narrow_centered')

@section('content')
	<p class="text-center">
	<h1>
		@lang('Ya has iniciado sesión como ')
		<p>
			<strong>
				{{Auth::user()->name}}
			</strong>
		</p>
	</h1>
	
	<h3>
		<a href="{{ route('login.logout') }}">
			@lang('Haz clic aqui para salir y proseguir')
		</a>
	</h3>
</p>
@endsection
