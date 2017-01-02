<?php namespace App\Http\Controllers;

use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ProfilePhotoUploadController extends Controller {

    public function index(Request $request)
    {
        if (BaseUser::isSignedIn())
        {
            return view('pages.profile.photo_upload');
        }
        else
        {
            return redirect()->intended('signin');
        }
    }
	
	public function post(Request $request)
	{
        if (BaseUser::isSignedIn())
        {
			$validation_rules = array(
				'profile_photo' => 'required|file|image|dimensions:min_width=10,min_height=10',
			);
			$validator = Validator::make(Input::all(), $validation_rules);
			if ($validator->fails())
			{
				return Redirect::to('profile-photo-upload')->withErrors($validator)->withInput();	
			}
			
			// FIXME: Write the file.
			
            return view('pages.profile.photo_upload_success');
		}
        else
        {
            return redirect()->intended('signin');
        }
	}

}