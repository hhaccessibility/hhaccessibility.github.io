<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\Country;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use DB;

class HomeAddressController extends \Illuminate\Routing\Controller
{
    public function getRegions()
    {
        return DB::table('region')->get();
    }

    public static function getView($validator = null)
    {
        $user = BaseUser::getDbUser();
        $countries = Country::orderBy('name')->get();
        $enabled_countries = Country::whereIn('id', function ($query) {
            $query->select('country_id')
            ->from(with(new Region)->getTable());
        })->get(['id']);
        $enabled_country_ids = [];
        foreach ($enabled_countries as $country_id) {
            $enabled_country_ids []= $country_id->id;
        }
        $view_data = [
            'user' => $user,
            'countries' => $countries,
            'enabled_country_ids' => $enabled_country_ids
            ];

        if ($validator === null) {
            return view('pages.profile.home_address_form', $view_data);
        } else {
            return view('pages.profile.home_address_form', $view_data)->withErrors($validator);
        }
    }

    public function save(Request $request)
    {
        if (!BaseUser::isSignedIn()) {
            return redirect()->intended('signin');
        }

        $validation_rules = array(
            'country_id'           => 'integer|exists:country,id',
            'home_city'            => 'max:255',
            'home_region'          => 'max:255'
        );
        $validator = Validator::make(Input::all(), $validation_rules);

        if ($validator->fails()) {
            return HomeAddressController::getView($validator);
        }

        $user = BaseUser::getDbUser();
        if ($request->home_country_id === '') {
            $user->home_country_id = null;
        } else {
            $user->home_country_id = intval($request->home_country_id);
        }

        $user->home_region = trim($request->home_region);
        $user->home_city = $request->home_city;
        $user->save();

        return redirect()->intended('/profile');
    }

    /**
     * Either shows home address form view or redirects browser to sign in.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (BaseUser::isSignedIn()) {
            return HomeAddressController::getView();
        } else {
            return redirect()->intended('signin');
        }
    }
}
