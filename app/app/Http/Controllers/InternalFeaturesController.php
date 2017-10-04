<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Auth\AuthenticationException;
use DB;

// FIXME: move this into central place to be used by 
// LocationSearchController and InternalFeaturesController.
function compareByName($named1, $named2)
{
	return strcasecmp($named1->name, $named2->name);
}

class InternalFeaturesController extends Controller
{
	public function showMapVisualizer()
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		
		$location_data = DB::table('location')->get();
		$view_data = [
			'locations' => json_encode($location_data),
			'google_map_api_key' => config('app.google_map_api_key')
		];
		
		return view('pages.internal_features.map_location_visualizer', $view_data);
	}
	
	public function showLocationGroupsReport()
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$location_groups_with_locations = DB::table('location_group')
				->join('location as loc', 'loc.location_group_id', '=', 'location_group.id')
				->orderBy('location_group.name')
				->groupBy('location_group.id')
				->get(['location_group.id', 'location_group.name',
					DB::raw('count(loc.id) as num_locations')])
				->toArray();
		$location_group_ids = [];
		foreach ($location_groups_with_locations as $location_group_with_location)
		{
			$location_group_ids []= $location_group_with_location->id;
		}
		$location_groups_without_locations = DB::table('location_group')
				->whereNotIn('id', $location_group_ids)
				->get(['id', 'location_group.name', DB::raw('0 as num_locations')])
				->toArray();
		$location_groups = array_merge(
			$location_groups_without_locations,
			$location_groups_with_locations);
		usort($location_groups, 'App\Http\Controllers\compareByName');
		$view_data = [
			'location_groups' => $location_groups
		];
		
		return view('pages.internal_features.location_groups_report', $view_data);
	}
	
	public function showUserReport(string $user_id)
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$user = User::find($user_id);
		$home_country_name = ( $user->home_country_id ? $user->home_country()->first()->name : '');
		$num_rating_submissions = count(DB::table('user_answer')
			->where('answered_by_user_id', '=', $user_id)
			->groupBy(['when_submitted', 'location_id'])
			->get(['when_submitted', 'location_id']));
		$num_rated_locations = DB::table('user_answer')
			->where('answered_by_user_id', '=', $user_id)
			->distinct('location_id')
			->count('location_id');
		$num_created_locations = DB::table('location')
			->where('creator_user_id', '=', $user_id)
			->count();
		$view_data = [
			'user' => $user,
			'home_country_name' => $home_country_name,
			'num_comments' => DB::table('review_comment')->where('answered_by_user_id', '=', $user_id)->count(),
			'num_answers' => DB::table('user_answer')->where('answered_by_user_id', '=', $user_id)->count(),
			'num_rating_submissions' => $num_rating_submissions,
			'num_rated_locations' => $num_rated_locations,
			'num_created_locations' => $num_created_locations
		];
		
		return view('pages.internal_features.user_report', $view_data);
	}
	
	public function showDashboard()
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$num_user_created_locations = DB::table('location')->whereNotNull('creator_user_id')->count();
		$num_no_phone_locations = DB::table('location')->whereNull('phone_number')->orWhere('phone_number', '=', '')->count();
		$num_no_address_locations = DB::table('location')->whereNull('address')->orWhere('address', '=', '')->count();
		
		$location_tags = DB::table('location_tag')->orderBy('name')->get();
		foreach ($location_tags as $location_tag)
		{
			$location_tag->num_locations = DB::table('location_location_tag')->where('location_tag_id', '=', $location_tag->id)->count();
		}
		
		$view_data = [
		'num_users' => DB::table('user')->count(),
		'num_users_using_screen_readers' => DB::table('user')->where('uses_screen_reader', '=', 1)->count(),
		'num_locations' => DB::table('location')->count(),
		'num_user_created_locations' => $num_user_created_locations,
		'num_no_address_locations' => $num_no_address_locations,
		'num_no_phone_locations' => $num_no_phone_locations,
		'num_location_groups' => DB::table('location_group')->count(),
		'num_rating_submissions' => count(DB::table('user_answer')
			->groupBy(['when_submitted', 'answered_by_user_id', 'location_id'])
			->get(['when_submitted', 'answered_by_user_id', 'location_id'])),
		'num_rated_locations' => DB::table('user_answer')->distinct('location_id')->count('location_id'),
		'num_comments' => DB::table('review_comment')->count(),
		'num_data_sources' => DB::table('data_source')->count(),
		'location_tags' => $location_tags
		];

		return view('pages.internal_features.dashboard', $view_data);
	}
	
	public function showUsers()
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$view_data = [
			'users' => DB::table('user')->orderBy('email')->get()
		];
		return view('pages.internal_features.users', $view_data);
	}
}