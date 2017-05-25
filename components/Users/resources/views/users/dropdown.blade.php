@if (Auth::guest())
	<a class="btn navbar-btn btn-primary" href="{{ route('login.show') }}">@lang("Ingresa")</a>
	<a class="btn navbar-btn btn-primary" href="{{ route('register.show') }}">@lang('Regístrate')</a>
@else
	<div class="btn-group">
		<button class="btn navbar-btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			@lang('Hola') {{Auth::user()->name}} <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li>
				<a href="{{route('login.logout')}}">@lang('Salir')</a>
			</li>
		</ul>
	</div>
@endif
