@extends('layouts.default')
@section('content')

<div class="sign-up">
	<div class="text-center header">
		<h1>Sign Up</h1>
		<p>Already have an Access Locator account? <a href="/login">Log in</a></p>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="signup-form">
				<form method="post" action="/signup">
					{!! csrf_field() !!}
					<div class="row">
						<div class="col-xs-12">
							<input class="clean" name="email" placeholder="Email">
						</div>
						<div class="col-xs-6">
							<input class="clean" name="last_name" placeholder="Last Name">
						</div>
						<div class="col-xs-6">
							<input class="clean" name="first_name" placeholder="First Name">
						</div>
						<div class="col-xs-12">
							<input class="clean" type="password" placeholder="Password">
						</div>
						<div class="col-xs-12">
							<input class="clean" type="password" placeholder="Type your password again">
						</div>
					</div>
					<div>
						<input class="clean" type="submit" value="Sign Up">
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-6">
			<div class="social-media-logins">
				Or sign in using your social media account
				
				<a class="facebook" href="">
					<i class="fa-lg fa fa-facebook"></i>
					<div class="pull-right">Sign in with facebook</div>
				</a>
				<a class="google-plus" href="">
					<i class="fa-lg fa fa-google-plus"></i>
					<div class="pull-right">Sign in with Google</div>
				</a>
			</div>
		</div>
	</div>
	<div class="text-center agreements">
		<p>By signing up you agree to our <a href="/terms-of-use">Terms of Use</a>,
		<a href="/privacy-policy">privacy policy</a>, and to receive newsletters &amp; updates.</p>
	</div>
</div>

@stop