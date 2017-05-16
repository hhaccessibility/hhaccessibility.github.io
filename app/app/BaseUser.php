<?php

namespace App;
use Session;
use Exception;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\AuthenticationException;

class BaseUser
{
	/**
	* Checks that the username and password matches a user record in the database.
	*
	* @return true if and only if the parameters match a user record.
	*/
	public static function authenticate(string $email, string $password)
	{
		$matching_user = User::where('email', '=', $email)->first();
		if (!$matching_user)
		{
			return false;
		}
		return Hash::check($password, $matching_user->password_hash);
	}

	/**
	* Checks if the visitor associated with the current request is authenticated.
	*/
	public static function isSignedIn()
	{
		return Session::has('email');
	}

	public static function isCompleteAccessibilityProfile()
	{
		return BaseUser::isSignedIn();
	}

	/**
	* Returns an instance of App/User associated with the current session
	*/
	public static function getDbUser() {
		if (!BaseUser::isSignedIn())
		{
			throw new AuthenticationException(
			'Unable to get database user because you are not signed in');
		}

		$email = Session::get('email');
		$user = User::where('email', $email) -> first();
		if ( !$user )
		{
			BaseUser::signout();
			throw new AuthenticationException(
			'Signed in but not able to get user from database');
		}

		return $user;
	}

	public static function getSearchRadius()
	{
		$default_search_radius = 1;
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			if ( $user->search_radius_km === null )
			{
				return $default_search_radius;
			}
			else
			{
				return $user->search_radius_km;
			}
		}
		else if ( Session::has('search_radius_km') )
		{
			return Session::get('search_radius_km');
		}
		else
		{
			return $default_search_radius;
		}
	}

	public static function setAddress(string $address)
	{
		$address = trim($address);
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			$user->location_search_text = $address;
			$user->save();
		}
		else
		{
			Session::put('location_search_text', $address);
		}
	}

	public static function setLongitude(float $longitude)
	{
		$longitude = trim($longitude);
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			$user->longitude = $longitude;
			$user->save();
		}
		else
		{
			Session::put('longitude', $longitude);
		}
	}

	public static function setLatitude(float $latitude)
	{
		$latitude = trim($latitude);
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			$user->latitude = $latitude;
			$user->save();
		}
		else
		{
			Session::put('latitude', $latitude);
		}
	}

	public static function getDefaultAddress()
	{
		return 'Windsor, Ontario, Canada';
	}

	public static function getAddress()
	{
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			return $user->location_search_text;
		}
		else if ( Session::has('location_search_text') )
		{
			return Session::get('location_search_text');
		}
		else
		{
			return '';
		}
	}
	
	public static function isInternal()
	{
		if ( !BaseUser::isSignedIn() )
			return false;

		$user = BaseUser::getDbUser();
		return $user->hasRole(Role::INTERNAL);
	}

	/**
	Calculates distance that a direct flight would take across the spherical
	surface of Earth.

	@param long1 is longitude in degrees
	@param lat1 is latitude in degrees
	@param long2 is longitude in degrees
	@param lat2 is latitude in degrees
	*/
	public static function getDirectDistance($long1, $lat1,
		$long2, $lat2)
	{
		$earthRadius = 6371; // km
		$long1 = deg2rad($long1);
		$lat1 = deg2rad($lat1);
		$long2 = deg2rad($long2);
		$lat2 = deg2rad($lat2);
		$deltaLong = $long2 - $long1;
		$deltaLat = $lat2 - $lat1;
		$a = sin($deltaLat / 2) * sin($deltaLat / 2) +
			cos($lat1) * cos($lat2) *
			sin($deltaLong / 2) * sin($deltaLong / 2);
		$c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
		return $earthRadius * $c;
	}

	public static function getLatitude()
	{
		if ( BaseUser::isSignedIn() )
		{
			$user = BaseUser::getDbUser();
			if (is_numeric($user->latitude))
				return $user->latitude;
		}
		else if ( Session::has('latitude') ) {
			return Session::get('latitude');
		}

		// Latitude of Windsor city hall
		return 42.3174246;
	}

	public static function getLongitude()
	{
		if ( BaseUser::isSignedIn() )
		{
			$user = BaseUser::getDbUser();
			if (is_numeric($user->longitude))
				return $user->longitude;
		}
		else if ( Session::has('longitude') )
		{
			return Session::get('longitude');
		}

		// Longitude of Windsor city hall
		return -83.0374028;
	}

	/**
	Returns distance in kilometers
	*/
	public function getDistanceTo($longitude, $latitude)
	{
		return BaseUser::getDirectDistance(
			$longitude, $latitude,
			$this->getLongitude(), $this->getLatitude());
	}

	public static function signIn($email)
	{
		Session::put('email',$email);
		//copying non-default session data to user table.
		if ( Session::has('location_search_text') && Session::get('location_search_text') !== ''
		&& Session::has('longitude') && Session::has('latitude') )
		{
			//copy the address, longitude  and  latitude to the user table
			BaseUser::setAddress(Session::get('location_search_text'));
			BaseUser::setLatitude(Session::get('latitude'));
			BaseUser::setLongitude(Session::get('longitude'));
			
			// Remove the data from the session.
			Session::forget('longitude');
			Session::forget('latitude');
			Session::forget('location_search_text');
		}
	}

	public static function signout()
	{
		Session::forget(['email']);
	}

	/**
	* send verification email to user's email
	*
	*/
	public static function sendVerificationEmail($user)
	{
		$body = "Final step...\r\n".
				"Confirm your email address to complete your Accesslocator account. It's easy — just copy and past link below into your browser. \r\n".
				config('app.url')."/signup/confirmEmail/".$user->email."/".$user->email_verification_token;
		$headers = "From: noreply@accesslocator.com\r\n";
		$headers .= "Content-Type: text/plain; charset=\"utf-8\"\r\n";
		mail($user->email, "Confirm your Accesslocator account, ".$user->first_name." ".$user->last_name, $body, $headers);
	}
	/**
	* to verify user's email verification code
	*
	*/
	public static function confirmEmail(string $email,string $confirmCode)
	{
		$matching_user = User::where('email', '=', $email)->first();

		if ($matching_user && $matching_user->email_verification_token === $confirmCode)
		{
			$matching_user->email_verification_time = new DateTime();
			$matching_user->save();
			return true;
		}

		return false;
	}

	// check if user's email verificated while loging in
	public static function checkEmail(string $email) {
		$matching_user = User::where('email', '=', $email)->first();
		return !is_null($matching_user->email_verification_time);
	}
	// set search radius
	public static function setSearchRadius(int $distance) {
		if ( BaseUser::isSignedIn() )
		{
			$user = BaseUser::getDbUser();
			if ( $distance > 0) {
				$user-> search_radius_km = $distance;
				$user->save();
			}
		}
		else if ( Session::has('search_radius_km') )
		{
			Session::put('search_radius_km', $distance);
		}

	}
}

?>
