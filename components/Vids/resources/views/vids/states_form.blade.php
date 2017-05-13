<h3>@lang(VID_STATE_STRING[$state])</h3>

@can('aprove', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action='{{$name}}' method='POST'>
		{{csrf_field()}}
		{{method_field('PUT')}}
		<input type='submit' class="btn btn-success" value="@lang('Aprobar')" name='Aprobar'>
	</form>
@elsecan('vote', $vid)
	<form class="buttonform-inline" action="{{route('vids.vote', ['vid' => $name])}}" method='POST'>
		{{csrf_field()}}
		{{method_field('POST')}}
		<input type='submit' class="btn btn-success" value="@lang('No lo borres, por favor D:')" name='KEEP'>
	</form>
@endcan


@can('delete', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action='{{$name}}' method='POST'>
		{{csrf_field()}}
		{{method_field('DELETE')}}
		<input type='submit' class="btn btn-danger" value="@lang('Borrar')" name='DELETE'>
	</form>
@elsecan('vote', $vid)
	<form class="buttonform-inline" action="{{route('vids.vote', ['vid' => $name])}}" method='POST'>
		{{csrf_field()}}
		{{method_field('POST')}}
		<input type='submit' class="btn btn-danger" value="@lang('DESTRUYELO :D')" name='NOTKEEP'>
	</form>
@endcan
<br>

@if(Auth::check())
	@cannot('vote', $vid)
		@lang('Ya votaste')
	@endcannot
@endif

<br>

{{$vid->votes()->where('should_keep', true)->count()}} @lang('No lo borres')
<br>
{{$vid->votes()->where('should_keep', false)->count()}} @lang('BORRALO')
