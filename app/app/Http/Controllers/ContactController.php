<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class ContactController extends Controller {

    public function index(Request $request)
    {
		return view('pages.contact.form');
    }

	public function sendMessage(Request $request)
	{
		$validation_rules = array(
			'email'            => 'required|email',
			'comment'         => 'required',
			'g-recaptcha-response' => 'required|captcha'
		);
		$validator = Validator::make(Input::all(), $validation_rules);
		if ($validator->fails())
		{
			return Redirect::to('contact')->withErrors($validator)->withInput();			
		}
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= 'From: <webmaster@app.accesslocator.com>' . "\r\n";
		$email = $request->input('email');
		$message = $request->input('comment');
		$to_email = "accesslocator@gmail.com";
		$subject = "you have a message";
		$body = "Hello,You have a message from: ".$email." \r\n ".$message;
		$body = str_replace("\n.", "\n..", $body);
		mail($to_email,$subject, $body,$headers);
		return view('pages.contact.message-sent');
	}
}