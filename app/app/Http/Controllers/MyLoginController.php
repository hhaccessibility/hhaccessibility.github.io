<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class MyLoginController extends Controller {

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
		$username = $request->input('username');
		$matching_user = User::where('username', '=', $username)->first();
        if ($matching_user && 
		Hash::check($request->input('password'), $matching_user->password_hash))
        {
            return redirect()->intended('profile');
        }
		else
		{
			return redirect()->intended('login');
		}
    }

}