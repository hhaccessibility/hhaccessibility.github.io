<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Response;
use DateTime;
use DateTimeZone;
use App\Location;
use App\BaseUser;
use App\Suggestion;
use App\User;
use DB;

class SuggestionController extends Controller
{
    public function addSuggestion(Request $request)
    {
        // user needs to sign in before sending suggestions
        if (!BaseUser::isSignedIn()) {
            return  Response::json([
                'success' => 0,
                'message' => "Not signed in."
            ], 403);
        }
        $user = BaseUser::getDbUser();
        // validate data from front end
        $validation_rules = array(
            'location-id'           => 'required',
            'location-name'         => 'between:2,255|required',
            'phone-number'          => 'max:50',
            'url'                   => 'max:255|url',
            'address'               => 'max:255'

        );
        $validator = Validator::make(Input::all(), $validation_rules);
        if ($validator->fails()) {
            return Response::json([
                'success' => 0,
                'message' => $validator->errors()
            ], 422);
        }

        //Fetch the message
        $location_id = $request->get('location-id');
        $location_name = $request->get('location-name');
        $phone_number = $request->get('phone-number');
        $url = $request->get('url');
        $address = $request->get('address');

        $location = Location::where('id', '=', $location_id)->first();
        //Return to home page if the location-id doesn't exist in the database or the phone number is invalid
        if (!$location) {
            return Response::json([
                'success' => 0,
                'message' => "Location doesn't exist."
            ], 422);
        }
        if ($phone_number !== '' && preg_match_all("/[0-9]/", $phone_number) < 9) {
            return Response::json([
                'success' => 0,
                'message' => "Phone number is invalid.  It must include area code."
            ], 422);
        }
        //Add a new record in the database
        $suggestion = new Suggestion;
        $suggestion->location_id = $request->input('location-id');
        $suggestion->location_name = $request->input('location-name');
        $suggestion->location_phone_number = $request->input('phone-number');
        $suggestion->location_address = $request->input('address');
        $suggestion->location_external_web_url = $request->input('url');
        $suggestion->user_id = $user->id;
        $suggestion->when_generated = new DateTime('now', new DateTimeZone('UTC'));
        $suggestion->save();

        return Response::json([
            'success' => 1
        ], 200);
    }

    public function showSuggestionList(string $location_id)
    {
        $location_name = Location::find($location_id)->name;
        $suggestions = DB::table('suggestion')
                        ->where('location_id', '=', $location_id)
                        ->get([
                            'id',
                            'user_id',
                            'when_generated']);
        foreach ($suggestions as $suggestion) {
            $user = User::find($suggestion->user_id);
            $suggestion->user_name = $user->first_name." ".$user->last_name;
        }
        $view_data = [
            'suggestions' => $suggestions,
            'name' => $location_name
        ];
        return view('pages.location_management.suggestion_list', $view_data);
    }

    public function showSuggestionDetail(string $suggestion_id)
    {
        $suggestion = Suggestion::find($suggestion_id);
        $location = Location::find($suggestion->location_id);
        $user = User::find($suggestion->user_id);
        $user_name = $user->first_name." ".$user->last_name;
        $view_data = [
            'suggestion' => $suggestion,
            'user_name' => $user_name,
            'location_name' => $location->name,
            'original_location' => $location
        ];
        return view('pages.location_management.suggestion_detail', $view_data);
    }
}
