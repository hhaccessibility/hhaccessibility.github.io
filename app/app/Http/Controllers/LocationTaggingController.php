<?php namespace App\Http\Controllers;

use App\BaseUser;
use App\Location;
use App\LocationTag;
use App\LocationLocationTag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use DB;

class LocationTaggingController extends Controller
{
    public function search(Request $request)
    {
        if (!BaseUser::isSignedIn()) {
            $message = "You+must+sign+in+to+tag+a+location";
            $afterSignInRedirect = "/location/tagging";
            return redirect()->intended("/signin?message=$message&after_signin_redirect=$afterSignInRedirect");
        }
		$offset = 0;
		$page_size = 100;
		if (Input::has('page_size') && is_numeric(Input::get('page_size'))) {
			$page_size = intval(Input::get('page_size'));
			$page_size = max(5, $page_size);
		}
		if (Input::has('offset') && is_numeric(Input::get('offset'))) {
			$offset = intval(Input::get('offset'));
			$offset = max(0, $offset);
		}
		$query = Location::getLocationsWithoutTag();
        $total_num_locations_matched = $query->count();
		$locations = $query->orderBy('name')->offset($offset)->limit($page_size)->get();
		$location_tags = LocationTag::all();
        return view('pages.internal_features.location_tagging', [
			'page_size' => $page_size,
			'offset' => $offset,
            'locations' => $locations,
			'total_num_locations_matched' => $total_num_locations_matched, 
			'location_tags' => $location_tags
        ]);
    }

	private function validateTagToggleRequest(Request $request)
	{
        if (!BaseUser::isInternal()) {
            throw new AuthenticationException('Must be internal user');
        }
        $validation_rules = [
            'location_tag_id' => 'required|integer|min:1',
            'location_id' => 'required'
        ];
        $validator = Validator::make(Input::all(), $validation_rules);
        if ($validator->fails()) {
            return response(422)->json(['success' => false]);
        }
	}

	public function addTag(Request $request)
	{
		$validationResult = $this->validateTagToggleRequest($request);
		if ($validationResult) {
			return $validationResult;
		}
		// insert.
		$new_location_location_tag = new LocationLocationTag();
		$new_location_location_tag->location_id = Input::get('location_id');
		$new_location_location_tag->location_tag_id = Input::get('location_tag_id');
		$new_location_location_tag->save();
		
        return response()->json(['success' => true]);			
	}

	public function removeTag(Request $request)
	{
		$validationResult = $this->validateTagToggleRequest($request);
		if ($validationResult) {
			return $validationResult;
		}
		LocationLocationTag::
			where('location_id', '=', Input::get('location_id'))->
			where('location_tag_id', '=', Input::get('location_tag_id'))->
			delete();
        return response()->json(['success' => true]);			
	}
}
