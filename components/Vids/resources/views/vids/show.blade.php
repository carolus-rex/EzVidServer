@extends("base")

@push('head')
		<script>
			$(function () {
					$('[data-toggle="popover"]').popover()
			  })
		</script>
@endpush

@section('body')
		
		@section('navbar')
			
				<ul class="nav navbar-nav navbar-left">
					<li>
						<a href="{{$name}}/prev" >
							<span class="glyphicon glyphicon-chevron-left"></span>
							<span class="h4">@lang('Anterior')</span>
						</a>
					</li>
				</ul>
				
				<ul class="nav navbar-nav center-nav">
					<li>
						<div class="btn-group">
							@include('users.dropdown')
							<a class="btn btn-default navbar-btn" href={{"$name/gomain"}}>
								<span class="glyphicon glyphicon-home"></span>
							</a>
							@include('changelocale')
							<button class="btn btn-default navbar-btn" data-trigger="click" data-placement="bottom" data-content="menu" data-container="body" data-toggle="popover" data-template='
							

								<nav class="navbar navbar-inverse text-center navbar-static-top">
									<div class="container-fluid">

										@include("vids.filters_form", ["from" => "fromshow",
																	   "to" => $name])

									</div>						  
								</nav>
								
							
							'>
							<!--DONT DELETE THAT QUOT-->
								<span class="caret"></span>
							</button>	
						</div>
					</li>
				</ul>
				
				<ul class="nav navbar-nav navbar-right">
					<li>
						<a href="{{$name}}/next">
							<span class="h4">@lang('Siguiente')</span>
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</li>
				</ul>
			
		@endsection		
		<div class="container-fluid">
			<div class="row complete-height">
			<!--States form-->
				<div class="col-xs-2 complete-height">
					@include('vids.states_form')
				</div>
			<!--Vid-->
				<div class="col-xs-8 complete-height">
					<video class="img-responsive no-overflow-height center-block" autoplay controls muted>
						<source src={{$vidpath}} type='video/mp4'>
						Your browser does not support the video tag.
					</video>
				</div>
			</div>
		</div>
@endsection
