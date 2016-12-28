@extends('layouts.default')
@section('content')
<div class="sign-up">
	<h1>Sign Up</h1>
	<div class="text-center success">
		<strong>Your account has been created.</strong>
		<p>You can <a href="/signin?email={{ $email }}">sign in</a> with it now.</p>
	</div>
</div>

@stop