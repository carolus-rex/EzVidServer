<h3>@lang(VID_STATE_STRING[$vid->state_id])</h3>

@can('aprove', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action="{{$vid->name}}" method='POST'>
		{{csrf_field()}}
		{{method_field('PUT')}}
		<input type='submit' class="btn btn-success" value="@lang('Aprobar')" name='Aprobar'>
	</form>
@elsecan('vote', $vid)
	<form class="buttonform-inline" action="{{route('vids.vote', ['vid' => $vid->name])}}" method='POST'>
		{{csrf_field()}}
		{{method_field('POST')}}
		<input type='submit' class="btn btn-success" value="@lang('No lo borres, por favor D:')" name='KEEP'>
	</form>
@endcan


@can('delete', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action="{{$vid->name}}" method='POST'>
		{{csrf_field()}}
		{{method_field('DELETE')}}
		<input type='submit' class="btn btn-danger" value="@lang('Borrar')" name='DELETE'>
	</form>
@elsecan('vote', $vid)
	<form class="buttonform-inline" action="{{route('vids.vote', ['vid' => $vid->name])}}" method='POST'>
		{{csrf_field()}}
		{{method_field('POST')}}
		<input type='submit' class="btn btn-danger" value="@lang('DESTRUYELO :D')" name='NOTKEEP'>
	</form>
@endcan
<br>

@if(Auth::check())
	@cannot('vote', $vid)
		<p class="bg-info text-center">
			@lang('Ya votaste')
		</p>
	@endcannot
@endif

<br>

<p class="text-success">
	<strong>
		{{$vid->votes()->where('should_keep', true)->count()}} @lang('No lo borres')
	</strong>
</p>
<!--Maybe i should pass the vote model like i do with the vid model instead-->
<p class="text-danger">
	<strong>
		{{$vid->votes()->where('should_keep', false)->count()}} @lang('BORRALO')
	</strong>
</p>
