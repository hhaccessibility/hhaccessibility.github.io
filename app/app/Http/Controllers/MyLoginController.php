<?php namespace App\Http\Controllers;

use User;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class MyLoginController extends Controller {

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        if ($request->input('username') === 'test' && $request->input('password') === 'password' )
        {
            return redirect()->intended('profile');
        }
		else
		{
			return redirect()->intended('login');
		}
    }

}