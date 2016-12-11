<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller {

    /**
     * Either shows profile view or redirects browser to login.
	 *
     * @return Response
     */
    public function index(Request $request)
    {
        return view('pages.home', [
			'location_tags' => LocationTag::orderBy('name')->get(),
			'is_authenticated' => BaseUser::isLoggedIn()]);
    }

}