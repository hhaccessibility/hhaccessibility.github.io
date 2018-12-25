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
use App\Role;
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
            'success' => 1,
            'id' => $suggestion->id
        ], 200);
    }

    private function getSuggestionsFor($location_id)
    {
        if (!$location_id && BaseUser::isSignedIn()) {
            if (!BaseUser::isInternal()) {
                $db_user = BaseUser::getDbUser();
                $location_ids = Location::where('creator_user_id', '=', $db_user->id)->get();
                if (count($location_ids) === 1) {
                    $location_id = $location_ids[0];
                } elseif (count($location_ids) === 0) {
                    return ['suggestions' => [], 'name' => ''];
                }
            }
        } else {
            $location_ids = [$location_id];
        }
        $columns = ['suggestion.id', 'suggestion.user_id', 'suggestion.when_generated',
            DB::raw('concat(user.first_name, \' \', user.last_name) as user_name')];
        $suggestions = Suggestion::join('user', 'user.id', '=', 'suggestion.user_id')->where('deleted_at', '=', null);
        if ($location_id === null) {
            $location_name = '';
            $suggestions = $suggestions->join('location', 'location.id', '=', 'suggestion.location_id');
        } else {
            $location_name = Location::find($location_id)->name;
        }
        if (isset($location_ids)) {
            $suggestions = $suggestions->whereIn('location_id', $location_ids);
        }
        if (!$location_name) {
            $columns []= DB::raw('location.name as location_name');
        }
        $suggestions = $suggestions->get($columns);
        return ['suggestions' => $suggestions, 'name' => $location_name];
    }

    public function showSuggestionList($location_id = null)
    {
        $suggestionsInfo = $this->getSuggestionsFor($location_id);
        $view_data = [
            'suggestions' => $suggestionsInfo['suggestions'],
            'name' => $suggestionsInfo['name']
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

    public function accept(string $suggestion_id, string $fieldname)
    {
        $result = $this->validateSuggestionAndUser($suggestion_id);
        if ($result instanceof Suggestion) {
            $suggestion = $result;
        } elseif ($result) {
            return $result;
        }
        $location = Location::find($suggestion->location_id);
        $all_fields = ['phone_number', 'address', 'name', 'external_web_url'];
        $fields = [];
        if ($fieldname === 'all') {
            $fields = $all_fields;
        } elseif (in_array($fieldname, $all_fields)) {
            $fields = [$fieldname];
        } else {
            return  Response::json([
                'success' => false,
                'message' => "Invalid field name"
            ], 403);
        }
        foreach ($fields as $field) {
            $location->{$field} = $suggestion->{'location_' . $field};
        }
        $location->save();
    }

    private function validateSuggestionAndUser(string $suggestion_id)
    {
        if (!BaseUser::isSignedIn()) {
            return Response::json([
                'success' => false,
                'message' => "Not signed in."
            ], 403);
        }
        $user = BaseUser::getDbUser();
        $suggestion = Suggestion::find($suggestion_id);
        if (!$suggestion) {
            return Response::json([
                'success' => false,
                'message' => "Suggestion not found matching specified suggestion id."
            ], 404);
        }
        if ($suggestion->deleted_at) {
            return Response::json([
                'success' => false,
                'message' => "Suggestion already marked as resolved."
            ], 422);
        }
        if (!$user->hasRole(Role::INTERNAL)) {
            $location = Location::
                where('creator_user_id', '=', $user->id)->
                where('id', '=', $suggestion->location_id)->
                get(['id']);
            if (!$location) {
                return Response::json([
                    'success' => false,
                    'message' =>
                      'Not allowed to update location because you did not create it.'
                ], 403);
            }
        }
        return $suggestion;
    }

    public function markSuggestionAsResolved(string $suggestion_id)
    {
        $result = $this->validateSuggestionAndUser($suggestion_id);
        if ($result instanceof Suggestion) {
            $suggestion = $result;
        } elseif ($result) {
            return $result;
        }
        $suggestion->delete();
        return Response::json([
            'success' => true
        ]);
    }
}
