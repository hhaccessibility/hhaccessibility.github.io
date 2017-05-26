<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\User;
use Session;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class PasswordRecoveryController extends Controller {

	public function form(Request $request)
	{
		return view('pages.password_recovery.form');
	}

	public function sendEmail(Request $request)
	{
		$validation_rules = array(
			'email'                 => 'required|email',  
			'g-recaptcha-response'  => 'required|captcha'
		);
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return Redirect::to('password-recovery')->withErrors($validator)->withInput();;
		}
		$email = trim(Input::get('email'));
		// find user with matching email address.
		$matching_user = User::where('email', '=', $email)->first();
		if ( !$matching_user ) {
			return view('pages.password_recovery.unmatched_email');
		}
		BaseUser::sendPasswordRecoveryEmail($matching_user);
		return view('pages.password_recovery.email_sent');
	}

	public function passworkRecover($user_email, $password_recovery_token) {
		$matching_user = User::where('email', '=', $user_email)->first();

		if ( !$matching_user ) {
			return view('pages.password_recovery.unmatched_email');
		}

		if ( $matching_user->password_recovery_token == $password_recovery_token ) {
			Session::put('PasswordRecovery',$matching_user->email);
			return view('pages.password_recovery.reset_password');
		}

		return view('pages.password_recovery.unmatched_token');
	}
	public function resetPassword(Request $request) {
		if ( Session::has('PasswordRecovery') ) {
			$validation_rules = array(
				'new_password'         => 'required',
				'password_confirm'     => 'required|same:new_password'
			);
			$validator = Validator::make(Input::all(), $validation_rules);
			$failing = $validator->fails();
			if ($failing)
			{
				return Redirect::back()->withErrors($validator)->withInput();
			}
			else
			{
				$user = User::where('email', '=', Session::get('PasswordRecovery'))->first();
				$user->password_hash = User::generateSaltedHash($request->input('new_password'));
				$user->save();
				Session::forget('PasswordRecovery');
				return view('pages.password_recovery.reset_password_success');
			}
		}
		else
		{
			return Redirect::to('/');
		}
	}
}
?>
