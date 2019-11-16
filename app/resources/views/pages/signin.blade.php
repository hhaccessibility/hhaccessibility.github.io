@extends('layouts.default', ['body_class' => 'nav-profile'])
@section('content')
	<script src="/js/jquery-3.1.1.js"></script>
	<script src="/js/password_caps.js" type="text/javascript"></script>
<div class="sign-in">
	<div class="text-center header">
		<h1>Sign In</h1>
		<p>New to AccessLocator? <a href="/signup">Sign Up</a></p>
	</div>
	<div class="signin-form">
		@if ( isset($message) && $message )
		<div class="message text-center alert alert-info">
			{{$message}}
		</div>
		@endif
		@if ( isset($confirmmessage) && $confirmmessage )
		<strong>{{ $confirmmessage }}</strong>
		@else
		<form method="post" action="/signin">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))
			<input type="hidden" name="after_signin_redirect" value="{{ isset($after_signin_redirect) ? $after_signin_redirect : '' }}">
			<div class="row">
				<div class="col-xs-12">
					<input type="email"
						class="clean" name="email"
						placeholder="Email" value="{{ old('email', $email) }}">
				</div>
				<div class="col-xs-12">
					<input class="clean" name="password" type="password" placeholder="Password" value="{{ old('password') }}">
					<div id="capsLock" class="capsLock text-right alert alert-warning">CapsLock is on!
						<span class="fa fa-exclamation-circle"></span>
					</div>
				</div>
				
				<div class="col-xs-12">
					<div class="remember-password">
						<a class="pull-right" href="/user/password-recovery"> Account Recovery </a>
					</div>
				</div>
										
			</div>
			<div>
				<input class="clean" type="submit" value="Sign in">
			</div>
		</form>
		@endif
	</div> 
</div>

@stop