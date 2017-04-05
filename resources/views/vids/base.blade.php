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
			@yield('navbar')
		</nav>
		@yield('body')
	</body>
</html>
