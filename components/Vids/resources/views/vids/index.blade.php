@php
	$lg_colswidth = 12/      4; //vids per row
	$md_colswidth = 12/      4; //vids per row
	$sm_colswidth = 12/      3; //vids per row
	$xs_colswidth = 12/      2; //vids per row
@endphp

@extends('base')

@section('body')

	@section('navbar')

		<div class="nav navbar-nav navbar-left">
			@include('vids.filters_form', ['from' => 'fromindex'])
		</div>
		<div class="navbar-form navbar-left">
			@include('changelocale')
		</div>	

		<div class="navbar-form navbar-right">
			@include('users.dropdown')
		</div>
			
	@endsection
	<!-- Content -->
	<div class="container">
		<!-- Pagination -->
		<div class="row">
			{{$vids->links()}}
		</div>
		<!-- Vids -->
		<div class="row">
		@foreach ($vids as $vid)

			<div class="col-xs-{{$xs_colswidth}} col-sm-{{$sm_colswidth}} col-md-{{$md_colswidth}} col-lg-{{$lg_colswidth}}">
				<a href="{{ route('vids.show', ['vid' => $vid->name]) }}" class="thumbnail">
					<img class="img-responsive" src='{{"$thumbs_url/$vid->name"}}.png' alt="{{$vid->name}}">
				</a>
				<p class="text-center">
					<b>@lang("Estado"):</b>
					<br>
					@lang(VID_STATE_STRING[$vid->state_id])
				</p>
			</div>

		@endforeach
		</div>
		<!-- Pagination -->
		<div class="row">
			{{$vids->links()}}
		</div>
	</div>

@endsection
