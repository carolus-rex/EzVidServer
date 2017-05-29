@extends('narrow_centered')

@section('content')

	<p class="text-center">
		<h1>@lang('Haz sido registrado exitosamente')</h1>

		<h3>
			<a href="{{ route('vids.index') }}">
				@lang('Haz clic aqui para ir a la página principal')
			</a>
		</h3>
	</p>

@endsection
