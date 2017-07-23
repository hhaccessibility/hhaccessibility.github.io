@extends('layouts.default')
@section('content')
<div class="user-report">
	<h1><a class="internal-dashboard-link" href="/dashboard">
			<em class="fa fa-gears"></em>
		</a>
<a href="/users">Users</a> - {{ $user->email }}</a></h1>

	<div class="row">
		<div class="col-sm-6 col-xs-12">
			<div class="box">
				<h2>Basic Information</h2>
				<ul>
					<li>First Name: <span>{{ $user->first_name }}</span></li>
					<li>Last Name: <span>{{ $user->last_name }}</span></li>
					<li>Home City: <span>{{ $user->home_city }}</span></li>
					<li>Home Region: <span>{{ $user->home_region }}</span></li>
					<li>Home Country: <span>{{ $home_country_name }}</span></li>
					<li>Search text: <span>{{ $user->location_search_text }}</span></li>
					<li>Email verification: <span>{{ $user->email_verification_time }}</span></li>
				</ul>
			</div>
		</div>
		<div class="col-sm-6 col-xs-12">
			<div class="box">
				<h2>Ratings</h2>
				<ul>
					<li>Comments: <span>{{ $num_comments }}</span></li>
					<li>Rating Submissions: <span>{{ $num_rating_submissions }}</span></li>
					<li>Question Answers: <span>{{ $num_answers }}</span></li>
					<li>At least partly rated locations: <span>{{ $num_rated_locations }}</span></li>
					<li>Created locations: <span>{{ $num_created_locations }}</span></li>
				</ul>
			</div>
		</div>
	</div>
</div>
@stop