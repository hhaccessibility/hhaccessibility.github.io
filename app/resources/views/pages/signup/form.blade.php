@extends('layouts.default')
@section('content')

<div class="sign-up">
	<div class="text-center header">
		<h1>Sign Up</h1>
		<p>Already have an AccessLocator account? <a href="/signin">Sign in</a></p>
	</div>
	<div class="signup-form">
		<form method="post" action="/signup">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))
			<input type="hidden" name="after_signup_redirect" value="{{ isset($after_signup_redirect) ? $after_signup_redirect : '' }}">
			<div class="row">
				<div class="col-xs-12">
					<input class="clean" name="email" placeholder="Email" value="{{ old('email') }}">
				</div>
				<div class="col-xs-6">
					<input class="clean" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}">
				</div>
				<div class="col-xs-6">
					<input class="clean" name="first_name" placeholder="First Name" value="{{ old('first_name') }}">
				</div>
				<div class="col-xs-12">
					<input class="clean" name="password" type="password" placeholder="Password" value="{{ old('password') }}">
				</div>
				<div class="col-xs-12">
					<input class="clean" name="password_confirm" type="password" placeholder="Type your password again" value="{{ old('password_confirm') }}">
				</div>
				<div class="col-xs-12">
					{!! app('captcha')->display(); !!}
				</div>
			</div>
			<div>
				<input class="clean" type="submit" value="Sign Up">
			</div>
		</form>
	</div>
	<div class="text-center agreements">
		<p>By signing up you agree to our <a href="/terms-of-use">Terms of Use</a>,
		<a href="/privacy-policy">privacy policy</a>, and to receive newsletters &amp; updates.</p>
	</div>
</div>

@stop