@extends('layouts.default')
@section('content')
<h1>Sign In</h1>
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
			{{ Form::label('username', 'Username') }}
		</div>
		<div class="col-xs-8">
			{{ Form::text('username', Input::old('username'), array('placeholder' => 'test')) }}
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

	<p>{{ Form::submit('Submit!') }}</p>
	{{ Form::close() }}

	<div class="form-group">
		<label class="control-label">Remember my login</label>
		<input type="checkbox">
	</div>
</div>

<h4>Don't have an account?</h4>

<div class="btn">
	<a href="/signup">Sign up</a>
</div>

<div class="btn btn-block btn-social btn-large btn-facebook btn-foursquare">hi</div>
<div class="btn btn-block btn-social btn-large btn-google btn-foursquare">hi</div>
<div class="btn btn-block btn-social btn-large btn-twitter btn-foursquare">hi</div>

@stop