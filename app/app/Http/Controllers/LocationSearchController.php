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

/**
For efficiency's sake, we want to remove any locations that are outside 
the latitude and longitude range we're interested in.

The calculations are explained at:
http://janmatuschek.de/LatitudeLongitudeBoundingCoordinates
*/
function filterLatitudeAndLongitude($locationsQuery)
{
	$searchRadius = BaseUser::getSearchRadius(); // km
	$earthRadius = 6371; // km
	// If search radius is larger than the Earth's radius, 
	// we can't filter down at all here.
	if ( $searchRadius >= $earthRadius * 0.99 )
	{
		return $locationsQuery;
	}
	$lat = BaseUser::getLatitude();
	$r = $searchRadius / $earthRadius;
	$latDelta = rad2deg($r);
	$maxLat = $lat + $latDelta;
	$minLat = $lat - $latDelta;
	$locationsQuery = $locationsQuery->where('latitude', '<=', $maxLat)
		->where('latitude', '>=', $minLat);

	// If the latitude goes over the poles, 
	// longitude is unrestricted.
	// Imagine the circle overlapping the north or south pole and
	// you'll see why all longitudes are covered.
	if ( $maxLat >= 90 || $minLat <= -90 || abs($lat) === 90 )
		return $locationsQuery;

	$latR = deg2rad($lat);
	$asinInput = sin($r) / cos($latR);
	if ( abs($asinInput) > 1 ) {
		return $locationsQuery;
	}
	$lonDelta = rad2deg(asin( $asinInput ));
	$lon = BaseUser::getLongitude();
	$maxLon = $lon + $lonDelta;
	$minLon = $lon - $lonDelta;
	$locationsQuery = $locationsQuery->where('longitude', '<=', $maxLon)
		->where('longitude', '>=', $minLon);

	return $locationsQuery;
}

function getSortedLocations($locations, $view, $order_by_field_name)
{
	if ( $view === 'table' ) {
		if ( $order_by_field_name === 'name' )
		{
			$locations = $locations->orderBy('name');
		}
	}
	// filter down to a bounding latitude and longitude range.
	$locations = filterLatitudeAndLongitude($locations);
	
	$locations = $locations->get();
	// get() doesn't return an array so let's make one.
	$loc_array = [];
	foreach ($locations as $loc)
	{
		$loc_array []= $loc;
	}
	$locations = $loc_array;

	updateDistances($locations);
	if ( $view === 'table' ) {
		updateRatings($locations);
		if ( $order_by_field_name === 'distance' )
			usort($locations, 'App\Http\Controllers\compareByDistance');
		else if ( $order_by_field_name === 'rating' )
			usort($locations, 'App\Http\Controllers\compareByRating');

	}
	return $locations;
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

		if ( Input::has('location_tag_id') && is_numeric(Input::get('location_tag_id')) )
		{
			$location_tag_id = Input::get('location_tag_id');
			$location_tag = LocationTag::find($location_tag_id);
			$location_tag_name = $location_tag->name;
			$locations = $location_tag->locations();
		}
		else if ( Input::has('keywords') || isset($_GET['keywords']) )
		{
			$keywords = Input::get('keywords');
			if ( !Input::has('keywords') )
			{
				$keywords = ' ';
			}
			$keywordsArray = explode(' ', $keywords);
			foreach ($keywordsArray as $keyword)
			{
				$locationsQuery->where('name', 'LIKE', '%' . $keyword . '%');
			}
			$locations = $locationsQuery->distinct();
		}
		else
		{
			\App::abort(422, 'Either keywords or location_tag_id must be specified');
		}
		$locations = getSortedLocations($locations, $view, $order_by_field_name);

		// Remove locations that are too far away.
		$filtered_locations = [];
		$search_radius = BaseUser::getSearchRadius();
		foreach ($locations as $location) {
			if ( $location->distance <= $search_radius ) {
				$filtered_locations []= $location;
			}
		}
		$locations = $filtered_locations;

		$url_factory = new URLFactory([
			'keywords' => $keywords,
			'order_by' => $order_by_field_name,
			'location_tag_id' => $location_tag_id,
			'view' => $view
		]);

		return view('pages.location_search.search',
			[
				'locations'          => $locations,
				'keywords'           => $keywords,
				'location_tag_name'  => $location_tag_name,
				'url_factory'        => $url_factory,
				'view'               => $view,
				'google_map_api_key' => config('app.google_map_api_key'),
				'turn_off_maps'      => config('app.turn_off_maps'),
				'order_by'           => $order_by_field_name,
				'search_radius'      => $search_radius
			]);
	}
}
