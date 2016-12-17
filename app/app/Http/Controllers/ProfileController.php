<?php namespace App\Http\Controllers;

use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    /**
     * Either shows profile view or redirects browser to sign in.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
		
        if (BaseUser::isSignedIn())
        {
			$user = BaseUser::getDbUser();
            return view('pages.profile', ['user' => $user]);
        }
        else
        {
            return redirect()->intended('signin');
        }
    }

}