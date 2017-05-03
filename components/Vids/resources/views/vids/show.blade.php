@extends("vids.base")

@push('head')
		<script>
			$(function () {
					$('[data-toggle="popover"]').popover()
			  })
		</script>
@endpush

@section('body')
		
		@section('navbar')
		
			<div class="container-fluid text-center">
			
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
							<a class="btn btn-default navbar-btn" href={{"$name/gomain"}}>
								<span class="glyphicon glyphicon-home"></span>
							</a>
							@include('changelocale')
							<button class="btn btn-default navbar-btn" data-trigger="click" data-placement="bottom" data-content="menu" data-container="body" data-toggle="popover" data-template='
							

								<nav class="navbar navbar-inverse text-center navbar-static-top">
								
									@include("vids.filters_form", ["from" => "fromshow",
																  "to" => $name])
																  
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
				
			</div>
			
		@endsection		
		<div class="container-fluid">
			<div class="row complete-height">
			<!--State form-->
				<div class="col-xs-2 complete-height">
					<h3>@lang(VID_STATE_STRING[$state])</h3>
					
					<form class="buttonform-inline" action='{{$name}}' method='POST'>
						{{csrf_field()}}
						{{method_field('PUT')}}
						<input type='submit' class="btn btn-success" value="@lang('Aprobar')" name='Aprobar'>
					</form>
					
					<form class="buttonform-inline" action='{{$name}}' method='POST'>
						{{csrf_field()}}
						{{method_field('DELETE')}}
						<input type='submit' class="btn btn-danger" value="@lang('Borrar')" name='DELETE'>
					</form>
					
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
