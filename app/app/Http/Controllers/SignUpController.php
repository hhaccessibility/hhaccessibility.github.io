<?php namespace App\Http\Controllers;

use App\User;
use App\UserRole;
use App\BaseUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class SignUpController extends Controller {

    public function showForm(Request $request)
    {
		return view('pages.signup.form');
    }
	
    public function createUser(Request $request)
    {
		$validation_rules = array(
			'first_name'            => 'required|max:255',
			'last_name'             => 'required|max:255',
			'email'                 => 'required|email|unique:user|max:255',  
			'password'              => 'required',
			'password_confirm'      => 'required|same:password',
			'g-recaptcha-response'  => 'required|captcha'
		);
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return Redirect::to('signup')->withErrors($validator)->withInput();			
		}
		else
		{
			$email = $request->input('email');
			$newUser = new User;
			$newUser->email = $email;
			$newUser->first_name = $request->input('first_name');
			$newUser->last_name = $request->input('last_name');
			$newUser->password_hash = User::generateSaltedHash($request->input('password'));
			$newUser->location_search_text = BaseUser::getAddress();
			$newUser->email_verification_token = str_random(60); //generate email verification token
			BaseUser::sendVerificationEmail($newUser);
			$newUser->save();
			
			$newUserRole = new UserRole;
			$newUserRole->role_id = 2;
			$newUserRole->user_id = $newUser->id;
			$newUserRole->save();
			return view('pages.signup.success',['email' => $email,'confirmmessage'=>'A verification code has been sent to your email '.$email.'. Check your email to confirm.']);
		}
		return view('pages.signup.form');
    }
	public function confirmEmail($user_email,$email_verification_token) {
		$email = $user_email;
		$confirmCode = $email_verification_token;
		if (BaseUser::confirmEmail($email,$confirmCode)) {
			return view('pages.signup.success',['email' => $email,'confirmmessage'=>'Your email has been confirmed.']);
		}

		return Redirect::to('signup')->withErrors('Ooophs, there is a problem with your confirm code! you can try it again.');

	}
}