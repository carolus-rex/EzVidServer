@if (Auth::guest())
	<a href="{{route('login')}}">@lang("Ingresa")</a>
@else
	<a>
		{{Auth::user()->name}} 
	</a>
	<a href="{{route('login.logout')}}">@lang('Salir')</a>
@endif
