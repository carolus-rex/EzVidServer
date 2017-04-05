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
						<a href="{{$nombre}}/prev" >
							<span class="glyphicon glyphicon-chevron-left"></span>
							<span class="h4">Anterior</span>
						</a>
					</li>
				</ul>
				
				<ul class="nav navbar-nav center-nav">
					<li>
						<div class="btn-group">
							<a class="btn btn-default navbar-btn" href={{"$nombre/gomain"}}>
								<span class="glyphicon glyphicon-home"></span>
							</a>
							<button class="btn btn-default navbar-btn" data-trigger="click" data-placement="bottom" data-content="menu" data-container="body" data-toggle="popover" data-template='
							

								<nav class="navbar navbar-inverse text-center navbar-static-top">
								
									@include("vids.filters_form", ["from" => "fromshow",
																  "to" => $nombre])
																  
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
						<a href="{{$nombre}}/next">
							<span class="h4">Siguiente</span>
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</li>
				</ul>
				
			</div>
			
		@endsection		
		<div class="container-fluid">
			<div class="row complete-height">
			<!--Status form-->
				<div class="col-xs-2 complete-height">
					<h3>{{VID_STATUS_STRING[$estado]}}</h3>
					
					<form class="buttonform-inline" action='{{$nombre}}' method='POST'>
						{{csrf_field()}}
						{{method_field('PUT')}}
						<input type='submit' class="btn btn-success" value='Aprobar' name='Aprobar'>
					</form>
					
					<form class="buttonform-inline" action='{{$nombre}}' method='POST'>
						{{csrf_field()}}
						{{method_field('DELETE')}}
						<input type='submit' class="btn btn-danger" value='Borrar' name='DELETE'>
					</form>
					
				</div>
			<!--Vid-->
				<div class="col-xs-8 complete-height">
					<video class="img-responsive no-overflow-height center-block" autoplay controls muted>
						<source src={{$videopath}} type='video/mp4'>
						Your browser does not support the video tag.
					</video>
				</div>
			</div>
		</div>
@endsection
