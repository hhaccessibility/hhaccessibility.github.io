@extends('layouts.default')
@section('content')
<div class="change-password">
	<h1>Change Password</h1>

	<div class="password-change-form">
		<form method="post" action="/password-recovery/reset-password">
			<input type="hidden" name="user_email" value="{{ $user_email }}">
			<input type="hidden" name="password_recovery_token" value="{{ $password_recovery_token }}">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))
			<div>
				<input class="clean" type="password" name="new_password"
					placeholder="New Password"
					value="">
			</div>
			<div>
				<input class="clean" type="password" name="password_confirm"
					placeholder="Password Confirmation"
					value="">
			</div>
			<div>
				<input type="submit" class="clean" value="Update Password">
			</div>
		</form>
	</div>
</div>
@stop
