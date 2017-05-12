<h3>@lang(VID_STATE_STRING[$state])</h3>

@can('aprove', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action='{{$name}}' method='POST'>
		{{csrf_field()}}
		{{method_field('PUT')}}
		<input type='submit' class="btn btn-success" value="@lang('Aprobar')" name='Aprobar'>
	</form>
@endcan
@cannot('aprove', Components\Vids\Models\Vid::class)
	<!--TODO: FILL THIS-->
@endcannot


@can('delete', Components\Vids\Models\Vid::class)
	<form class="buttonform-inline" action='{{$name}}' method='POST'>
		{{csrf_field()}}
		{{method_field('DELETE')}}
		<input type='submit' class="btn btn-danger" value="@lang('Borrar')" name='DELETE'>
	</form>
@endcan
@cannot('delete', Components\Vids\Models\Vid::class)
	<!--TODO: FILL THIS-->
@endcannot
