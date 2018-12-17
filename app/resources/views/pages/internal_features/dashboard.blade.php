@extends('layouts.default')
@section('content')
<div class="internal-dashboard">
	<h1>Internal Dashboard</h1>
	
	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<div class="box">
				<h2>General</h2>
				<ul>
					<li><a href="/users"><span>{{ $num_users }}</span> users</a></li>
					<li>{{ $num_internal_users }}</span> internal users</a></li>
					<li><span>{{ $num_users_using_screen_readers }}</span> user(s) use screen readers</li>
					<li><span>{{ $num_locations }}</span> locations (<a href="/map-location-visualizer">visualizer</a>)</li>
					<li><span>{{ $num_user_created_locations }}</span> locations created by users</li>
					<li><span>{{ $num_no_address_locations }}</span> locations with blank addresses</li>
					<li><span>{{ $num_no_phone_locations }}</span> locations with blank phone numbers</li>
					<li>
						<a href="/location-groups">
							<span>{{ $num_location_groups }}</span> location groups
						</a>
					</li>
					<li><span>{{ $num_data_sources }}</span> data sources</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12">
			<div class="box">
				<h2>Ratings</h2>
				<ul>
					<li><span>{{ $num_rating_submissions }}</span> total rating submissions</li>
					<li><span>{{ $num_comments }}</span> total rating comments</li>
					<li><span>{{ $num_rated_locations }}</span> at least partly rated locations</li>
				</ul>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12">
			<div class="box">
				<h2>Location Categories</h2>
				<ul>
					<li>Uncategorized Locations - <a href="/location-tagging">{{ $num_untagged_locations }}</a></li>
					@foreach ($location_tags as $location_tag)
					<li>{{ $location_tag->name }} - {{ $location_tag->num_locations }}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>
@stop