<form class="navbar-form" action='{{route("vids.index")}}/setfilter/{{$from}}{{isset($to) ? "/$to" : ""}}' method="POST">
	{{csrf_field()}}
	<input type="submit" class="btn navbar-btn btn-default {{$show_all == 'true' ? 'active' : ''}}" aria-pressed="{{$show_all}}" autocomplete="off" name="all" value="@lang('Todos')">
	<div class="btn-group">
		<input type="submit" class="btn navbar-btn btn-default {{$show_unchecked == 'true' ? 'active' : ''}}" aria-pressed="{{$show_unchecked}}" autocomplete="off" name="unchecked" value="@lang('No Vistos')">
		<input type="submit" class="btn navbar-btn btn-default {{$show_checked == 'true' ? 'active' : ''}}" aria-pressed="{{$show_checked}}" autocomplete="off" name="checked" value="@lang('Vistos')">
		<input type="submit" class="btn navbar-btn btn-default {{$show_aproved == 'true' ? 'active' : ''}}" aria-pressed="{{$show_aproved}}" autocomplete="off" name="aproved" value="@lang('Aprobados')">
	</div>
</form>
