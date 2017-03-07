<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class HomeController extends Controller {

    /**
     * Either shows profile view or redirects browser to sign in.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
		$address_value = BaseUser::getAddress();
		if (trim($address_value) === trim(BaseUser::getDefaultAddress()))
		{
			$address_value = '';
		}
        return view('pages.home', [
			'location_tags' => LocationTag::orderBy('name')->get(),
			'is_authenticated' => BaseUser::isSignedIn(),
			'address_default' => BaseUser::getDefaultAddress(),
			'address_value' => $address_value,
			'google_map_api_key' => config('app.google_map_api_key'),
			'turn_off_maps'      => config('app.turn_off_maps')
			]);
    }

    /*Save the map data that was sent from the home page ajax call*/
    public function saveUserLocation(Request $request)
    {
		$latitude = floatval(Input::get('latitude'));
		$longitude = floatval(Input::get('longitude'));
		$address = Input::get('address');

		BaseUser::setAddress($address);
		BaseUser::setLongitude($longitude);
		BaseUser::setLatitude($latitude);

		return response()->json(['success' => true]);
    }
}