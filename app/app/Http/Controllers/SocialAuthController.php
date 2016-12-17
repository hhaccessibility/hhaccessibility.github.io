<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
$original_path = dirname(__FILE__);
$num_dirs_up = 3;
$path = $original_path;
for ($i=0;$i < $num_dirs_up; $i ++ ) {
	$index = strrpos($path, '\\');
	$path = substr($path, 0, $index);
}
$auth_dir = $path.'\vendor\hybridauth\hybridauth\hybridauth\Hybrid';
set_include_path($auth_dir);
echo '<br>auth_dir = '.$auth_dir;
include $auth_dir.'\Auth.php';
echo '<br>original_path = '.$original_path;
restore_include_path();

class SocialAuthController extends Controller {

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
		return var_dump($profile).'<a href="signout">Sign Out</a>';
    }

	public function getLoggedOut()
	{
		$fauth=new Hybrid_auth(app_path().'/config/fb_auth.php');
		$fauth->logoutAllProviders();
		return View::make('login');
	}
}