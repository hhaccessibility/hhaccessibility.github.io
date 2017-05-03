<?php namespace App\Http\Controllers;

use App\Location;
use App\BaseUser;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use DB;

class LocationManagementController extends Controller {
	public function showUserReport($user_id)
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$user = User::find($user_id);
		$home_country_name = ( $user->home_country_id ? $user->home_country()->first()->name : '');
		$view_data = [
			'user' => $user,
			'home_country_name' => $home_country_name,
			'num_comments' => DB::table('review_comment')->where('answered_by_user_id', '=', $user_id)->count(),
			'num_answers' => DB::table('user_answer')->where('answered_by_user_id', '=', $user_id)->count()
		];
		
		return view('pages.internal_features.user_report', $view_data);
	}
	
	public function showDashboard()
	{
		if ( !BaseUser::isInternal() ) {
			throw new AuthenticationException('Must be internal user');
		}
		$view_data = [
		'num_users' => DB::table('user')->count(),
		'num_locations' => DB::table('location')->count(),
		'num_location_groups' => DB::table('location_group')->count(),
		'num_rating_submissions' => count(DB::table('user_answer')
			->groupBy(['when_submitted', 'answered_by_user_id', 'location_id'])
			->get(['when_submitted', 'answered_by_user_id', 'location_id'])),
		'num_rated_locations' => DB::table('user_answer')->distinct('location_id')->count('location_id'),
		'num_comments' => DB::table('review_comment')->count(),
		'num_data_sources' => DB::table('data_source')->count()
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