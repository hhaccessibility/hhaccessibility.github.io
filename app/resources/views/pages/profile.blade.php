@extends('layouts.default')
@section('content')
<div class="profile">
	<h1>Profile</h1>

	<p>Welcome, {{ $user->first_name }}.</p>
	<div>
	Name: {{ $user->first_name.' '.$user->last_name }}
	</div>
	<div>
	Age:
	</div>
	<div>
	Points:
	</div>
</div>

@stop