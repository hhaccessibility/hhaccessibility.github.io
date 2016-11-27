@extends('layouts.default')
@section('content')
<div class="profile">
	<h1>Profile</h1>

	<p>Welcome, {{ $user }}.</p>
	<div>
	Name:
	</div>
	<div>
	Age:
	</div>
	<div>
	Points:
	</div>
</div>

@stop