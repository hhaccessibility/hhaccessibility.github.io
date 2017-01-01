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
                      <h3>Parking</h3>
                      <div>
                            <p>
                                <div class="checkbox">
                                  <label><input type="checkbox" value="">Select All</label>
                                </div>
                            </p>
                           <hr/>
                
                
                            <p>
                                    <div class="checkbox">
                                      <label><input type="checkbox" value="">Curb cuts/free of tripping hazards</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                      <label><input type="checkbox" value="">Crosswalk/Markings leading to entrance</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                      <label><input type="checkbox" value="">Good lighting in parking lot</label>
                                    </div>
                            </p>
                
                      </div>
                     
    
                    <h3>Entrance</h3>
                      <div>
                            <p>
                                <div class="checkbox">
                                  <label><input type="checkbox" value="">Select All</label>
                                </div>
                            </p>
                           <hr/>
    
                          <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Good lighting at entrance </label>
                                    </div>
                            </p>
                      
                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Ramped or level grade entrance</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Button Access/Automatic doors</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Wide doorway to allow wheelchair access</label>
                                    </div>
                            </p>
                    
                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Seating available in lobby/waiting area</label>
                                    </div>
                            </p>
                    </div>
    
    
    
    
    
    
                      <h3>Public Areas</h3>
                      <div>
                            <p>
                                <div class="checkbox">
                                  <label><input type="checkbox" value="">Select All</label>
                                </div>
                            </p>
                           <hr/>




                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Wide aisles to allow wheelchair/walker access</label>
                                    </div>
                            </p>
                        
                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">No thresholds/free of tripping hazards</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Non-slip flooring</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Auditory cues in elevator</label>
                                    </div>
                            </p>


                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Elevator + Accessible elevator</label>
                                    </div>
                            </p>
                                
                            <p>
                                                        
                                    <dl>
                                      <dd> 
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Wheelchair accessible counters</label>
                                           </div> 
                                      </dd>
                                        
                                         
                                      <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value="">Under counter clearance</label>
                                           </div>                                        
                                      
                                      </dd>
                                       <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value="">Low counter height</label>
                                           </div>                                        
                                      
                                      </dd>

                                    </dl>

                            </p>
                   

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Braille signage</label>
                                    </div>
                            </p>


                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Large print or symbol based signs available</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Quiet sections available</label>
                                    </div>
                            </p>
                      </div>





                      <h3>Bathrooms</h3>
                      <div>
                          
                            <p>
                                <div class="checkbox">
                                  <label><input type="checkbox" value="">Select All</label>
                                </div>
                            </p>
                           <hr/>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Family/Unisex bathrooms available</label>
                                    </div>
                            </p>




                            <p>
                                                        
                                    <dl>
                                      <dd> 
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Wheelchair accessible doorway/entrance</label>
                                           </div> 
                                      </dd>
                                        
                                         
                                      <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value="">Adequate width</label>
                                           </div>                                        
                                      
                                      </dd>
                                       <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Door opener</label>
                                           </div>                                        
                                      
                                      </dd>

                                    </dl>

                            </p>



                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Stall large enough to turn wheelchair/walker</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Grab bars mounted beside toilet</label>
                                    </div>
                            </p>



                            <p>
                                                        
                                    <dl>
                                      <dd> 
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Accessible sink</label>
                                           </div> 
                                      </dd>
                                        
                                         
                                      <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Clearance under the sink</label>
                                           </div>                                        
                                      
                                      </dd>
                                       <dd class="indentedItem">
                                            <div class="checkbox">
                                               <label><input type="checkbox" value=""> Accessible counter/sink height</label>
                                           </div>                                        
                                      
                                      </dd>

                                    </dl>

                            </p>


                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Taps with lever handles or motion sensors</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Sensor or one hand activated soap dispenser </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Large weight capacity change table in private area</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Tall toilet(s) </label>
                                    </div>
                            </p>
                      </div>


                      <h3>Amenities</h3>
                      <div>
                            <p>
                                <div class="checkbox">
                                  <label><input type="checkbox" value="">Select All</label>
                                </div>
                            </p>
                           <hr/>


                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Picture based symbols for information and signage </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Braille signage/information available </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Large print written information available </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Removable chairs/seating </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Chairs with armrests available </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Fidget toys available </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Free WiFi </label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Closed captioning on display screens</label>
                                    </div>
                            </p>

                            <p>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Assisted Listening Devices available </label>
                                    </div>
                            </p>
                      </div>


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