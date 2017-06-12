@php 
	if (LaravelLocalization::getCurrentLocale() === 'es')
		$newlangstring = 'en';
	else
		$newlangstring = 'es';
@endphp

<a class="btn navbar-btn btn-default" href="{{ LaravelLocalization::getLocalizedURL($newlangstring) }}">
	{{$newlangstring}}
</a>
