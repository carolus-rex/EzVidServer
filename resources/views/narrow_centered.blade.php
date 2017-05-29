@extends('base')

@section('body')

	@section('navbar')
		<div class="navbar-left">
			@include('changelocale')
		</div>
	@endsection
	
	<div class="container">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
					
				@yield('content')

			</div>
		</div>
	</div>
	
@endsection
