<?php namespace App\Http\Controllers;

use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller {

    /**
     * Either shows password change form or redirects to sign in if not signed in.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
        if (BaseUser::isSignedIn())
        {
			$user = BaseUser::getDbUser();
            
            return view('pages.profile.change_password', ['user' => $user]);
        }
        else
        {
            return redirect()->intended('signin');
        }
    }

}