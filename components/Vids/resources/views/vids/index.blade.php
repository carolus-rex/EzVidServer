@php
	$colswidth = 3;
@endphp

@extends('vids.base')

@section('body')

		@section('navbar')
			<ul class="nav nav-pills">
				<li>
					@include('vids.filters_form', ['from' => 'fromindex'])
				</li>
				<li>
					<div class="navbar-form">
						@include('changelocale')
					</div>
				</li>
			</ul>
				
		@endsection
		<!-- Content -->
		<div class="container">
			<!-- Pagination -->
			<div class="row">
				{{$vids->links()}}
			</div>
			<!-- Vids -->
@foreach ($vids as $vid)
@if (($loop->iteration - 1) % (12/$colswidth) == 0)
			<div class="row">
@endif			

				<div class="col-xs-{{$colswidth}}">
					<a href="vids/{{$vid->name}}" class="thumbnail">
						<img class="img-responsive" src={{"$thumbs_url/$vid->name"}}.png alt="{{$vid->name}}">
					</a>
					<p class="text-center">
						<b>@lang("Estado"):</b>
						<br>
						@lang(VID_STATE_STRING[$vid->state])
					</p>
				</div>
@if ((($loop->iteration - 1) % (12/$colswidth) == (12/$colswidth) - 1) || $loop->last)
			</div>
@endif
@endforeach
			<!-- Pagination -->
			<div class="row">
				{{$vids->links()}}
			</div>
		</div>

@endsection
