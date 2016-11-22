<?php namespace App\Http\Controllers;

use App\BuildingTag;
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
        return view('pages.home', ['building_tags' => BuildingTag::orderBy('name')->get()]);
    }

}