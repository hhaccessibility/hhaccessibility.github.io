<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\Location;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LocationSearchController extends Controller {

	public function byKeywords(Request $request)
	{
		$keywords = Input::get('keywords');
		BaseUser::setAddress(Input::get('address'));
		$keywordsArray = explode(' ', $keywords);
		$locationsQuery = Location::query();
		foreach ($keywordsArray as $keyword)
		{
			$locationsQuery->orWhere('name', 'LIKE', '%'.$keyword.'%');			
			$locationsQuery->orWhere('address', 'LIKE', '%'.$keyword.'%');			
		}
	
		$locations = $locationsQuery->distinct()->orderBy('name')->get();
		return view('pages.locations_by_keywords',
			['locations' => $locations, 'keywords' => $keywords]);
	}

    public function by_tag($location_tag_id)
    {
		$location_tag = LocationTag::find($location_tag_id);
		$locations = $location_tag->locations()->orderBy('name')->get();
		
		return view('pages.locations_by_tag', ['locations' => $locations, 'location_tag' => $location_tag]);
    }
	
	public function index()
	{
		return view('pages.location_search');
	}

}