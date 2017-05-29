@extends('narrow_centered')

@section('content')

<div class="panel panel-default">

	<div class="panel-heading">
		<h1>@lang($form_title)</h1>
	</div>


	<div class="panel-body">
		<form method="POST" action="{{ route($form_route) }}" class="form-horizontal">
			
			{{ csrf_field() }}
			@yield('form')

		</form>
	</div>

</div>

@endsection
