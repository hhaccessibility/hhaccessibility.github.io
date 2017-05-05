<?php namespace App\Http\Controllers;

use App\Location;
use App\BaseUser;
use App\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use DB;

class LocationManagementController extends Controller {
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