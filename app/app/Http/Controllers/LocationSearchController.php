<?php namespace App\Http\Controllers;

use App\LocationTag;
use App\Location;
use App\BaseUser;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

function compareByDistance($location1, $location2)
{
	if ( $location1->distance < $location2->distance )
		return -1;
	else if ( $location1->distance === $location2->distance ) 
		return 0;
	else
		return 1;
}

function compareByRating($location1, $location2)
{
	if ( $location1->rating < $location2->rating )
		return -1;
	else if ( $location1->rating === $location2->rating )
		return 0;
	else
		return 1;
}

function updateDistances(array $locations)
{
	$user = new BaseUser();
	$longitude = $user->getLongitude();
	$latitude = $user->getLatitude();
	foreach ($locations as $location)
	{
		$location->distance = BaseUser::getDirectDistance(
			$longitude, $latitude, $location->longitude, $location->latitude);
	}
}

function updateRatings(array $locations)
{
	foreach ($locations as $location)
	{
		$location->rating = $location->getAccessibilityRating('universal');
	}
}

function getSortedLocations($locations, $view, $order_by_field_name)
{		
	if ( $view === 'table' ) {

		if ( $order_by_field_name === 'name' )
		{
			$locations = $locations->orderBy('name');
		}
		$locations = $locations->get();
		// get() doesn't return an array so let's make one.
		$loc_array = [];
		foreach ($locations as $loc)
		{
			$loc_array []= $loc; 
		}
		$locations = $loc_array;
		
		updateRatings($locations);
		updateDistances($locations);
		if ( $order_by_field_name === 'distance' )
			usort($locations, 'App\Http\Controllers\compareByDistance');
		else if ( $order_by_field_name === 'rating' )
			usort($locations, 'App\Http\Controllers\compareByRating');
		
		return $locations;
	}
	return $locations->get();
}

/**
Used in location search views to get URL's for various tweaks to the state.
*/
class URLFactory
{
	public function __construct($params) {
		$this->params = $params;
	}
	
	private static function getURLFromParameters($params)
	{
		$url = '/location-search?';
		$needs_ampersand = false;
		foreach ($params as $param_name => $param_value) {
			if ( $needs_ampersand ) {
				$url .= '&amp;';
			}
			$url .= $param_name . '=' . rawurlencode($param_value);
			$needs_ampersand = true;
		}
		return $url;
	}
	
	private function cloneParams()
	{
		return array_flip(array_flip($this->params));
	}
	
	public function createURL()
	{
		return $this->params;
	}
	
	public function createURLForOrderByField($field_name)
	{
		$params = $this->cloneParams();
		$params['order_by'] = $field_name;
		return URLFactory::getURLFromParameters($params);
	}
	
	public function createURLForView($view)
	{
		$params = $this->cloneParams();
		$params['view'] = $view;
		return URLFactory::getURLFromParameters($params);
	}
}

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
		$order_by_field_name = 'name';
		if ( Input::has('order_by') )
		{
			$field_name = Input::get('order_by');
			if ( in_array($field_name, ['name', 'rating', 'distance']) )
			{
				$order_by_field_name = $field_name;
			}
		}
		$view = 'table';
		
		if ( Input::has('view') && ( Input::get('view') === 'map' || Input::get('view') === 'table' ) )
			$view = Input::get('view');
		
		if ( Input::has('keywords') )
		{
			$keywords = Input::get('keywords');
			$keywordsArray = explode(' ', $keywords);
			foreach ($keywordsArray as $keyword)
			{
				$locationsQuery->orWhere('name', 'LIKE', '%' . $keyword . '%');
				$locationsQuery->orWhere('address', 'LIKE', '%' . $keyword . '%');
			}
			$locations = $locationsQuery->distinct();
		}
		else if ( Input::has('location_tag_id') && is_numeric(Input::get('location_tag_id')) )
		{
			$location_tag_id = Input::get('location_tag_id');
			$location_tag = LocationTag::find($location_tag_id);
			$location_tag_name = $location_tag->name;
			$locations = $location_tag->locations();
		}
		else
		{
			throw new Exception('Either keywords or location_tag_id must be specified');
		}
		$locations = getSortedLocations($locations, $view, $order_by_field_name);

		$url_factory = new URLFactory([
			'keywords' => $keywords,
			'order_by' => $order_by_field_name,
			'location_tag_id' => $location_tag_id,
			'view' => $view
		]);

		return view('pages.location_search.search',
			[
				'locations' => $locations, 'keywords' => $keywords,
				'location_tag_name' => $location_tag_name,
				'url_factory' => $url_factory,
				'view' => $view,
				'google_map_api_key' => config('app.google_map_api_key'),
				'turn_off_maps' => config('app.turn_off_maps'),
				'order_by' => $order_by_field_name
			]);
	}
}