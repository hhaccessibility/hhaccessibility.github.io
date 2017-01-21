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
		$keywords = '';
		$location_tag_name = '';
		$location_tag_id = '';
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
			$location_tag_id = Input::get('location_tag_id');
			$location_tag = LocationTag::find($location_tag_id);
			$location_tag_name = $location_tag->name;
			$locations = $location_tag->locations()->orderBy('name')->get();
		}
		$url = '/location-search?keywords='.$keywords.'&amp;location_tag_id='.$location_tag_id;
		
		$view = 'table';
		
		if ( Input::has('view') && ( Input::get('view') === 'map' || Input::get('view') === 'table' ) )
			$view = Input::get('view');

		return view('pages.location_search.search',
			[
				'locations' => $locations, 'keywords' => $keywords,
				'location_tag_name' => $location_tag_name,
				'url' => $url,
				'view' => $view,
				'google_map_api_key' => config('app.google_map_api_key'),
				'turn_off_maps' => config('app.turn_off_maps')
			]);
	}
}