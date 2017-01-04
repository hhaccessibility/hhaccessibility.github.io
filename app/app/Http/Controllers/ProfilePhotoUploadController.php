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
	
	private static function getUploadDirectory()
	{
		return realpath(public_path() . '/../resources/assets/user_profile_images').'\\';
	}
	
	private static function getProfilePhotoPath()
	{
		$user = BaseUser::getDbUser();
		return ProfilePhotoUploadController::getUploadDirectory() . 'user_' . $user->id . '.jpg';
	}
	
	/**
	Returns a string with the original file extension but named with the specified user id.
	
	For example, getFileNameFromOriginalName(3, 'Bobby.png') returns 'user_3.png'.
	*/
	private static function getFileNameFromOriginalName(int $user_id, string $originalFilename)
	{
		$dotIndex = strrpos($originalFilename, '.');
		if ( $dotIndex === FALSE )
			throw new Exception("No extension found");

		$extension = strtolower(substr($originalFilename, $dotIndex + 1));
		if ( strlen($extension) > 4 )
			throw new Exception("Extension is too long");
		
		return 'user_' . $user_id . '.' . $extension;
	}

	/**
	Sends the current user's photo to the client
	*/
	public function photo()
	{
        if (BaseUser::isSignedIn())
        {
			$file_path = ProfilePhotoUploadController::getProfilePhotoPath();
			return response()->file($file_path);
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
			$photo = Input::file('profile_photo');
			$destinationPath = ProfilePhotoUploadController::getUploadDirectory();

			$user = BaseUser::getDbUser();
			$filename = ProfilePhotoUploadController::getFileNameFromOriginalName($user->id, $request->file('profile_photo')->getClientOriginalName());
 			$full_path = $destinationPath . 'user_' . $user->id . '.jpg';

			$temp_filename = $_FILES['profile_photo']['tmp_name'];
			$content = file_get_contents($temp_filename);
			$new_image = imagecreatefromstring($content);
			
			$width = imagesx($new_image);
			$height = imagesy($new_image);
			$newDimensions = ProfilePhotoUploadController::getPhotoDimensionsFromUploadDimensions($width, $height);
			$scaled_result = imagescale ( $new_image , $newDimensions['width'], $newDimensions['height']);
			imagejpeg($scaled_result, $full_path);
			
            return view('pages.profile.photo_upload_success');
		}
        else
        {
            return redirect()->intended('signin');
        }
	}

}