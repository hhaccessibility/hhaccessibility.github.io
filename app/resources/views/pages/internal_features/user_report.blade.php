@extends('layouts.default')
@section('content')
<div class="location-management">
	<h1><a href="/users">Users</a> - {{ $user->email }}</a></h1>

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<h2>Basic Information</h2>
			<ul>
				<li>First Name: {{ $user->first_name }}</li>
				<li>Last Name: {{ $user->last_name }}</li>
				<li>Home City: {{ $user->home_city }}</li>
				<li>Home Region: {{ $user->home_region }}</li>
				<li>Home Country: {{ $home_country_name }}</li>
			</ul>
		</div>
		<div class="col-sm-6 col-xs-12">
			<h2>Ratings</h2>
			<ul>
				<li>Comments: {{ $num_comments }}</li>
				<li>Rating Submissions: {{ $num_rating_submissions }}</li>
				<li>Question Answers: {{ $num_answers }}</li>
				<li>At least partly rated locations: {{ $num_rated_locations }}</li>
			</ul>
		</div>
	</div>
</div>
@stop