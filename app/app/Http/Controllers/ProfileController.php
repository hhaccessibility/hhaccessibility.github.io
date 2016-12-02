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
        $email = $request->session()->get('email');
		$user = User::where('email',$email) -> first();
		
        if ($email)
        {
            return view('pages.profile', ['user' => $user]);
        }
        else
        {
            return redirect()->intended('login');
        }
    }

}