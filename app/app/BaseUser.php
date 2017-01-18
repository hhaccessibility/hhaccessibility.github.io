<?php

namespace App;
use Session;
use Exception;
use Illuminate\Support\Facades\Hash;

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
			throw new Exception('Unable to get database user because you are not signed in');
		}
		
		$email = Session::get('email');
		$user = User::where('email', $email) -> first();
		
		return $user;
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
	
	public static function getDefaultAddress()
	{
		return 'Windsor, Ontario, Canada';
	}
	
	public static function getAddress()
	{
		if (BaseUser::isSignedIn())
		{
			$user = BaseUser::getDbUser();
			if( !$user )
			{
				throw new Exception('Signed in but unable to find user in database');
			}
			return $user->location_search_text;
		}
		else
		{
			return Session::get('location_search_text');
		}
	}
	
	/**
	@param long1 is longitude in degrees
	@param lat1 is latitude in degrees
	@param long2 is longitude in degrees
	@param lat2 is latitude in degrees
	*/
	public static function getDirectDistance(float $long1, float $lat1,
		float $long2, float $lat2)
	{
		$earthRadius = 6371;
		$long1 = deg2rad($long1);
		$lat1 = deg2rad($lat1);
		$long2 = deg2rad($long2);
		$lat2 = deg2rad($lat2);
		$deltaLong = $long2 - $long1;
		$deltaLat = $lat2 - $lat1;
		$a = sin($deltaLat / 2) * sin($deltaLat / 2) +
			cos($lat1) * cos($long2) *
			sin($deltaLong / 2) * sin($deltaLong / 2);
		$c = 2 * atan2( sqrt( $a ), sqrt( 1 - $a ) );
		return $earthRadius * $c;
	}
	
	public function getLatitude()
	{
		if ( BaseUser::isSignedIn() )
		{
			$user = $this->getDbUser();
			if (is_numeric($user->latitude))
				return $user->latitude;
		}
		
		// Latitude of Windsor city hall
		return 42.3174246;
	}
	
	public function getLongitude()
	{
		if ( BaseUser::isSignedIn() )
		{
			$user = $this->getDbUser();
			if (is_numeric($user->longitude))
				return $user->longitude;
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
	}
	
	public static function signout()
	{
		Session::forget(['email']);
	}
}

?>