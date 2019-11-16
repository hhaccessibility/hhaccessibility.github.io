@extends('layouts.default')
@section('content')

<div class="password-recovery">
	<h1>Forgotten Password</h1>
	
	<div class="form-box">
		Check your email in a few minutes.
		You'll receive an email with instructions on how to recover your account.
		If you do not receive an email after 5 minutes, you can resend the email
		<form method="post" action="/user/password-recovery">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))					
			<input class="clean" type="email" name="email" placeholder="Your Email">
			<div>
				{!! app('captcha')->display(); !!}
			</div>
			<input class="clean" type="submit" value="Send Password Recovery Email">
		</form>
	</div>
</div>

@stop