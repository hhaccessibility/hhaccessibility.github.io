@extends('layouts.default')
@section('content')
<div class="profile row">
	<div class="col-sm-4 col-xs-12">
		<a class="upload-photo" href="">
		Upload Your Photo
		</a>
	</div>
	<div class="col-sm-8 col-xs-12">
		<h1>{{ $user->first_name.' '.$user->last_name }}</h1>

		<h2>Personal</h2>
		<div class="box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="first_name">First Name</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="first_name" name="first_name" value="{{ $user->first_name }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="last_name">Last Name</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="last_name" name="last_name" value="{{ $user->last_name }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="email">Email</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="email" name="email" value="{{ $user->email }}">
					</div>
				</div>
			</div>
		</div>
		
		<h2>Home</h2>
		<div class="box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="country">Country</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="country" name="country" value="">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="province">State/Province</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="province" name="province" value="">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="city">City</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="city" name="city" value="">
					</div>
				</div>
			</div>
		</div>
		
		<h2>Search Location</h2>
		<div class="box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="location">Location</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="location" name="location_search_text" value="{{ $user->location_search_text }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="distance">Distance</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="distance" name="search_radius_km" value="{{ $user->search_radius_km }}">
					</div>
				</div>
			</div>
		</div>
		
		<h2>Accessibility</h2>
		<div class="box">
		</div>
		
		<h2>Reward Program</h2>
		<div class="box">
			<div class="row">
			</div>
		</div>
	</div>
</div>

@stop