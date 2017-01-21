<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\Location;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LocationSearchController extends Controller {

	/**
	search handles both search by location tag and by keywords.
	*/
	public function search(Request $request)
	{
		if ( Input::has('address') )
			BaseUser::setAddress(Input::get('address'));

		$locationsQuery = Location::query();
		$location_tag = '';
		$keywords = [];
		if ( Input::has('keywords') )
		{
			$keywords = Input::get('keywords');
			$keywordsArray = explode(' ', $keywords);
			foreach ($keywordsArray as $keyword)
			{
				$locationsQuery->orWhere('name', 'LIKE', '%' . $keyword . '%');
				$locationsQuery->orWhere('address', 'LIKE', '%' . $keyword . '%');
			}
			$locations = $locationsQuery->distinct()->orderBy('name')->get();
		}
		else
		{
			$location_tag = LocationTag::find(Input::get('location_tag_id'));
			$locations = $location_tag->locations()->orderBy('name')->get();
		}

		return view('pages.location_search.search',
			['locations' => $locations, 'keywords' => $keywords, 'location_tag' => $location_tag]);
	}
}