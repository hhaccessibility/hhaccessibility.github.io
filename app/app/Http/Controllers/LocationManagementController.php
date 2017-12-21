<?php namespace App\Http\Controllers;

use App\Location;
use App\BaseUser;
use App\User;
use App\Libraries\StringMatcher;
use App\Libraries\StringMatcherRepository;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use DB;

class LocationManagementController extends Controller {
	private static function sanitizeDirectorySeparators($path)
	{
		return str_replace('\\', DIRECTORY_SEPARATOR, $path);
	}
	
	public function getLocationSuggestionsForLocationName($location_name)
	{
		$data = [];
		$string_repo = new StringMatcherRepository(
			dirname(dirname($_SERVER['DOCUMENT_ROOT'])).self::sanitizeDirectorySeparators('\\importers\\utils\\data\\location_tags\\location_tags.json'));
		
		$item_ids = $string_repo->getItemIds();
		foreach ($item_ids as $id)
		{
			$data[$id]['is_matched'] = $string_repo->appliesTo($location_name, $id);
		}
		return [
			'location_tags' => $data,
			'location_group' => $this->getLocationGroupForLocationName($location_name)
			];
	}
	
	private function getLocationGroupForLocationName($location_name)
	{
		$data = [];
		$string_repo = new StringMatcherRepository(
			dirname(dirname($_SERVER['DOCUMENT_ROOT'])).self::sanitizeDirectorySeparators('\\importers\\utils\\data\\location_groups\\location_groups.json'));
		
		$item_ids = $string_repo->getItemIds();
		$matched_group = null;
		foreach ($item_ids as $id)
		{
			if ( $string_repo->appliesTo($location_name, $id) )
			{
				$matched_group = $id;
			}
		}
		return $matched_group;
	}
	
	public function showCurrentUserLocations()
	{
		$user = BaseUser::getDbUser();

		$locations = DB::table('location')->where('creator_user_id', '=', $user->id)->orderBy('name')->get();
		
		# Calculate list of locations that can safely be hard-deleted.
		$location_ids = [];
		foreach ($locations as $location)
		{
			$location_ids []= $location->id;
		}
		
		$locations_unsafe_to_delete = DB::table('user_answer')
			->distinct()->select('location_id')
			->where('answered_by_user_id', '<>', $user->id)
			->whereIn('location_id', $location_ids)
			->pluck('location_id')->toArray();
			
		$location_ids = array_diff($location_ids, $locations_unsafe_to_delete);
		
		$locations_unsafe_to_delete = DB::table('review_comment')
			->distinct()->select('location_id')
			->where('answered_by_user_id', '<>', $user->id)
			->whereIn('location_id', $location_ids)
			->pluck('location_id')->toArray();

		$location_ids = array_diff($location_ids, $locations_unsafe_to_delete);

		foreach ($locations as $location)
		{
			$location->is_safe_to_delete = in_array($location->id, $location_ids);
		}

		$view_data = [
			'locations' => $locations
		];
		return view('pages.location_management.locations_added_by_me', $view_data);
	}
	
	public function deleteMyLocation(string $location_id)
	{
		$user = BaseUser::getDbUser();
		$location = DB::table('location')->find($location_id);
		if ( !$user || $user->id !== $location->creator_user_id )
		{
			throw new AuthenticationException('Must be the same user that created the location to delete it');
		}
		$answered_by_other_user = DB::table('user_answer')
			->where('answered_by_user_id', '<>', $user->id)
			->where('location_id', '=', $location->id)
			->count();
		$commented_by_other_user = DB::table('review_comment')
			->where('location_id', '=', $location->id)
			->where('answered_by_user_id', '<>', $user->id)
			->count();
		if ( $answered_by_other_user + $commented_by_other_user > 0 )
		{
			throw new AuthenticationException('Can not delete location because it was rated by another user');
		}

		// Delete records from child tables.
		DB::transaction(function() use($location)
		{
			DB::table('user_answer')->where('location_id', '=', $location->id)->delete();
			DB::table('review_comment')->where('location_id', '=', $location->id)->delete();
			DB::table('location')->where('id', '=', $location->id)->delete();
		});
		return Redirect('/locations-added-by-me');
	}
	
    public function show(string $location_id)
    {
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$location = Location::find($location_id);
		if ( !$location )
			return view('pages.location_management.not_found');

		$view_data = [
			'location' => $location,
			'location_groups' => DB::table('location_group')->orderBy('name')->get(),
			'location_tags' => DB::table('location_tag')->orderBy('name')->get(),
			'associated_location_tag_ids' => $location->getLocationTagIds(),
			'data_sources' => DB::table('data_source')->orderBy('name')->get()
		];
		return view('pages.location_management.modify', $view_data);
    }

}