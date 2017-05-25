@extends('vids.base')

@section('body')
	@section('navbar')
		<div class="navbar-left">
			@include('changelocale')
		</div>
	@endsection

	<div class="container">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-8 col-md-offset-2">
				<p class="text-center">
					<h1>@lang('Haz sido registrado exitosamente')</h1>

					<h3>
						<a href="{{ route('vids.index') }}">
							@lang('Haz clic aqui para ir a la página principal')
						</a>
					</h3>
				</p>
			</div>
		</div>
	</div>

@endsection
