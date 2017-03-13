@extends('layouts.default')
@section('content')
<div class="sign-up">
	<h1>Sign Up</h1>
	<div class="text-center success">
		<strong>{{ $confirmmessage }}</strong>
		@if ( $can_sign_in )
		<p>You can <a href="/signin?email={{ $email }}">sign in</a> with it now.</p>
		@endif
	</div>
</div>

@stop