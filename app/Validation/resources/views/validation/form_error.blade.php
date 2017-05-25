<p class="bg-danger">
	@foreach($errors->get($form_field) as $message)
		@lang($message) <br>
	@endforeach
</p>
