@extends('layouts.default', ['body_class' => 'nav-profile profile-names'])
@section('content')
<div class="profile-names-page">
	<h1>{{ $user->first_name.' '.$user->last_name }}</h1>
	<form method="post" action="/profile/names">
		{!! csrf_field() !!}
		@include('pages.validation_messages', array('errors'=>$errors))
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
						<input class="form-control" id="email" name="email" type="email" required readonly value="{{ $user->email }}">
					</div>
				</div>
			</div>
			<div class="form-group password">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label>Password</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<a class="btn btn-default" href="/user/change-password">Change password</a>
					</div>
				</div>
			</div>
		</div>

		<div class="footer text-right">
			<a href="/profile"><button class="btn btn-lg btn-primary save-button">Cancel</button></a>
			<button type="submit" class="btn btn-lg btn-primary save-button">Save</button>
	   </div>
	</form>
</div>

@stop