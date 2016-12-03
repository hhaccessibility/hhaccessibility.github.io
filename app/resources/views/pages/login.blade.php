@extends('layouts.default')
@section('content')

<div class="log-in">
	<div class="text-center header">
		<h1>Log In</h1>
		<p>New to Access Locator? <a href="/signup">Sign Up </a></p>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="login-form">
				<form method="post" action="/login">
                    {!! csrf_field() !!}
					<div class="row">
						<div class="col-xs-12">
							<input class="clean" name="email" placeholder="Email">
						</div>						
						<div class="col-xs-12">
							<input class="clean" name="password" type="password" placeholder="Password">
						</div>
						
                        <div class="wrapper" style="height:30px;width:100%">
                            <div class="checkbox" style="margin-left:17px;height:100%;width:55%;float:left">
                                <label>
                                    
                                    <input type="checkbox"> Remember Me
                                                   
                                </label>
                            </div>
                        
                              <div style="float:left;height:100%;margin-top:10px"> Forgot Password? </div>  
                        </div>
                                                
					</div>
					<div>
						<input class="clean" type="submit" value="Log in">
					</div>
				</form>
			</div> 
		</div>
		<div class="col-md-6">
			<div class="social-media-logins">
				Or sign in using your social media account
				
				<a class="facebook" href="">
					<i class="fa-lg fa fa-facebook"></i>
					<div class="pull-right">Sign in with facebook</div>
				</a>
				<a class="google-plus" href="">
					<i class="fa-lg fa fa-google-plus"></i>
					<div class="pull-right">Sign in with Google</div>
				</a>
			</div>
		</div>
	</div>
</div>

@stop