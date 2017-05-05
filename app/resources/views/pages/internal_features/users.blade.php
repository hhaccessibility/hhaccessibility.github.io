@extends('layouts.default')
@section('content')
<div class="users">
	<h1><a class="internal-dashboard-link" href="/dashboard">
			<em class="fa fa-gears"></em>
		</a> Users</h1>
	<p>{{ count($users) }} total user(s)</p>
	<div class="data">
		<div class="row headings">
			<div class="col-xs-2">Email</div>
			<div class="col-xs-2">First Name</div>
			<div class="col-xs-2">Last Name</div>
			<div class="col-xs-2">Home City</div>
			<div class="col-xs-1">Home Zipcode</div>
			<div class="col-xs-2">Region</div>
			<div class="col-xs-1">Search Radius</div>
		</div>
		@foreach ($users as $user)
		<div class="row">
			<div class="col-xs-2"><a href="/user-report/{{ $user->id }}">{{ $user->email }}</a></div>
			<div class="col-xs-2">{{ $user->first_name }}</div>
			<div class="col-xs-2">{{ $user->last_name }}</div>
			<div class="col-xs-2">{{ $user->home_city }}</div>
			<div class="col-xs-1">{{ $user->home_zipcode }}</div>
			<div class="col-xs-2">{{ $user->home_region }}</div>
			<div class="col-xs-1">{{ sprintf('%.1', $user->search_radius_km) }}</div>
		</div>
		@endforeach
	</div>
</div>
@stop