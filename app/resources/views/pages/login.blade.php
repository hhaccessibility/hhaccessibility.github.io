@extends('layouts.default')
@section('content')
<h1>Log In</h1>

<p>
	New to AccessLocator? 	<a href="/signup">Sign up</a>
</p>

<div class="row">
	<div class="col-lg-6 col-xs-12">
		<div class="login-form">
			{{ Form::open(array('url' => 'login')) }}

			<!-- if there are login errors, show them here -->
			@if (!$errors->isEmpty())
			<ul>
				@if ($errors->first('username'))
				<li>{{ $errors->first('username') }}</li>
				@endif
				@if ($errors->first('password'))
				<li>{{ $errors->first('password') }}</li>
				@endif
			</ul>
			@endif

			<div class="row">
				<div class="col-xs-4">
					{{ Form::label('email', 'Email') }}
				</div>
				<div class="col-xs-8">
					{{ Form::text('email', Input::old('email'), array('placeholder' => 'test')) }}
				</div>
			</div>

			<div class="row">
				<div class="col-xs-4">
					{{ Form::label('password', 'Password') }}
				</div>
				<div class="col-xs-8">
					{{ Form::password('password') }}
				</div>
			</div>

			<p>{{ Form::submit('Log In') }}</p>
			{{ Form::close() }}

			<div class="form-group">
				<input type="checkbox">
				<label class="control-label">Remember Me</label>
				
				<a class="pull-right" href="">Forgot Password?</a>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-xs-12">
		<div><a href="/fbauth"><i class="fa-lg fa fa-facebook-square"></i></a></div>
		<div class="fa-lg fa fa-twitter"></div>
		<div class="fa-lg fa fa-google-plus"></div>
		<div class="fa-lg fa fa-linkedin"></div>
	</div>
</div>

@stop