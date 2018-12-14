@extends('layouts.default')
@section('content')
<div class="location-groups">
	<h1>
		<a class="internal-dashboard-link" href="/dashboard">
			<em class="fa fa-gears"></em>
		</a>
		- Location Groups</h1>
	<p>{{ count($location_groups) }} total location groups</p>
	<div class="data">
		<div class="row headings">
			<div class="col-xs-6">Name</div>
			<div class="col-xs-6">Number of Locations</div>
		</div>
		@foreach ($location_groups as $location_group)
		<div class="row">
			<div class="col-xs-6">{{ $location_group->name }}</div>
			<div class="col-xs-6"><a href="location/search?location_group_id={{ $location_group->id }}&amp;view=map">{{ $location_group->num_locations }}</a></div>
		</div>
		@endforeach
	</div>
</div>
@stop