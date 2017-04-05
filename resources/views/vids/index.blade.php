@php
	$colswidth = 3;
@endphp

@extends('vids.base')

@section('body')

		@section('navbar')
			
			@include('vids.filters_form', ['from' => 'fromindex'])
				
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
					<a href=vids/{{$vid->video}} class="thumbnail">
						<img class="img-responsive" src={{"$thumbs_url/$vid->video"}}.png alt={{$vid->video}}>
					</a>
					<p class="text-center">
						<b>Estado:</b>
						<br>
						{{VID_STATUS_STRING[$vid->estado]}}
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
