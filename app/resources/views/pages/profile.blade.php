@extends('layouts.default')
@include('includes.collapsible')
@section('content')

<div class="profile row">
	<div class="col-sm-12 col-xs-12">
       
        <!--upload picture-->
        <div class="col-sm-3 col-xs-12" id="uploadDiv">
            
            <a class="upload-photo" href="">
            Upload Your Photo
            </a>
        
        </div>
    
        <!--/upload picture-->
        
        <!--profile form-->
    <div class="col-sm-9 col-xs-12">
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
						<a class="btn btn-default">Change password</a>
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
						<label for="location">Location</label>
					</div>
					<div class="col-sm-8 col-xs-7">
						<input class="form-control" id="location" name="location_search_text"
							value="{{ $user->location_search_text }}">
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
		
		<h2>Accessibility Interests</h2>
		<div class="box">
         
            <p>
                <div class="checkbox">
                        <label><input type="checkbox" value="">Screen Reader</label>
                </div>
            </p>
            <div id="accordion">
        
            @foreach ($question_categories as $category)
        
                <h3>{{ $category->name }}</h3>
                <div>
                    <p>
                        <div class="checkbox">
                          <label><input type="checkbox" value="">Select All</label>
                        </div>
                    </p>
                   <hr>
                
                    @foreach ($category->questions()->get() as $question)
                    <p>
                        <div class="checkbox">
                          <label><input type="checkbox" value="">{!! $question->question_html !!}</label>
                        </div>
                    </p>
                    @endforeach
                </div>
                
            @endforeach

            </div>
            
		</div>
		
		<h2>Reward Program</h2>
		<div class="box">
			
            <div class="row">
                <p>&nbsp;&nbsp;My Rewards: </p>
                
                <div class="col-sm-8 col-xs-7">
                    <div class="row">
                         &nbsp;<i class="fa fa-trophy fa-3x"></i> <i class="fa fa-trophy fa-3x"></i>

                    </div>
                    <!--row -->
                    <div class="row">
                        &nbsp;&nbsp;
                    </div>
                    
                    <div class="row">
                        &nbsp;&nbsp;<a class="btn btn-default">My Reviewed Blogs</a>
                    </div>
				</div>
                
			</div>
		</div>

        <div class="row">
            <div class="col-sm-9 col-xs-8"> </div>
            <div class="col-sm-3 col-xs-4">
                
                <button class="btn  btn-lg btn-block btn-primary saveButton"><i class="fa fa-check"></i> Save Profile</button>
                <br/>
            </div>
       </div>
     </div>
        <!--/profile form-->
		


	</div>
</div>

@stop