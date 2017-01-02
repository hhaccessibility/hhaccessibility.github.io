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

	private static function getPhotoDimensionsFromUploadDimensions($width, $height)
	{
		$aspect_ratio = ($width * 1.0) / $height;
		$max_height = 200;
		$max_width = 730;
		
		if ( $aspect_ratio > ($max_width * 1.0) / $max_height )
		{
			if ( $width > $max_width )
			{
				// scale down the image to have a 730 pixel width.
				$width = $max_width;
				$height = round($max_width / $aspect_ratio);
			}
		}
		else if ( $height > $max_height )
		{
			$height = $max_height;
			$width = $max_height * $aspect_ratio;
		}
		return ['width' => $width, 'height' => $height];
	}
	
	public function post(Request $request)
	{
        if (BaseUser::isSignedIn())
        {
			$validation_rules = array(
				'profile_photo' => 'required|file|image|dimensions:min_width=32,min_height=32',
			);
			$validator = Validator::make(Input::all(), $validation_rules);
			if ($validator->fails())
			{
				return Redirect::to('profile-photo-upload')->withErrors($validator)->withInput();	
			}
			
			// FIXME: get dimensions from actual uploaded image.
			$width = 1;
			$height = 1;
			$newDimensions = ProfilePhotoUploadController::getPhotoDimensionsFromUploadDimensions($width, $height);
			// FIXME: Write the file to 'resources/assets/user_profile_images'.
			
            return view('pages.profile.photo_upload_success');
		}
        else
        {
            return redirect()->intended('signin');
        }
	}

}