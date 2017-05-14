<!DOCTYPE html>
<html lang="en" id={{$view_name}}>
	<head>
		<!-- Bootstrap -->
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="{{ asset('js/app.js') }}"></script>
		@stack('head')
	</head>
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-inverse navbar-static-top">
			<div class="container-fluid text-center">
				<!--<div class="navbar-header">
					 <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-content" aria-expanded="false">
				        <span class="sr-only">Toggle navigation</span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
				        <span class="icon-bar"></span>
      				</button>
				</div>-
				<div class="collapse navbar-collapse" id="navbar-content">-
				LOOKS AWFUL-->
					@yield('navbar')
				<!--</div>-->
			</div>
		</nav>
		@yield('body')
	</body>
</html>
