@extends('vids.base')

@section('body')
	@section('navbar')
		<div class="navbar-left">
			@include('changelocale')
		</div>
	@endsection

	<div class="container">
		<div class="row">
			<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<div class="panel panel-default">

					<div class="panel-heading">
						<h1>@lang('Iniciar Sesión')</h1>
					</div>


					<div class="panel-body">
						<form method="POST" action="{{ route('login') }}" class="form-horizontal">
							
							{{ csrf_field() }}
							<div class="form-group">
								<input type="email" class="form-control" name="email" placeholder="@lang('Correo')">
							</div>
							<div class="form-group">
								<input type="password" class="form-control" name="password" placeholder="@lang('Contraseña')">
							</div>
								
							<input type="submit" class="btn btn-primary" name="submit_login" value="@lang('Ingresar')">
						
						</form>
					</div>

				</div>
			</div>
		</div>
	</div>

@endsection
