@extends('layouts.default')
@section('head-content')
  <link href="/css/jquery/jquery-ui.css" rel="stylesheet" type="text/css"> 
  <script src="/css/jquery/external/jquery/jquery.js"></script>
  <script src="/css/jquery/jquery-ui.js"></script>
  <script src="/js/profile.js">
  </script>
@stop
@section('content')

<div class="profile row">
	<div class="col-md-3 col-sm-4 col-xs-12">
 		<a class="upload-photo" href="/profile-photo-upload">
 		Upload Your Photo
 		</a>
 	</div>
    <div class="col-md-9 col-sm-8 col-xs-12">
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
            <div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="password">Password</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<a class="btn btn-default" href="/change-password">Change password</a>
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
						<select class="form-control" id="country" name="country">
							<option value="">-- Select Country --</option>
							@foreach ($countries as $country)
								@if ($user->home_country_id === $country->id)
								<option value="{{ $country->id }}" selected>{{ $country->name }}</option>
								@else
								<option value="{{ $country->id }}">{{ $country->name }}</option>
								@endif
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="province">State/Province</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="province" name="province" value="{{ $user->home_region }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="home_city">City</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="home_city" name="home_city" value="{{ $user->home_city }}">
					</div>
				</div>
			</div>
		</div>
		
		<h2>Search Location</h2>
		<div class="box">
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="location_search_text">Location</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="location_search_text" name="location_search_text"
							value="{{ $user->location_search_text }}"
							placeholder="{{ $address_default }}">
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<label for="distance">Distance (km)</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="distance" name="search_radius_km" value="{{ $user->search_radius_km }}">
					</div>
				</div>
			</div>
		</div>

		<h2>Accessibility Interests</h2>
		<div class="box accesibility-interests">
            <p>
                <div class="checkbox">
                        <label>
						@if ( $user->uses_screen_reader )
						<input type="checkbox" name="uses_screen_reader" checked>
						@else
						<input type="checkbox" name="uses_screen_reader">
						@endif
						Screen Reader</label>
                </div>
            </p>
            <div id="accordion">
        
            @foreach ($question_categories as $category)
        
                <h3>{{ $category->name }}</h3>
                <div class="category">
					<div class="checkbox">
					  <label><input class="select-all" type="checkbox" value="">Select All</label>
					</div>
					<div class="questions">
						@foreach ($category->questions()->get() as $question)
							<div class="checkbox">
							  <label><input type="checkbox" value="">{!! $question->question_html !!}</label>
							</div>
						@endforeach
					</div>
                </div>
                
            @endforeach

            </div>
            
		</div>
		
		<h2>Reward Program</h2>
		<div class="box rewards">
			<p>My Rewards:</p>
			<div>
				 <i class="fa fa-trophy fa-3x"></i> <i class="fa fa-trophy fa-3x"></i>
			</div>
			<div>
				<a class="btn btn-default">My Reviews</a>
			</div>
		</div>

        <div class="text-right">
            <button class="btn btn-lg btn-primary save-button"><i class="fa fa-check"></i> Save Profile</button>
       </div>
     </div>
        <!--/profile form-->

	</div>
</div>

@stop