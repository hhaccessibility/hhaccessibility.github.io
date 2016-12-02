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
		$email = $request->input('email');
		$matching_user = User::where('email', '=', $email)->first();
        if ($matching_user && 
		Hash::check($request->input('password'), $matching_user->password_hash))
        {
            $request->session()->put('email', $email);
            return redirect()->intended('profile');
        }
		else
		{
			return redirect()->intended('login');
		}
    }

	public function logout(Request $request)
	{
		$request->session()->forget('username');
		return redirect()->intended('/');
	}
}