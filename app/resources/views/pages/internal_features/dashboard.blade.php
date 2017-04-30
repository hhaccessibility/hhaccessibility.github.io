@extends('layouts.default')
@section('content')
<div class="internal-dashboard">
	<h1>Internal Dashboard</h1>
	
	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<h2>General</h2>
			<ul>
				<li><a href="/users">{{ $num_users }} user(s)</a></li>
				<li>{{ $num_locations }} locations</li>
				<li>{{ $num_location_groups }} location groups</li>
				<li>{{ $num_data_sources }} data sources</li>
			</ul>
		</div>
		<div class="col-sm-6 col-xs-12">
			<h2>Ratings</h2>
			<ul>
				<li>{{ $num_rating_submissions }} total rating submissions</li>
				<li>{{ $num_comments }} total rating comments</li>
				<li>{{ $num_rated_locations }} at least partly rated locations</li>
			</ul>
		</div>
</div>
@stop