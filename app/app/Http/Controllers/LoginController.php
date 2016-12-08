<?php namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller {
	
	public function showForm(Request $request)
	{
		$email = '';
		if ( !empty(Input::get('email')) ) {
			$email = trim(Input::get('email'));
		}
		return view('pages.login', ['email' => $email]);
	}

    /**
     * Handle an authentication attempt.
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
		$validation_rules = array(
			'email'            => 'required|email',
			'password'         => 'required',
		);
		$validator = Validator::make(Input::all(), $validation_rules);
		
		if (!$validator->fails())
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
				$validator->errors()->add('password', 'Invalid email and password combination');
			}
		}
		
		return Redirect::to('login')->withErrors($validator)->withInput();	
    }

	public function logout(Request $request)
	{
		$request->session()->forget('username');
		return redirect()->intended('/');
	}
}