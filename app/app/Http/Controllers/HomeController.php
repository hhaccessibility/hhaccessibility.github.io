<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

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
			'address_value' => $address_value
			]);
    }

}