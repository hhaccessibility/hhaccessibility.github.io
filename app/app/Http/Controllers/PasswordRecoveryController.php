<?php namespace App\Http\Controllers;

use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class PasswordRecoveryController extends Controller {

	public function form(Request $request)
	{
		return view('pages.password_recovery');
	}

}