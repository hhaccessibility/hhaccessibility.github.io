@extends('layouts.default')
@section('content')

<div class="password-recovery">
	<h1>Password Recovery</h1>
	<p>Under development</p>
	
	<div class="form-box">
		<form method="post" action="/password-recovery">
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