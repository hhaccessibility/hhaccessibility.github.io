<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SignUpController extends Controller {

    public function showForm(Request $request)
    {
		return view('pages.signup');
    }
	
    public function createUser(Request $request)
    {
		$newUser = new User;
		$newUser->email = $request->input('email');
		$newUser->first_name = $request->input('first_name');
		$newUser->last_name = $request->input('last_name');
		$newUser->password_hash = User::generateSaltedHash($request->input('password'));
		$newUser->save();
		
		return view('pages.signup');
    }
}