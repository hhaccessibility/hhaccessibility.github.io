<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller {

    /**
     * Either shows profile view or redirects browser to login.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
        $username = $request->session()->get('username');
		$user = User::where('username',$username) -> first();
		
        if ($username)
        {
            return view('pages.profile', ['user' => $user]);
        }
        else
        {
            return redirect()->intended('login');
        }
    }

}