<?php namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use App\BaseUser;
use Exception;
use Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Hybrid_Auth;
use Hybrid_Endpoint;

class SocialAuthController extends Controller {

	public function getSocialLogin($providerName) {
		$providerName = trim($providerName);
		if(!in_array($providerName, array('Facebook', 'Google'))) {
			return view('pages.signin')->withErrors('Ooophs, there is a problem! you can try it again.')->with('email','');
		}
		try {

			$auth = new Hybrid_Auth(config_path('hybridauth.php'));
			$provider = $auth->authenticate($providerName);
			$profile = $provider->getUserProfile();
			$provider->logout();
			$this->createNewUserWithGoogle($profile);
			return view('pages.signup.success', ['email' => $profile->email]);

		} catch (Exception $e) {
			// 	echo "Ooophs, we got an error: " . $e->getMessage();
		   //  	echo " Error code: " . $e->getCode();
    		return view('pages.signin')->withErrors('Ooophs, there is a problem! you can try it again.')->with('email','');
		}
		
	}

	private function createNewUserWithGoogle($profile) {
		$email = $profile->email;
		$isExist = User::where("email", "=", $email)->count();
		if($isExist === 0) {
			$newUser = new User;
			$newUser->email = $email;
			$newUser->first_name = $profile->firstName;
			$newUser->last_name = $profile->lastName;
			$newUser->password_hash = User::generateSaltedHash('Hhaccess.2017');
			$newUser->location_search_text = BaseUser::getAddress();
			$newUser->save();

			$newUserRole = new UserRole;
			$newUserRole->role_id = 2;
			$newUserRole->user_id = $newUser->id;
			$newUserRole->save();
		}
    }
    // first time login need user's permission to access their account informaiton
    public function getSocialLoginCallBack() {

    	try {
    		Hybrid_Endpoint::process();
    	}
    	catch (Exception $e) {
    		echo "Ooophs, we got an error: " . $e->getMessage();
    		echo " Error code: " . $e->getCode();
    		return Redirect::to('signin');
    	}

    }
    public function getFacebookLogin($auth=NULL)
    {
		if ($auth === 'auth')
		{
			try
			{
				Hibrid_Endpoint::process();
			}
			catch (Exception $e)
			{
				return Redirect::to('fbauth');
			}
			return;
		}
		
		$oauth = new Hybrid_Auth(app_path(). '/config/fb_auth.php');
		$provider = $oauth->authenticate('Facebook');
		$profile = $provider->getUserProfile();
		var_dump($profile).'<a href="signout">Sign Out</a>';
    }

	public function getLoggedOut()
	{
		$fauth = new Hybrid_auth(app_path().'/config/fb_auth.php');
		$fauth->logoutAllProviders();
		return View::make('login');
	}
}
