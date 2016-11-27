<?php namespace App\Http\Controllers;

use App\LocationTag;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class LocationSearchController extends Controller {

    public function by_tag($location_tag_id)
    {
		$location_tag = LocationTag::find($location_tag_id);
		$locations = $location_tag->locations()->orderBy('name')->get();
		
		return view('pages.locations', ['locations' => $locations, 'location_tag' => $location_tag]);
    }
	
	public function index()
	{
		return view('pages.location_search');
	}

}