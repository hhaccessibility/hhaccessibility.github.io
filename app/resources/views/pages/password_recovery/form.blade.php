@extends('layouts.default')
@section('content')

<div class="password-recovery">
	<h1>Account Recovery</h1>
	
	<div class="form-box">
		<form method="post" action="/user/password-recovery">
			{!! csrf_field() !!}
			@include('pages.validation_messages', array('errors'=>$errors))					
			<input class="clean" type="email" name="email" placeholder="Your Email">
			<div>
				{!! app('captcha')->display(); !!}
			</div>
			<input class="clean" type="submit" value="Send Account Recovery Email">
		</form>
	</div>
</div>

@stop